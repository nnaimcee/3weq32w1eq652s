<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Location;

class LocationController extends Controller
{
    // ฟังก์ชันนี้จะถูกเรียกผ่าน AJAX เพื่อดึงข้อมูลสินค้าที่อยู่ใน Location ที่ถูกคลิก
    public function getItems($id)
    {
        // ดึงสต็อกสินค้าที่อยู่ใน Location นี้ และมีจำนวนมากกว่า 0
        $stocks = \App\Models\Stock::with('product')
                    ->where('location_id', $id)
                    ->where('quantity', '>', 0)
                    ->get();

        return response()->json([
            'success' => true,
            'items' => $stocks->map(function ($stock) {
                return [
                    'product_name' => $stock->product->name ?? 'ไม่ทราบชื่อ',
                    'sku' => $stock->product->sku ?? '-',
                    'quantity' => $stock->quantity,
                    'reserved' => $stock->reserved_qty ?? 0,
                    'available' => $stock->quantity - ($stock->reserved_qty ?? 0),
                    'lot_number' => $stock->lot_number ?? '-',
                    'received_date' => $stock->received_date ? date('d/m/Y', strtotime($stock->received_date)) : '-',
                ];
            })
        ]);
    }

    // ======== Admin Functions ========

    // แสดงรายการสถานที่ทั้งหมด
    public function index(Request $request)
    {
        $query = Location::withCount('stocks')
            ->withSum('stocks', 'quantity')
            ->orderBy('zone')
            ->orderBy('name');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('zone', 'like', "%{$search}%");
        }

        $locations = $query->paginate(20);

        return view('locations.index', compact('locations'));
    }

    // บันทึกสถานที่ใหม่
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:locations,name',
            'zone' => 'nullable|string|max:50',
            'shelf' => 'nullable|string|max:50',
            'bin' => 'nullable|string|max:50',
            'type' => 'required|in:storage,transit',
        ], [
            'name.unique' => 'ชื่อสถานที่นี้มีอยู่แล้วในระบบ',
        ]);

        Location::create([
            'name' => $request->name,
            'zone' => $request->zone,
            'shelf' => $request->shelf,
            'bin' => $request->bin,
            'type' => $request->type,
            'status' => 'active',
        ]);

        return redirect()->route('locations.index')->with('success', '✅ เพิ่มสถานที่ใหม่เรียบร้อยแล้ว!');
    }

    // อัพเดทชื่อสถานที่
    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:locations,name,' . $id,
            'zone' => 'nullable|string|max:50',
            'type' => 'required|in:storage,transit',
            'status' => 'required|in:active,inactive,full',
        ], [
            'name.unique' => 'ชื่อสถานที่นี้มีอยู่แล้วในระบบ',
        ]);

        $location->update([
            'name' => $request->name,
            'zone' => $request->zone,
            'type' => $request->type,
            'status' => $request->status,
        ]);

        return redirect()->route('locations.index')->with('success', '✅ อัพเดทข้อมูลสถานที่เรียบร้อยแล้ว!');
    }

    // ลบสถานที่ (เฉพาะที่ไม่มีสต็อก)
    public function destroy($id)
    {
        $location = Location::withCount('stocks')->findOrFail($id);

        // ห้ามลบถ้ายังมีสต็อกอยู่
        $hasStock = \App\Models\Stock::where('location_id', $id)->where('quantity', '>', 0)->exists();
        if ($hasStock) {
            return redirect()->route('locations.index')
                ->withErrors('❌ ไม่สามารถลบได้ เนื่องจากสถานที่นี้ยังมีสินค้าอยู่!');
        }

        $location->delete();
        return redirect()->route('locations.index')->with('success', '✅ ลบสถานที่เรียบร้อยแล้ว!');
    }
}
