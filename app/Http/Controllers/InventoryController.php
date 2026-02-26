<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

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
    // 1. ค้นหาสินค้าที่ต้องการลบ
    $product = Product::findOrFail($id);

    // 2. เช็คว่าสินค้าตัวนี้มีรูปบาร์โค้ดบันทึกไว้ไหม
    if ($product->barcode_image) {
        if (Storage::disk('public')->exists('barcodes/' . $product->barcode_image)) {
            Storage::disk('public')->delete('barcodes/' . $product->barcode_image);
        }
    }

    // 3. เช็คว่ามีรูป QR Code ไหม ถ้ามีก็ลบทิ้ง
    if ($product->qr_code_image) {
        if (Storage::disk('public')->exists('qrcodes/' . $product->qr_code_image)) {
            Storage::disk('public')->delete('qrcodes/' . $product->qr_code_image);
        }
    }

    // 4. ลบข้อมูลสินค้าออกจาก Database
    $product->delete();

    // 5. เด้งกลับไปหน้าเดิมพร้อมข้อความแจ้งเตือน
    return redirect()->route('inventory.index')->with('success', 'ลบรายการสินค้าและไฟล์รูปบาร์โค้ด + QR Code เรียบร้อยแล้ว!');
}
}
