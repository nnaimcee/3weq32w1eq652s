<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // 1. PostgreSQL ไม่รองรับคำสั่ง ->after() ครับ
            // ระบบจะเพิ่ม column 'notes' ต่อท้ายตารางให้โดยอัตโนมัติ
            $table->text('notes')->nullable();
        });

        // 2. เปลี่ยนจาก MODIFY COLUMN เป็น ALTER COLUMN ... TYPE (สำหรับ PostgreSQL)
        // และแนะนำให้ใช้ VARCHAR(255) เพื่อป้องกันปัญหา Data Type ขัดแย้งกันครับ
        DB::statement("ALTER TABLE transactions ALTER COLUMN type TYPE VARCHAR(255)");
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        // ปรับให้เป็น VARCHAR(255) เช่นกันครับ
        DB::statement("ALTER TABLE transactions ALTER COLUMN type TYPE VARCHAR(255)");
    }
};