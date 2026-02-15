<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\OutboundController;

// Laravel จะอ่านไฟล์นี้เพื่อกำหนดเส้นทาง (Route) ต่างๆ ของเว็บแอปพลิเคชันเรา
Route::get('/', function () {
    return view('welcome');
});

// --- IGNORE --- (ส่วนนี้เป็นโค้ดที่ Laravel สร้างมาให้แล้ว ไม่ต้องแก้ไข)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route สำหรับแสดงหน้าสต็อกสินค้า
Route::get('/inventory', [InventoryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('inventory.index');

// Route สำหรับพิมพ์บาร์โค้ดสินค้า
Route::get('/products/barcode/{id}', [ProductController::class, 'printBarcode'])
    ->middleware(['auth', 'verified'])
    ->name('products.barcode');

// Routes สำหรับรับของเข้า (Inbound)
Route::get('/inbound', [InboundController::class, 'create'])->middleware(['auth', 'verified'])->name('inbound.create');
Route::post('/inbound', [InboundController::class, 'store'])->middleware(['auth', 'verified'])->name('inbound.store');

// Routes สำหรับเบิกของออก (Outbound)
Route::get('/outbound', [OutboundController::class, 'create'])->middleware(['auth', 'verified'])->name('outbound.create');
Route::post('/outbound', [OutboundController::class, 'store'])->middleware(['auth', 'verified'])->name('outbound.store');










// --- IGNORE --- (ส่วนนี้เป็นโค้ดที่ Laravel สร้างมาให้แล้ว ไม่ต้องแก้ไข)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
