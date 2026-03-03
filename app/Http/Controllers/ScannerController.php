<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\LocationReservation;

class ScannerController extends Controller
{
    public function index()
    {
        // ดึง location_id ที่มี pending reservation
        $reservedLocationIds = LocationReservation::where('status', 'pending')
            ->pluck('location_id');

        // exclude: full, inactive, หรือถูกจองรอสินค้าเข้าอยู่
        $locations = Location::where('type', 'storage')
            ->whereNotIn('status', ['full', 'inactive'])
            ->whereNotIn('id', $reservedLocationIds)
            ->orderBy('name')
            ->get();

        // ✅ Bug #9: ส่ง map ไปให้ view แสดง warning (ใช้กรณีที่ dropdown แสดงทั้งหมด)
        // แม้ตัว dropdown จะไม่แสดง reserved location แล้ว แต่เก็บ map ไว้สำหรับ JS check
        $pendingReservationMap = LocationReservation::with('product')
            ->where('status', 'pending')
            ->get()
            ->keyBy('location_id')
            ->map(fn($r) => $r->product ? $r->product->name : 'ไม่ระบุสินค้า');

        return view('scanner.index', compact('locations', 'pendingReservationMap'));
    }
}
