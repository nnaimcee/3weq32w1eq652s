<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // ฟังก์ชันสำหรับหน้าพิมพ์บาร์โค้ด
    public function printBarcode($id)
    {
        // ค้นหาสินค้าจาก ID ถ้าไม่เจอให้แสดงหน้า 404
        $product = Product::findOrFail($id);
        
        return view('products.barcode', compact('product'));
    }
    
    public function create()
    {
        // 1. ดึงข้อมูลสินค้าล่าสุด
        $lastProduct = Product::orderBy('id', 'desc')->first();

        // 2. คำนวณเลขถัดไป
        $nextId = $lastProduct ? $lastProduct->id + 1 : 1;

        // 3. สร้างรหัส SKU (เช่น SKU-000001)
        $nextSku = 'SKU-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

        // 4. ส่งตัวแปรไปที่หน้า View ด้วยคำสั่ง compact
        // *** มั่นใจว่าใน compact พิมพ์ชื่อตัวแปรไม่มีเครื่องหมาย $ ***
        return view('products.create', compact('nextSku'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'barcode' => 'nullable|string|unique:products,barcode',
        ]);

        // ถ้าไม่ได้กรอกบาร์โค้ดมา ให้ใช้ SKU เป็นบาร์โค้ดแทน
        $barcode = $request->barcode ?: $request->sku;

        Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'barcode' => $barcode,
            'min_stock' => $request->min_stock ?? 0,
        ]);

        return redirect()->route('inventory.index')->with('success', 'เพิ่มสินค้าใหม่เรียบร้อยแล้ว!');
    }
}
