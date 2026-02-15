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
        // 1. ดึงสินค้าที่มี SKU รูปแบบ 'SKU-xxxxxx' ที่มีเลขมากที่สุด
        // โดยการสั่งตัดคำว่า 'SKU-' ออกแล้วแปลงเป็นตัวเลขเพื่อหาค่า Max
        $lastSkuRecord = \App\Models\Product::where('sku', 'LIKE', 'SKU-%')
            ->selectRaw('MAX(CAST(SUBSTRING(sku, 5) AS UNSIGNED)) as max_sku')
            ->first();

        // 2. ถ้ามีข้อมูลให้เอาค่า Max + 1 ถ้าไม่มีให้เริ่มที่ 1
        $nextId = ($lastSkuRecord && $lastSkuRecord->max_sku) ? $lastSkuRecord->max_sku + 1 : 1;

        // 3. จัดรูปแบบรหัส (เช่น SKU-000003)
        $nextSku = 'SKU-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

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

    public function getByBarcode($barcode)
    {
        // ค้นหาสินค้าจากเลขบาร์โค้ด
        $product = \App\Models\Product::where('barcode', $barcode)->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'product' => $product
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'ไม่พบข้อมูลสินค้า'
        ]);
    }
}
