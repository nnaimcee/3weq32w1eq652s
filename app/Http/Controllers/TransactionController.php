<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลประวัติทั้งหมด เรียงจากใหม่ล่าสุดไปเก่าสุด
        // ใช้ with เพื่อดึงข้อมูลตารางที่เกี่ยวข้องมารวดเดียว (Eager Loading)
        $transactions = Transaction::with(['user', 'product', 'fromLocation', 'toLocation'])
            ->latest()
            ->paginate(15); // แบ่งหน้าละ 15 รายการ

        return view('transactions.index', compact('transactions'));
    }
}
