<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Location;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    // 1. หน้าจอส่งของออก (ต้นทาง) — ระบุปลายทางตั้งแต่ตอนส่ง
    public function create()
    {
        $stocks = Stock::with(['product', 'location'])
            ->select('product_id', 'location_id', DB::raw('SUM(quantity) as total_quantity'))
            ->where('quantity', '>', 0)
            ->groupBy('product_id', 'location_id')
            ->get();

        // ดึง Location ปลายทาง (เฉพาะ storage ที่ active)
        $destinations = Location::where('type', 'storage')->where('status', 'active')->get();

        return view('transfer.send', compact('stocks', 'destinations'));
    }

    // 2. Logic ส่งของไปที่ Transit พร้อมระบุปลายทาง
    public function send(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'location_id' => 'required',
            'to_location_id' => 'required|exists:locations,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // ห้ามส่งไปที่เดิม
        if ($request->location_id == $request->to_location_id) {
            return back()->withErrors(['ไม่สามารถย้ายไปตำแหน่งเดิมได้']);
        }

        $transitLocation = Location::where('type', 'transit')->first();

        if (!$transitLocation) {
            return back()->withErrors(['ไม่พบพื้นที่ Transit ในระบบ กรุณาสร้างก่อน']);
        }

        $requestedQty = $request->quantity;

        // ตรวจสอบสต็อกรวมในตำแหน่งนั้นว่าพอไหม
        $totalInLocation = Stock::where('product_id', $request->product_id)
            ->where('location_id', $request->location_id)
            ->sum('quantity');

        if ($totalInLocation < $requestedQty) {
            return back()->withErrors(['จำนวนสินค้าในตำแหน่งนี้ไม่พอให้ย้าย (เหลือ ' . $totalInLocation . ' ชิ้น)']);
        }

        DB::transaction(function () use ($request, $transitLocation, $requestedQty) {
            // ดึงรายการสต็อกในตำแหน่งนั้น เรียงตามวันที่รับเข้า (FIFO)
            $locationStocks = Stock::where('product_id', $request->product_id)
                ->where('location_id', $request->location_id)
                ->where('quantity', '>', 0)
                ->orderBy('received_date', 'asc')
                ->get();

            $remainingToMove = $requestedQty;

            foreach ($locationStocks as $stock) {
                if ($remainingToMove <= 0) break;

                $moveAmount = min($stock->quantity, $remainingToMove);

                // 1. ลดจำนวนจากที่เดิม
                $stock->decrement('quantity', $moveAmount);

                // 2. ย้ายไป Transit (สร้างล็อตใหม่โดยรักษาเวลา FIFO เดิมไว้)
                Stock::create([
                    'product_id' => $stock->product_id,
                    'location_id' => $transitLocation->id,
                    'quantity' => $moveAmount,
                    'received_date' => $stock->received_date,
                    'lot_number' => $stock->lot_number,
                ]);

                $remainingToMove -= $moveAmount;
            }

            // 3. บันทึกประวัติ พร้อมระบุปลายทางเลย
            Transaction::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'from_location_id' => $request->location_id,
                'to_location_id' => $request->to_location_id,
                'quantity' => $request->quantity,
                'type' => 'TRANSFER',
                'status' => 'pending',
                'notes' => 'กำลังจัดส่ง → ' . Location::find($request->to_location_id)->name,
            ]);
        });

        return redirect()->route('transfer.pending')->with('success', '🚚 ส่งสินค้าไป Transit แล้ว — ปลายทาง: ' . Location::find($request->to_location_id)->name);
    }

    // 3. หน้าจอรายการที่ค้างอยู่ใน Transit (รอรับเข้า)
    public function pending()
    {
        $transitLocation = Location::where('type', 'transit')->first();

        // ดึง stock ที่อยู่ใน transit
        $stocksInTransit = Stock::where('location_id', $transitLocation->id)
                                ->where('quantity', '>', 0)
                                ->with('product')
                                ->get();

        // ดึง transaction ที่ pending เพื่อแสดงข้อมูลปลายทาง/ผู้ส่ง
        $pendingTransactions = Transaction::where('type', 'TRANSFER')
            ->where('status', 'pending')
            ->with(['product', 'user', 'fromLocation', 'toLocation'])
            ->orderBy('created_at', 'desc')
            ->get();

        // จับคู่ stock กับ transaction ด้วย product_id
        $destinations = Location::where('type', 'storage')->where('status', 'active')->get();

        return view('transfer.pending', compact('stocksInTransit', 'pendingTransactions', 'destinations'));
    }

    // 4. Logic ยืนยันรับของจาก Transit เข้าที่ใหม่
    public function receive(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'to_location_id' => 'required|exists:locations,id',
        ]);

        $transitStock = Stock::findOrFail($request->stock_id);

        DB::transaction(function () use ($transitStock, $request) {
            // ย้ายจาก Transit ไปปลายทางที่เลือก
            Stock::create([
                'product_id' => $transitStock->product_id,
                'location_id' => $request->to_location_id,
                'quantity' => $transitStock->quantity,
                'received_date' => $transitStock->received_date,
                'lot_number' => $transitStock->lot_number,
            ]);

            // ลบยอดจาก Transit
            $transitStock->delete();

            // อัปเดตสถานะ Transaction ล่าสุดของไอเทมนี้
            $transaction = Transaction::where('product_id', $transitStock->product_id)
                        ->where('type', 'TRANSFER')
                        ->where('status', 'pending')
                        ->latest()
                        ->first();

            if ($transaction) {
                $userName = auth()->user()->name ?? 'System';
                $transaction->update([
                    'status' => 'completed',
                    'to_location_id' => $request->to_location_id,
                    'notes' => '✅ รับของเรียบร้อย → ' . Location::find($request->to_location_id)->name . ' (รับโดย: ' . $userName . ')',
                ]);
            }
        });

        return redirect()->back()->with('success', '✅ ย้ายสินค้าเข้าตำแหน่งใหม่เรียบร้อย!');
    }
}
