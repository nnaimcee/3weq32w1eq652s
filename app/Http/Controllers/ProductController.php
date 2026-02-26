<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Milon\Barcode\Facades\DNS2DFacade as DNS2D;

class ProductController extends Controller
{
    // ฟังก์ชันสำหรับหน้าพิมพ์บาร์โค้ด
    public function printBarcode($id)
    {
        // ค้นหาสินค้าจาก ID ถ้าไม่เจอให้แสดงหน้า 404
        $product = Product::findOrFail($id);
        
        return view('products.barcode', compact('product'));
    }

    // ฟังก์ชันสำหรับหน้าพิมพ์ QR Code
    public function printQrCode($id)
    {
        $product = Product::findOrFail($id);
        return view('products.qrcode', compact('product'));
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

        // 1. ตั้งชื่อไฟล์รูปภาพให้ไม่ซ้ำกัน (เช่น barcode_SKU-000001.png)
        $imageName = 'barcode_' . $request->sku . '.png';

        // 2. สั่งสร้างบาร์โค้ดเป็นไฟล์ภาพ PNG
        $barcodeBase64 = DNS1D::getBarcodePNG($barcode, 'C128', 1.5, 33);

        // 3. เซฟไฟล์ภาพลงในโฟลเดอร์ storage/app/public/barcodes/
        Storage::disk('public')->put('barcodes/' . $imageName, base64_decode($barcodeBase64));

        // 4. สร้าง QR Code เป็นไฟล์ภาพ PNG
        $qrImageName = 'qrcode_' . $request->sku . '.png';
        $qrBase64 = DNS2D::getBarcodePNG($barcode, 'QRCODE', 6, 6);
        Storage::disk('public')->put('qrcodes/' . $qrImageName, base64_decode($qrBase64));

        // 5. บันทึกข้อมูลทั้งหมด (รวมถึงชื่อไฟล์รูป) ลงฐานข้อมูล
        Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'barcode' => $barcode,
            'min_stock' => $request->min_stock ?? 0,
            'barcode_image' => $imageName,
            'qr_code_image' => $qrImageName,
        ]);

        return redirect()->route('inventory.index')->with('success', 'เพิ่มสินค้าและสร้างรูปบาร์โค้ด + QR Code อัตโนมัติเรียบร้อย!');
    }

    public function getByBarcode($barcode)
    {
        $transitLocationIds = \App\Models\Location::where('type', 'transit')->pluck('id');

        // ค้นหาสินค้าจากเลขบาร์โค้ด — exclude สต็อกที่อยู่ใน Transit
        $product = \App\Models\Product::where('barcode', $barcode)
            ->withSum(['stocks as stocks_sum_quantity' => function($q) use ($transitLocationIds) {
                $q->whereNotIn('location_id', $transitLocationIds);
            }], 'quantity')
            ->withSum(['stocks as stocks_sum_reserved_qty' => function($q) use ($transitLocationIds) {
                $q->whereNotIn('location_id', $transitLocationIds);
            }], 'reserved_qty')
            ->withSum(['stocks as transit_quantity' => function($q) use ($transitLocationIds) {
                $q->whereIn('location_id', $transitLocationIds);
            }], 'quantity')
            ->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'total_stock' => $product->stocks_sum_quantity ?? 0,
                    'reserved' => $product->stocks_sum_reserved_qty ?? 0,
                    'in_transit' => $product->transit_quantity ?? 0,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'ไม่พบข้อมูลสินค้าในระบบ'
        ]);
    }
}