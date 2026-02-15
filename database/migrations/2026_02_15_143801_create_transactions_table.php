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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // เชื่อมกับ users (คนทำรายการ)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); 
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            // ต้นทางและปลายทาง (nullable เพราะถ้ารับเข้าใหม่จะไม่มีต้นทาง, ถ้าเบิกออกจะไม่มีปลายทาง)
            $table->foreignId('from_location_id')->nullable()->constrained('locations')->onDelete('cascade');
            $table->foreignId('to_location_id')->nullable()->constrained('locations')->onDelete('cascade');
            
            $table->integer('quantity');
            $table->enum('type', ['IN', 'OUT', 'TRANSFER', 'RESERVE']); // ประเภทการทำรายการ
            $table->enum('status', ['pending', 'completed'])->default('completed'); // สถานะ (ใช้กับย้ายของ)
            $table->string('ref_doc_no', 50)->nullable(); // เลขที่เอกสารอ้างอิง
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
