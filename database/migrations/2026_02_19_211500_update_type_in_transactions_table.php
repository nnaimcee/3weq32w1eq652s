<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ใน PostgreSQL เราจะใช้ ALTER COLUMN ... TYPE
        // และแนะนำให้เปลี่ยนเป็น VARCHAR เพื่อความยืดหยุ่นในการรัน Migration
        DB::statement("ALTER TABLE transactions ALTER COLUMN type TYPE VARCHAR(255)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ตอนถอยกลับก็ให้คงความเป็น VARCHAR ไว้
        DB::statement("ALTER TABLE transactions ALTER COLUMN type TYPE VARCHAR(255)");
    }
};