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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // สร้าง PK ให้อัตโนมัติ
            $table->string('barcode', 50)->unique(); // บาร์โค้ด ห้ามซ้ำ
            $table->string('name'); // ชื่อสินค้า
            $table->text('description')->nullable(); // รายละเอียด (เว้นว่างได้)
            $table->string('unit', 50)->default('ชิ้น'); // หน่วยนับ
            $table->integer('min_stock')->default(0); // จุดสั่งซื้อขั้นต่ำ
            $table->string('image_path')->nullable(); // รูปภาพ
            $table->timestamps(); // สร้าง created_at, updated_at ให้อัตโนมัติ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
