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
}
