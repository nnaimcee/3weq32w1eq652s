<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class OutboundController extends Controller
{
    // เปิดหน้าฟอร์มเบิกสินค้า
    public function create()
    {
        return view('outbound.create');
    }

    // ฟังก์ชันคำนวณและตัดสต็อก FIFO
    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|exists:products,barcode',
            'quantity' => 'required|integer|min:1',
        ], [
            'barcode.exists' => 'ไม่พบสินค้านี้ในระบบ'
        ]);

        $product = Product::where('barcode', $request->barcode)->first();
        $requestedQty = $request->quantity;

        // 1. เช็คก่อนว่ามีสต็อกรวมพอให้เบิกไหม?
        // ต้องหักลบจำนวนที่ถูกจอง (reserved_qty) และไม่นับสต็อกที่อยู่ใน Transit
        $transitLocationIds = \App\Models\Location::where('type', 'transit')->pluck('id');
        $totalStock = Stock::where('product_id', $product->id)
            ->whereNotIn('location_id', $transitLocationIds)
            ->sum('quantity');
        $totalReserved = Stock::where('product_id', $product->id)
            ->whereNotIn('location_id', $transitLocationIds)
            ->sum('reserved_qty');
        
        $availableStock = $totalStock - $totalReserved;

        if ($availableStock < $requestedQty) {
            return back()->withErrors(['สินค้ามีไม่พอให้เบิก! (เหลือพร้อมเบิก ' . $availableStock . ' ชิ้น | จองไว้ ' . $totalReserved . ' ชิ้น)']);
        }

        // 2. เริ่มกระบวนการตัดสต็อกแบบ FIFO
        $deductedLocationIds = [];

        DB::transaction(function () use ($product, $requestedQty, $transitLocationIds, &$deductedLocationIds) {

            $stocks = Stock::where('product_id', $product->id)
                           ->whereNotIn('location_id', $transitLocationIds)
                           ->where('quantity', '>', 0)
                           ->orderBy('received_date', 'asc')
                           ->get();

            $remainingToPick = $requestedQty;

            foreach ($stocks as $stock) {
                if ($remainingToPick <= 0) break;

                $toDeduct = min($stock->quantity, $remainingToPick);
                $reservedToRelease = min($stock->reserved_qty, $toDeduct);

                $stock->quantity     -= $toDeduct;
                $stock->reserved_qty  = max(0, $stock->reserved_qty - $reservedToRelease);
                $stock->save();

                // ✅ Bug #10: track เฉพาะ location ที่ถูก deduct จริง
                $deductedLocationIds[] = $stock->location_id;

                $remainingToPick -= $toDeduct;
            }

            Transaction::create([
                'user_id'    => auth()->id(),
                'product_id' => $product->id,
                'quantity'   => $requestedQty,
                'type'       => 'OUT',
            ]);
        });

        // ✅ Bug #10: checkAndUpdateStatus เฉพาะ location ที่ถูก deduct จริง
        \App\Models\Location::whereIn('id', array_unique($deductedLocationIds))
            ->get()
            ->each(fn($loc) => $loc->checkAndUpdateStatus());

        return redirect()->back()->with('success', '✅ เบิกสินค้าเรียบร้อยแล้ว (ระบบทำการตัดสต็อกแบบ FIFO สำเร็จ!)');



    }
}
