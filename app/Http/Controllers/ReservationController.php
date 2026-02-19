<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    // จองสินค้า (Reserve)
    public function reserve(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $requestedQty = $request->quantity;

        // 1. เช็คว่ามีของ "ว่างพร้อมจอง" พอไหม
        // (total_quantity - total_reserved) >= requested
        $totalStock = Stock::where('product_id', $product->id)->sum('quantity');
        $totalReserved = Stock::where('product_id', $product->id)->sum('reserved_qty');
        $availableToReserve = $totalStock - $totalReserved;

        if ($availableToReserve < $requestedQty) {
            return back()->withErrors(['สินค้ามีไม่พอให้จอง! (เหลือว่างรับจองแค่ ' . number_format($availableToReserve) . ' ชิ้น)']);
        }

        DB::transaction(function () use ($product, $requestedQty) {
            // 2. ดึง Lot ที่ยังมีของเหลือ และยังจองไม่เต็ม
            // เรียงตาม FIFO (received_date ASC)
            $stocks = Stock::where('product_id', $product->id)
                ->whereRaw('quantity > reserved_qty') // เอาเฉพาะที่มีของว่างจริง
                ->orderBy('received_date', 'asc')
                ->get();

            $remainingToReserve = $requestedQty;

            foreach ($stocks as $stock) {
                if ($remainingToReserve <= 0) break;

                // คำนวณว่า Lot นี้จองเพิ่มได้อีกกี่ชิ้น
                $availableInLot = $stock->quantity - $stock->reserved_qty;

                if ($availableInLot >= $remainingToReserve) {
                    // Lot นี้มีที่ว่างเหลือพอ หรือ มากกว่าที่ต้องการ -> จองให้ครบเลย
                    $stock->reserved_qty += $remainingToReserve;
                    $stock->save();
                    $remainingToReserve = 0;
                } else {
                    // Lot นี้มีที่ว่างไม่พอ -> จองทั้งหมดที่มี แล้วไป Lot ถัดไป
                    $availableToTake = $availableInLot; // แก้ไข: ใช้ตัวแปรนี้เพื่อความชัดเจน
                    $remainingToReserve -= $availableToTake;
                    $stock->reserved_qty += $availableToTake; 
                    $stock->save();
                }
            }

            // 3. บันทึก Transaction (RESERVE)
            // เช็คว่า model Transaction รองรับ type ที่ยาวกว่านี้ไหม (ปกติ string)
            Transaction::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $requestedQty,
                'type' => 'RESERVE', 
            ]);
        });

        return back()->with('success', '✅ จองสินค้าเรียบร้อยแล้ว');
    }

    // ปลดจองสินค้า (Release)
    public function release(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $requestedQty = $request->quantity;

        // 1. เช็คว่ามีการจองอยู่พอให้ปลดไหม
        $totalReserved = Stock::where('product_id', $product->id)->sum('reserved_qty');

        if ($totalReserved < $requestedQty) {
            return back()->withErrors(['ระบุจำนวนเกินกว่าที่จองไว้! (ปัจจุบันจองไว้ ' . number_format($totalReserved) . ' ชิ้น)']);
        }

        DB::transaction(function () use ($product, $requestedQty) {
            // 2. ไล่ปลดจอง (เอาจาก Lot ที่มี reserved_qty > 0)
            // FIFO หรือ LIFO ก็ได้ แต่ปกติปลดจากเก่าก่อนเหมือนกัน (FIFO)
            $stocks = Stock::where('product_id', $product->id)
                ->where('reserved_qty', '>', 0)
                ->orderBy('received_date', 'asc')
                ->get();

            $remainingToRelease = $requestedQty;

            foreach ($stocks as $stock) {
                if ($remainingToRelease <= 0) break;

                if ($stock->reserved_qty >= $remainingToRelease) {
                    // Lot นี้มีจองเยอะกว่าที่ขอปลด -> ปลดให้จบเลย
                    $stock->reserved_qty -= $remainingToRelease;
                    $stock->save();
                    $remainingToRelease = 0;
                } else {
                    // Lot นี้มีจองน้อยกว่าที่ขอปลด -> ปลดให้หมด Lot แล้วไปต่อ
                    $releaseAmount = $stock->reserved_qty;
                    $remainingToRelease -= $releaseAmount;
                    $stock->reserved_qty = 0;
                    $stock->save();
                }
            }

            // 3. บันทึก Transaction (RELEASE)
            Transaction::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $requestedQty,
                'type' => 'RELEASE',
            ]);
        });

        return back()->with('success', '✅ ปลดจองสินค้าเรียบร้อยแล้ว');
    }
}
