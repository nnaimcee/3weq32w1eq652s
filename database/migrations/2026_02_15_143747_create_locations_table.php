<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // ชื่อเรียก เช่น Z1-S1-B01
            $table->string('zone', 50)->nullable();
            $table->string('shelf', 50)->nullable();
            $table->string('bin', 50)->nullable();
            $table->enum('type', ['storage', 'transit'])->default('storage'); // ประเภทพื้นที่
            $table->enum('status', ['active', 'inactive', 'full'])->default('active'); // สถานะ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
