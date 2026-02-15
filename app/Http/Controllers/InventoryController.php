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
}
