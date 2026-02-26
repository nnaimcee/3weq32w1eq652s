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
            $table->text('notes')->nullable()->after('ref_doc_no');
        });

        // อัปเดต type enum ให้รองรับ RELEASE ด้วย
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('IN', 'OUT', 'TRANSFER', 'RESERVE', 'RELEASE') NOT NULL");
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('IN', 'OUT', 'TRANSFER', 'RESERVE') NOT NULL");
    }
};
