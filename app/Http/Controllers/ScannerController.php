<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class ScannerController extends Controller
{
    public function index()
    {
        // ดึง Location สำหรับ dropdown ตอนรับเข้า
        $locations = Location::where('type', 'storage')->get();
        return view('scanner.index', compact('locations'));
    }
}
