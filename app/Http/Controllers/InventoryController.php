<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::withSum('stocks', 'quantity')->withSum('stocks', 'reserved_qty')->get();
        return view('inventory.index', compact('products'));
    }

    public function warehouseMap()
    {
        // ดึง Location ทั้งหมดพร้อมยอดรวมสต็อก
        $zones = \App\Models\Location::where('type', 'storage')
        ->with(['stocks' => function($query) {
            $query->select('location_id', 'quantity', 'reserved_qty');
        }])
        ->get()
        ->groupBy('zone'); // จัดกลุ่มตาม Zone เพื่อให้แสดงผลแยกส่วนกัน

        return view('inventory.map', compact('zones'));
    }
    public function destroy($id)
    {
        // ค้นหาสินค้าที่ต้องการลบ
        $product = \App\Models\Product::findOrFail($id);

        // ลบรายการสต็อกที่เกี่ยวข้องทั้งหมดก่อน (เพื่อป้องกัน Error Foreign Key)
        $product->stocks()->delete();

        // ลบตัวสินค้า
        $product->delete();

        return redirect()->back()->with('success', '🗑️ ลบสินค้าและข้อมูลสต็อกที่เกี่ยวข้องเรียบร้อยแล้ว');
    }
}
