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
        $totalStock = Stock::where('product_id', $product->id)->sum('quantity');
        
        if ($totalStock < $requestedQty) {
            return back()->withErrors(['สินค้ามีไม่พอให้เบิก! (ปัจจุบันมีเหลือแค่ ' . $totalStock . ' ชิ้น)']);
        }

        // 2. เริ่มกระบวนการตัดสต็อกแบบ FIFO
        DB::transaction(function () use ($product, $requestedQty) {
            
            // ดึงสต็อกทั้งหมดของสินค้านี้ ที่มีจำนวนมากกว่า 0 ชิ้น โดย "เรียงจากเก่าสุดไปใหม่สุด" (ASC)
            $stocks = Stock::where('product_id', $product->id)
                           ->where('quantity', '>', 0)
                           ->orderBy('received_date', 'asc')
                           ->get();

            $remainingToPick = $requestedQty; // จำนวนที่ยังต้องหยิบให้ครบ

            foreach ($stocks as $stock) {
                // ถ้าหยิบครบแล้ว ให้หยุดการทำงานลูป
                if ($remainingToPick <= 0) {
                    break;
                }

                if ($stock->quantity >= $remainingToPick) {
                    // กรณีที่ 1: ล็อตนี้มีของ "พอ" หรือ "มากกว่า" ที่ต้องการ
                    $stock->quantity -= $remainingToPick;
                    $stock->save();
                    $remainingToPick = 0; // หยิบครบแล้ว
                } else {
                    // กรณีที่ 2: ล็อตนี้มีของ "ไม่พอ" (ต้องกวาดให้เกลี้ยงแล้วไปเอาล็อตหน้าต่อ)
                    $remainingToPick -= $stock->quantity;
                    $stock->quantity = 0;
                    $stock->save();
                }
            }

            // 3. บันทึกประวัติการเบิกออก (Type: OUT)
            Transaction::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $requestedQty,
                'type' => 'OUT',
            ]);
        });

        return redirect()->back()->with('success', '✅ เบิกสินค้าเรียบร้อยแล้ว (ระบบทำการตัดสต็อกแบบ FIFO สำเร็จ!)');
    }
}
