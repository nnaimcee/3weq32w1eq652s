<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Location;

class LocationController extends Controller
{
    // ฟังก์ชันนี้จะถูกเรียกผ่าน AJAX เพื่อดึงข้อมูลสินค้าที่อยู่ใน Location ที่ถูกคลิก
    public function getItems($id)
    {
        // ดึงสต็อกสินค้าที่อยู่ใน Location นี้ และมีจำนวนมากกว่า 0
        // (สมมติว่าคุณมีตาราง Stock ที่ผูกกับ Product และ Location นะครับ)
        $stocks = \App\Models\Stock::with('product')
                    ->where('location_id', $id)
                    ->where('quantity', '>', 0)
                    ->get();

        return response()->json([
            'success' => true,
            'items' => $stocks->map(function ($stock) {
                return [
                    'product_name' => $stock->product->name ?? 'ไม่ทราบชื่อ',
                    'sku' => $stock->product->sku ?? '-',
                    'quantity' => $stock->quantity
                ];
            })
        ]);
    }
}
