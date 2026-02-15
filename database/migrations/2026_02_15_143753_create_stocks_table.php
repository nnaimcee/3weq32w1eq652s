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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // เชื่อมตาราง products
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade'); // เชื่อมตาราง locations
            $table->integer('quantity')->default(0); // จำนวนจริงที่มี
            $table->integer('reserved_qty')->default(0); // จำนวนที่ถูกจอง (กันที่)
            $table->string('lot_number', 50)->nullable();
            $table->dateTime('received_date'); // วันที่รับเข้า (สำคัญมากสำหรับเรียงลำดับ FIFO)
            $table->date('expiry_date')->nullable(); // วันหมดอายุ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
