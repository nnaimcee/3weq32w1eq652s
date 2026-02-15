<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Location;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    // 1. หน้าจอส่งของออก (ต้นทาง)
    public function create(){
    // ดึงข้อมูลแบบจัดกลุ่มตาม Product และ Location แล้วเอาจำนวน (quantity) มาบวกกัน
    $stocks = \App\Models\Stock::with(['product', 'location'])
        ->select('product_id', 'location_id', \DB::raw('SUM(quantity) as total_quantity'))
        ->where('quantity', '>', 0)
        ->groupBy('product_id', 'location_id')
        ->get();

    return view('transfer.send', compact('stocks'));
}

    // 2. Logic ส่งของไปที่ Transit (ตัดจากที่เก็บเดิม)
   public function send(Request $request)
{
    $request->validate([
        'product_id' => 'required',
        'location_id' => 'required',
        'quantity' => 'required|integer|min:1',
    ]);

    $transitLocation = \App\Models\Location::where('type', 'transit')->first();
    $requestedQty = $request->quantity;

    // ตรวจสอบสต็อกรวมในตำแหน่งนั้นว่าพอไหม
    $totalInLocation = \App\Models\Stock::where('product_id', $request->product_id)
        ->where('location_id', $request->location_id)
        ->sum('quantity');

    if ($totalInLocation < $requestedQty) {
        return back()->withErrors(['จำนวนสินค้าในตำแหน่งนี้ไม่พอให้ย้าย']);
    }

    \DB::transaction(function () use ($request, $transitLocation, $requestedQty) {
        // ดึงรายการสต็อกในตำแหน่งนั้น เรียงตามวันที่รับเข้า (FIFO)
        $locationStocks = \App\Models\Stock::where('product_id', $request->product_id)
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
            \App\Models\Stock::create([
                'product_id' => $stock->product_id,
                'location_id' => $transitLocation->id,
                'quantity' => $moveAmount,
                'received_date' => $stock->received_date,
                'lot_number' => $stock->lot_number,
            ]);

            $remainingToMove -= $moveAmount;
        }

        // 3. บันทึกประวัติ
        \App\Models\Transaction::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'from_location_id' => $request->location_id,
            'to_location_id' => $transitLocation->id,
            'quantity' => $request->quantity,
            'type' => 'TRANSFER',
            'status' => 'pending'
        ]);
    });

    return redirect()->route('transfer.pending')->with('success', '🚚 ส่งสินค้าไปยังพื้นที่ Transit แล้ว');
}

    // 3. หน้าจอรายการที่ค้างอยู่ใน Transit (รอรับเข้า)
    public function pending()
    {
        $transitLocation = Location::where('type', 'transit')->first();
        $stocksInTransit = Stock::where('location_id', $transitLocation->id)
                                ->where('quantity', '>', 0)
                                ->with('product')
                                ->get();
        
        $destinations = Location::where('type', 'storage')->get();
        
        return view('transfer.pending', compact('stocksInTransit', 'destinations'));
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
            Transaction::where('product_id', $transitStock->product_id)
                        ->where('type', 'TRANSFER')
                        ->where('status', 'pending')
                        ->latest()
                        ->first()
                        ->update(['status' => 'completed', 'to_location_id' => $request->to_location_id]);
        });

        return redirect()->back()->with('success', '✅ ย้ายสินค้าเข้าตำแหน่งใหม่เรียบร้อย!');
    }
}
