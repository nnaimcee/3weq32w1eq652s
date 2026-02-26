<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Location;
use App\Models\Stock;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class InboundController extends Controller
{
    // เปิดหน้าฟอร์มรับของเข้า
    public function create()
    {
        // ดึง Location เฉพาะที่เป็นพื้นที่จัดเก็บ (storage) มาให้เลือกใน Dropdown
        $locations = Location::where('type', 'storage')->get();
        return view('inbound.create', compact('locations'));
    }

    // ฟังก์ชันรับข้อมูลจากฟอร์มเพื่อบันทึกลง Database
    public function store(Request $request)
    {
        // 1. ตรวจสอบความถูกต้องของข้อมูล
        $request->validate([
            'barcode' => 'required|exists:products,barcode', // บาร์โค้ดต้องมีในระบบ
            'quantity' => 'required|integer|min:1',
            'location_id' => 'required|exists:locations,id',
            'lot_number' => 'nullable|string|max:50', // เพิ่มการรองรับ Lot ID (nullable)
        ], [
            'barcode.exists' => 'ไม่พบสินค้านี้ในระบบ กรุณาตรวจสอบบาร์โค้ดอีกครั้ง'
        ]);

        // 2. หาสินค้าจากบาร์โค้ด
        $product = Product::where('barcode', $request->barcode)->first();

        // สร้าง Lot Number อัตโนมัติถ้าไม่ได้กรอกมา (รูปแบบ INB-YYYYMMDD-HHMMSS)
        $lotNumber = $request->lot_number ?: 'INB-' . now()->format('Ymd-His');

        // 3. เริ่มบันทึกข้อมูล (ใช้ DB::transaction เพื่อความปลอดภัย ถ้าบันทึกพังตรงไหน มันจะยกเลิกให้ทั้งหมด)
        DB::transaction(function () use ($request, $product, $lotNumber) {
            
            // --> A. สร้างล็อตใหม่ลงตาราง Stocks (เพิ่มแถวใหม่เสมอเพื่อเก็บเวลา FIFO)
            Stock::create([
                'product_id' => $product->id,
                'location_id' => $request->location_id,
                'quantity' => $request->quantity,
                'reserved_qty' => 0,
                'lot_number' => $lotNumber, // บันทึก Lot Number ลง Stock
                'received_date' => now(), // สำคัญที่สุด! เก็บเวลาปัจจุบันเพื่อใช้ทำ FIFO
            ]);

            // --> B. บันทึกประวัติลงตาราง Transactions (Type: IN)
            Transaction::create([
                'user_id' => auth()->id(), // ใครเป็นคนรับเข้า
                'product_id' => $product->id,
                'to_location_id' => $request->location_id,
                'quantity' => $request->quantity,
                'type' => 'IN',
                'lot_number' => $lotNumber, // บันทึก Lot Number ลง Transaction ด้วย
            ]);
            
        });

        // 4. บันทึกเสร็จแล้ว เด้งกลับมาหน้าเดิมพร้อมข้อความสำเร็จ
        return redirect()->back()->with('success', "✅ รับสินค้าเข้าคลังเรียบร้อยแล้ว (Lot: {$lotNumber})!");
    }
}
