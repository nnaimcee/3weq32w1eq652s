<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Product;
use App\Models\LocationReservation;

class LocationReservationController extends Controller
{
    /** รายการการจองทั้งหมด + ฟอร์มสร้าง */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'active'); // active | history

        // การจองที่ active (pending)
        $pending = LocationReservation::with(['location', 'product', 'reserver'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // ประวัติ (fulfilled + cancelled) — paginated
        $history = LocationReservation::with(['location', 'product', 'reserver'])
            ->whereIn('status', ['fulfilled', 'cancelled'])
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        // เฉพาะ location ที่ไม่ปิด
        $locations = Location::where('type', 'storage')
            ->whereNotIn('status', ['inactive'])
            ->orderBy('name')
            ->get();

        $products = Product::orderBy('name')->get();

        return view('location-reservations.index', compact('pending', 'history', 'locations', 'products', 'tab'));
    }

    /** สร้างการจองใหม่ */
    public function store(Request $request)
    {
        $request->validate([
            'location_id'  => 'required|exists:locations,id',
            'product_id'   => 'nullable|exists:products,id',
            'expected_qty' => 'required|integer|min:1',
            'note'         => 'nullable|string|max:255',
            'expected_at'  => 'nullable|date',
        ]);

        // ตรวจ location ต้องไม่ inactive
        $location = Location::findOrFail($request->location_id);
        if ($location->status === 'inactive') {
            return redirect()->back()
                ->withErrors(['location_id' => "❌ ตำแหน่ง '{$location->name}' ถูกปิดใช้งาน ไม่สามารถจองได้"])
                ->withInput();
        }

        // ตรวจว่ามีการจอง pending อยู่แล้วหรือไม่
        $existing = LocationReservation::where('location_id', $request->location_id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withErrors(['location_id' => "⚠️ ตำแหน่ง '{$location->name}' มีการจองรออยู่แล้ว"])
                ->withInput();
        }

        LocationReservation::create([
            'location_id'  => $request->location_id,
            'product_id'   => $request->product_id ?: null,
            'reserved_by'  => auth()->id(),
            'expected_qty' => $request->expected_qty,
            'note'         => $request->note,
            'status'       => 'pending',
            'expected_at'  => $request->expected_at ?: null,
        ]);

        // ✅ ตรวจ capacity — ถ้า current + expected >= capacity → set full ทันที
        $capacity   = $location->capacity ?? 5000;
        $currentQty = $location->stocks()->sum('quantity');
        $afterQty   = $currentQty + $request->expected_qty;

        if ($afterQty >= $capacity && $location->status !== 'inactive') {
            $location->update(['status' => 'full']);
            $fullMsg = " 🔴 (ตำแหน่งนี้จะเต็มเมื่อสินค้ามาถึง ถูกล็อคเป็น Full แล้ว)";
        } else {
            $fullMsg = "";
        }

        return redirect()->route('location-reservations.index')
            ->with('success', "✅ จองพื้นที่ '{$location->name}' เรียบร้อยแล้ว!{$fullMsg}");

    }

    /** ยกเลิกการจอง */
    public function cancel($id)
    {
        $reservation = LocationReservation::with('location')->findOrFail($id);
        $reservation->update(['status' => 'cancelled']);

        // ✅ คืน status location — ถ้าถูก set full เพราะการจองนี้ ให้ recheck
        if ($reservation->location) {
            $reservation->location->checkAndUpdateStatus();
        }

        return redirect()->route('location-reservations.index')
            ->with('success', '🚫 ยกเลิกการจองเรียบร้อยแล้ว');
    }

    /** ยืนยันรับสินค้าแล้ว (manual) */
    public function fulfill($id)
    {
        $reservation = LocationReservation::with('location')->findOrFail($id);
        $reservation->update(['status' => 'fulfilled']);

        // ✅ recheck สถานะหลังรับสินค้า
        if ($reservation->location) {
            $reservation->location->checkAndUpdateStatus();
        }

        return redirect()->route('location-reservations.index')
            ->with('success', '✅ ยืนยันรับสินค้าเข้าพื้นที่เรียบร้อยแล้ว');
    }

}
