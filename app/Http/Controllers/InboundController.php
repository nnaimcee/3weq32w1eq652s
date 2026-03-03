<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Location;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\LocationReservation;
use Illuminate\Support\Facades\DB;

class InboundController extends Controller
{
    // เปิดหน้าฟอร์มรับของเข้า
    public function create()
    {
        // แสดงเฉพาะ storage ที่ไม่ inactive และไม่ full
        $locations = Location::where('type', 'storage')
            ->whereNotIn('status', ['inactive', 'full'])
            ->orderBy('name')
            ->get();
        return view('inbound.create', compact('locations'));
    }

    // ฟังก์ชันรับข้อมูลจากฟอร์มเพื่อบันทึกลง Database
    public function store(Request $request)
    {
        // 1. ตรวจสอบความถูกต้องของข้อมูล
        $request->validate([
            'barcode'     => 'required|exists:products,barcode',
            'quantity'    => 'required|integer|min:1',
            'location_id' => 'required|exists:locations,id',
            'lot_number'  => 'nullable|string|max:50',
        ], [
            'barcode.exists' => 'ไม่พบสินค้านี้ในระบบ กรุณาตรวจสอบบาร์โค้ดอีกครั้ง'
        ]);

        $location = Location::findOrFail($request->location_id);
        $qty      = (int) $request->quantity;
        $capacity = $location->capacity ?? 5000;

        // ✅ ตรวจสถานะ inactive / full
        if ($location->status === 'inactive') {
            return redirect()->back()
                ->withErrors(['location_id' => "❌ ตำแหน่ง '{$location->name}' ถูกปิดใช้งาน ไม่สามารถรับสินค้าเข้าได้"])
                ->withInput();
        }
        if ($location->status === 'full') {
            return redirect()->back()
                ->withErrors(['location_id' => "❌ ตำแหน่ง '{$location->name}' เต็มแล้ว กรุณาเลือกตำแหน่งอื่น"])
                ->withInput();
        }

        // ✅ ตรวจว่ามีการจองพื้นที่ pending อยู่หรือไม่
        $pendingRes = LocationReservation::where('location_id', $location->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingRes) {
            return redirect()->back()
                ->withErrors(['location_id' => "🔖 ตำแหน่ง '{$location->name}' ถูกจองรอสินค้าเข้า ({$pendingRes->expected_qty} ชิ้น) กรุณาเลือกตำแหน่งอื่น หรือยกเลิกการจองก่อน"])
                ->withInput();
        }

        // ✅ ตรวจ capacity
        $currentQty  = $location->stocks()->sum('quantity');
        $afterQty    = $currentQty + $qty;

        if ($afterQty > $capacity) {
            $remaining = $capacity - $currentQty;
            return redirect()->back()
                ->withErrors(['quantity' => "❌ ตำแหน่ง '{$location->name}' รับได้อีกแค่ {$remaining} ชิ้น (capacity: {$capacity}, มีอยู่แล้ว: {$currentQty})"])
                ->withInput();
        }

        // 2. หาสินค้าจากบาร์โค้ด
        $product   = Product::where('barcode', $request->barcode)->first();
        $lotNumber = $request->lot_number ?: 'INB-' . now()->format('Ymd-His');

        // 3. บันทึก
        DB::transaction(function () use ($request, $product, $lotNumber, $location) {
            Stock::create([
                'product_id'    => $product->id,
                'location_id'   => $request->location_id,
                'quantity'      => $request->quantity,
                'reserved_qty'  => 0,
                'lot_number'    => $lotNumber,
                'received_date' => now(),
            ]);

            Transaction::create([
                'user_id'        => auth()->id(),
                'product_id'     => $product->id,
                'to_location_id' => $request->location_id,
                'quantity'       => $request->quantity,
                'type'           => 'IN',
                'lot_number'     => $lotNumber,
            ]);
        });

        // ✅ อัปเดตสถานะ location อัตโนมัติ (ตรวจว่า qty >= capacity → full)
        $location->refresh();
        $location->checkAndUpdateStatus();

        $statusMsg = $location->status === 'full' ? ' 🔴 ตำแหน่งนี้เต็มแล้ว!' : '';
        return redirect()->back()->with('success', "✅ รับสินค้าเข้าคลังเรียบร้อยแล้ว (Lot: {$lotNumber})!{$statusMsg}");
    }
}
