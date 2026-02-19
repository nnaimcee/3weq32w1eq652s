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
        // Use raw SQL to modify ENUM
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('IN', 'OUT', 'TRANSFER', 'RESERVE', 'RELEASE') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original ENUM
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('IN', 'OUT', 'TRANSFER', 'RESERVE') NOT NULL");
    }
};
