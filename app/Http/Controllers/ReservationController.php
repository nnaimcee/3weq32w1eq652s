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
            'location_id' => 'nullable|exists:locations,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $requestedQty = $request->quantity;
        $locationId = $request->location_id;

        // 1. เช็คว่ามีของ "ว่างพร้อมจอง" พอไหม (ไม่นับสต็อกใน Transit)
        $transitLocationIds = \App\Models\Location::where('type', 'transit')->pluck('id');
        $stockQuery = Stock::where('product_id', $product->id)
            ->whereNotIn('location_id', $transitLocationIds);
        if ($locationId) {
            $stockQuery->where('location_id', $locationId);
        }
        $totalStock = (clone $stockQuery)->sum('quantity');
        $totalReserved = (clone $stockQuery)->sum('reserved_qty');
        $availableToReserve = $totalStock - $totalReserved;

        if ($availableToReserve < $requestedQty) {
            return back()->withErrors(['สินค้ามีไม่พอให้จอง! (เหลือว่างรับจองแค่ ' . number_format($availableToReserve) . ' ชิ้น)']);
        }

        DB::transaction(function () use ($product, $requestedQty, $transitLocationIds, $locationId) {
            // 2. ดึง Lot ที่ยังมีของเหลือ FIFO
            $query = Stock::where('product_id', $product->id)
                ->whereNotIn('location_id', $transitLocationIds)
                ->whereRaw('quantity > reserved_qty')
                ->orderBy('received_date', 'asc');
            if ($locationId) {
                $query->where('location_id', $locationId);
            }
            $stocks = $query->get();

            $remainingToReserve = $requestedQty;

            foreach ($stocks as $stock) {
                if ($remainingToReserve <= 0) break;
                $availableInLot = $stock->quantity - $stock->reserved_qty;

                if ($availableInLot >= $remainingToReserve) {
                    $stock->reserved_qty += $remainingToReserve;
                    $stock->save();
                    $remainingToReserve = 0;
                } else {
                    $remainingToReserve -= $availableInLot;
                    $stock->reserved_qty += $availableInLot;
                    $stock->save();
                }
            }

            // 3. บันทึก Transaction (RESERVE)
            Transaction::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $requestedQty,
                'type' => 'RESERVE',
                'to_location_id' => $locationId,
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
