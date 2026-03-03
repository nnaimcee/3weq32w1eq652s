<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Location;
use App\Models\Transaction;
use App\Models\LocationReservation;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    // 1. หน้าจอส่งของออก (ต้นทาง)
    public function create()
    {
        $stocks = Stock::with(['product', 'location'])
            ->select('product_id', 'location_id', DB::raw('SUM(quantity) as total_quantity'))
            ->where('quantity', '>', 0)
            ->groupBy('product_id', 'location_id')
            ->get();

        // ✅ destination ต้องไม่ inactive และไม่มี pending reservation
        $reservedLocationIds = LocationReservation::where('status', 'pending')->pluck('location_id');
        $destinations = Location::where('type', 'storage')
            ->where('status', 'active')
            ->whereNotIn('id', $reservedLocationIds)
            ->get();

        return view('transfer.send', compact('stocks', 'destinations'));
    }

    // 2. Logic ส่งของไปที่ Transit พร้อมระบุปลายทาง
    public function send(Request $request)
    {
        $request->validate([
            'product_id'    => 'required',
            'location_id'   => 'required',
            'to_location_id'=> 'required|exists:locations,id',
            'quantity'      => 'required|integer|min:1',
        ]);

        if ($request->location_id == $request->to_location_id) {
            return back()->withErrors(['ไม่สามารถย้ายไปตำแหน่งเดิมได้']);
        }

        $transitLocation = Location::where('type', 'transit')->first();
        if (!$transitLocation) {
            return back()->withErrors(['ไม่พบพื้นที่ Transit ในระบบ กรุณาสร้างก่อน']);
        }

        $destination    = Location::findOrFail($request->to_location_id);
        $requestedQty   = (int) $request->quantity;

        // ✅ Bug #2: ตรวจ destination ว่ามี pending reservation หรือ full หรือ inactive
        if ($destination->status === 'inactive') {
            return back()->withErrors(["❌ ปลายทาง '{$destination->name}' ถูกปิดใช้งาน"]);
        }
        if ($destination->status === 'full') {
            return back()->withErrors(["❌ ปลายทาง '{$destination->name}' เต็มแล้ว ไม่สามารถรับสินค้าได้"]);
        }
        $pendingRes = LocationReservation::where('location_id', $destination->id)->where('status', 'pending')->first();
        if ($pendingRes) {
            return back()->withErrors(["🔖 ปลายทาง '{$destination->name}' ถูกจองรอสินค้าเข้าอยู่ กรุณาเลือกตำแหน่งอื่น"]);
        }

        // ✅ Bug #2: ตรวจ capacity ปลายทาง
        $destCapacity   = $destination->capacity ?? 5000;
        $destCurrentQty = $destination->stocks()->sum('quantity');
        if ($destCurrentQty + $requestedQty > $destCapacity) {
            $remaining = $destCapacity - $destCurrentQty;
            return back()->withErrors(["❌ ปลายทาง '{$destination->name}' รับได้อีกแค่ {$remaining} ชิ้น (capacity: {$destCapacity})"]);
        }

        // ตรวจสอบสต็อกต้นทาง
        $totalInLocation = Stock::where('product_id', $request->product_id)
            ->where('location_id', $request->location_id)
            ->sum('quantity');

        if ($totalInLocation < $requestedQty) {
            return back()->withErrors(['จำนวนสินค้าในตำแหน่งนี้ไม่พอให้ย้าย (เหลือ ' . $totalInLocation . ' ชิ้น)']);
        }

        // ✅ Bug #4: ตรวจว่า reserved_qty จะเกินจากการ deduct หรือไม่
        $availableInSource = Stock::where('product_id', $request->product_id)
            ->where('location_id', $request->location_id)
            ->selectRaw('SUM(quantity - reserved_qty) as avail')
            ->value('avail') ?? 0;

        if ($availableInSource < $requestedQty) {
            return back()->withErrors(["❌ สินค้าในตำแหน่งต้นทางถูกกันไว้ (พร้อมย้ายแค่ {$availableInSource} ชิ้น)"]);
        }

        $movedLots = []; // เก็บ lot ที่ย้าย เพื่อ checkAndUpdateStatus ต้นทาง

        DB::transaction(function () use ($request, $transitLocation, $requestedQty, &$movedLots) {
            $locationStocks = Stock::where('product_id', $request->product_id)
                ->where('location_id', $request->location_id)
                ->where('quantity', '>', 0)
                ->orderBy('received_date', 'asc')
                ->get();

            $remainingToMove = $requestedQty;

            foreach ($locationStocks as $stock) {
                if ($remainingToMove <= 0) break;

                // ✅ Bug #4: ย้ายเฉพาะส่วนที่ไม่ได้ reserve
                $availableInLot = $stock->quantity - $stock->reserved_qty;
                if ($availableInLot <= 0) continue;

                $moveAmount = min($availableInLot, $remainingToMove);

                // ลดจาก source (ไม่แตะ reserved_qty เพราะเราย้าย non-reserved เท่านั้น)
                $stock->decrement('quantity', $moveAmount);

                // ย้ายไป Transit
                Stock::create([
                    'product_id'    => $stock->product_id,
                    'location_id'   => $transitLocation->id,
                    'quantity'      => $moveAmount,
                    'received_date' => $stock->received_date,
                    'lot_number'    => $stock->lot_number,
                ]);

                $movedLots[] = $stock->lot_number;
                $remainingToMove -= $moveAmount;
            }

            Transaction::create([
                'user_id'          => auth()->id(),
                'product_id'       => $request->product_id,
                'from_location_id' => $request->location_id,
                'to_location_id'   => $request->to_location_id,
                'quantity'         => $request->quantity,
                'type'             => 'TRANSFER',
                'status'           => 'pending',
                'notes'            => 'กำลังจัดส่ง → ' . Location::find($request->to_location_id)->name,
            ]);
        });

        // ✅ checkAndUpdateStatus ต้นทาง (อาจเปลี่ยนจาก full → active)
        Location::findOrFail($request->location_id)->checkAndUpdateStatus();

        $destName = Location::find($request->to_location_id)->name;
        return redirect()->route('transfer.pending')->with('success', "🚚 ส่งสินค้าไป Transit แล้ว — ปลายทาง: {$destName}");
    }

    // 3. หน้าจอรายการที่ค้างอยู่ใน Transit
    public function pending()
    {
        $transitLocation = Location::where('type', 'transit')->first();

        $stocksInTransit = Stock::where('location_id', $transitLocation->id)
            ->where('quantity', '>', 0)
            ->with('product')
            ->get();

        $pendingTransactions = Transaction::where('type', 'TRANSFER')
            ->where('status', 'pending')
            ->with(['product', 'user', 'fromLocation', 'toLocation'])
            ->orderBy('created_at', 'desc')
            ->get();

        $destinations = Location::where('type', 'storage')->where('status', 'active')->get();

        return view('transfer.pending', compact('stocksInTransit', 'pendingTransactions', 'destinations'));
    }

    // 4. Logic ยืนยันรับของจาก Transit เข้าที่ใหม่
    public function receive(Request $request)
    {
        $request->validate([
            'stock_id'       => 'required|exists:stocks,id',
            'to_location_id' => 'required|exists:locations,id',
        ]);

        $transitStock   = Stock::findOrFail($request->stock_id);
        $destination    = Location::findOrFail($request->to_location_id);
        $incomingQty    = $transitStock->quantity;

        // ✅ Bug #3: ตรวจ capacity ปลายทางก่อนรับ
        $destCapacity   = $destination->capacity ?? 5000;
        $destCurrentQty = $destination->stocks()->sum('quantity');

        if ($destCurrentQty + $incomingQty > $destCapacity) {
            $remaining = $destCapacity - $destCurrentQty;
            return back()->withErrors(["❌ ปลายทาง '{$destination->name}' รับได้อีกแค่ {$remaining} ชิ้น"]);
        }

        DB::transaction(function () use ($transitStock, $request) {
            Stock::create([
                'product_id'    => $transitStock->product_id,
                'location_id'   => $request->to_location_id,
                'quantity'      => $transitStock->quantity,
                'received_date' => $transitStock->received_date,
                'lot_number'    => $transitStock->lot_number,
            ]);

            $transitStock->delete();

            // ✅ Bug #5: match transaction ด้วย product_id + lot_number เพื่อกัน resolve ผิดรายการ
            $transaction = Transaction::where('product_id', $transitStock->product_id)
                ->where('type', 'TRANSFER')
                ->where('status', 'pending')
                ->when($transitStock->lot_number, function($q) use ($transitStock) {
                    $q->where('lot_number', $transitStock->lot_number);
                })
                ->latest()
                ->first();

            if ($transaction) {
                $userName = auth()->user()->name ?? 'System';
                $transaction->update([
                    'status'         => 'completed',
                    'to_location_id' => $request->to_location_id,
                    'notes'          => '✅ รับของเรียบร้อย → ' . Location::find($request->to_location_id)->name . " (รับโดย: {$userName})",
                ]);
            }
        });

        // ✅ Bug #3: checkAndUpdateStatus ปลายทาง (auto-set full ถ้า qty >= capacity)
        $destination->refresh();
        $destination->checkAndUpdateStatus();

        return redirect()->back()->with('success', '✅ ย้ายสินค้าเข้าตำแหน่งใหม่เรียบร้อย!');
    }
}
