<?php

use App\Models\Product;
use App\Models\Stock;
use App\Models\Transaction;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\TransactionController;

// --- IGNORE --- (ส่วนนี้เป็นโค้ดที่ Laravel สร้างมาให้แล้ว ไม่ต้องแก้ไข)
Route::get('/dashboard', function () {
    // 1. นับจำนวนสินค้าทั้งหมด
    $totalProducts = Product::count();
    
    // 2. รวมจำนวนสต็อกทั้งหมด
    $totalStock = Stock::sum('quantity');
    
    // 3. หาสินค้าที่ของใกล้หมด (ต่ำกว่า min_stock)
    $lowStockCount = Product::withSum('stocks', 'quantity')
        ->get()
        ->filter(function($product) {
            return $product->stocks_sum_quantity < $product->min_stock;
        })->count();

    // 4. ดึงประวัติล่าสุด 5 รายการ
    $recentActivities = Transaction::with(['product', 'user'])
        ->latest()
        ->take(5)
        ->get();

    // ส่งตัวแปรทั้งหมดไปที่ View ด้วยคำสั่ง compact
    return view('dashboard', compact('totalProducts', 'totalStock', 'lowStockCount', 'recentActivities'));
})->middleware(['auth', 'verified'])->name('dashboard');


// Route สำหรับแสดงหน้าสต็อกสินค้า
Route::get('/inventory', [InventoryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('inventory.index');

// Route สำหรับแสดงแผนที่คลังสินค้า
Route::get('/warehouse-map', [App\Http\Controllers\InventoryController::class, 'warehouseMap'])
    ->middleware(['auth'])->name('inventory.map');

// Route สำหรับพิมพ์บาร์โค้ดสินค้า
Route::get('/products/barcode/{id}', [ProductController::class, 'printBarcode'])
    ->middleware(['auth', 'verified'])
    ->name('products.barcode');

// Routes สำหรับจัดการสินค้า (เพิ่มสินค้าใหม่)
Route::middleware(['auth'])->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
});

// Routes สำหรับรับของเข้า (Inbound)
Route::get('/inbound', [InboundController::class, 'create'])->middleware(['auth', 'verified'])->name('inbound.create');
Route::post('/inbound', [InboundController::class, 'store'])->middleware(['auth', 'verified'])->name('inbound.store');

// Routes สำหรับเบิกของออก (Outbound)
Route::get('/outbound', [OutboundController::class, 'create'])->middleware(['auth', 'verified'])->name('outbound.create');
Route::post('/outbound', [OutboundController::class, 'store'])->middleware(['auth', 'verified'])->name('outbound.store');

// Routes สำหรับโอนย้ายสินค้า (Transfer)
Route::middleware(['auth'])->group(function () {
    Route::get('/transfer/send', [TransferController::class, 'create'])->name('transfer.create');
    Route::post('/transfer/send', [TransferController::class, 'send'])->name('transfer.send');
    Route::get('/transfer/pending', [TransferController::class, 'pending'])->name('transfer.pending');
    Route::post('/transfer/receive', [TransferController::class, 'receive'])->name('transfer.receive');
});

// Route สำหรับดูประวัติการทำรายการ (Transactions)
Route::get('/transactions', [TransactionController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('transactions.index');

// Route สำหรับลบรายการสต็อก (ใช้ในกรณีที่ต้องการแก้ไขข้อมูลสต็อกที่ผิดพลาด)
Route::delete('/inventory/{id}', [App\Http\Controllers\InventoryController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('inventory.destroy');



// --- IGNORE --- (ส่วนนี้เป็นโค้ดที่ Laravel สร้างมาให้แล้ว ไม่ต้องแก้ไข)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
