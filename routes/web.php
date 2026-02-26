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
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ScannerController;

// --- IGNORE --- (ส่วนนี้เป็นโค้ดที่ Laravel สร้างมาให้แล้ว ไม่ต้องแก้ไข)
Route::get('/dashboard', function () {
    $transitLocationIds = \App\Models\Location::where('type', 'transit')->pluck('id');

    // 1. จำนวนสินค้าทั้งหมด
    $totalProducts = Product::count();

    // 2. สต็อกในคลัง (ไม่นับ transit)
    $totalStock = Stock::whereNotIn('location_id', $transitLocationIds)->sum('quantity');

    // 3. จำนวนจอง
    $totalReserved = Stock::whereNotIn('location_id', $transitLocationIds)->sum('reserved_qty');

    // 4. ระหว่างทาง
    $totalTransit = Stock::whereIn('location_id', $transitLocationIds)->sum('quantity');

    // 5. พร้อมจ่าย
    $totalAvailable = $totalStock - $totalReserved;

    // 6. จำนวนตำแหน่งทั้งหมด
    $totalLocations = \App\Models\Location::where('type', 'storage')->count();

    // 7. สินค้า Low Stock
    // - ถ้าสินค้าสต็อก = 0 → แสดง Low Stock เสมอ (หมด)
    // - ถ้าสินค้าสต็อก <= min_stock (เมื่อ min_stock > 0) → แสดง Low Stock (ใกล้หมด)
    $allProducts = Product::withSum(['stocks as stocks_sum_quantity' => function($q) use ($transitLocationIds) {
            $q->whereNotIn('location_id', $transitLocationIds);
        }], 'quantity')
        ->get();

    $lowStockProducts = $allProducts->filter(function($product) {
        $currentStock = $product->stocks_sum_quantity ?? 0;
        // สินค้าหมดสต็อก
        if ($currentStock <= 0) return true;
        // สินค้าต่ำกว่าขั้นต่ำ
        if ($product->min_stock > 0 && $currentStock <= $product->min_stock) return true;
        return false;
    })->sortBy('stocks_sum_quantity');
    $lowStockCount = $lowStockProducts->count();

    // 8. กิจกรรมล่าสุด 10 รายการ
    $recentActivities = Transaction::with(['product', 'user', 'fromLocation', 'toLocation'])
        ->latest()
        ->take(10)
        ->get();

    // 9. Pending transfers
    $pendingTransfers = Transaction::where('type', 'TRANSFER')
        ->where('status', 'pending')
        ->count();

    // 10. Chart: รายการต่อวัน 7 วันล่าสุด (นับจำนวนรายการ)
    $dailyData = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i)->toDateString();
        $label = now()->subDays($i)->format('d/m');
        $dailyData[] = [
            'label' => $label,
            'in' => Transaction::where('type', 'IN')->whereDate('created_at', $date)->count(),
            'out' => Transaction::where('type', 'OUT')->whereDate('created_at', $date)->count(),
            'transfer' => Transaction::where('type', 'TRANSFER')->whereDate('created_at', $date)->count(),
            'reserve' => Transaction::where('type', 'RESERVE')->whereDate('created_at', $date)->count(),
        ];
    }

    // 11. Chart: สต็อกแยกตาม Zone
    $stockByZone = \App\Models\Location::where('type', 'storage')
        ->with(['stocks' => fn($q) => $q->select('location_id', 'quantity')])
        ->get()
        ->groupBy('zone')
        ->map(fn($locs) => $locs->sum(fn($l) => $l->stocks->sum('quantity')))
        ->filter(fn($v) => $v > 0);

    return view('dashboard', compact(
        'totalProducts', 'totalStock', 'totalReserved', 'totalTransit',
        'totalAvailable', 'totalLocations', 'lowStockCount', 'lowStockProducts',
        'recentActivities', 'pendingTransfers', 'dailyData', 'stockByZone'
    ));
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
    Route::get('/products/{id}/print', [ProductController::class, 'printBarcode'])->name('products.print_barcode');
    Route::get('/products/{id}/qrcode', [ProductController::class, 'printQrCode'])->name('products.print_qrcode');
    Route::get('/scanner', [ScannerController::class, 'index'])->name('scanner.index');
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

// API Route สำหรับดึงข้อมูลสินค้าโดยใช้บาร์โค้ด (ใช้ในฟอร์มรับของเข้า)
Route::get('/api/products/{barcode}', [App\Http\Controllers\ProductController::class, 'getByBarcode'])
    ->middleware(['auth']);

// Routes สำหรับจัดการการจองสินค้า (Reservation)
Route::post('/reservation/reserve', [App\Http\Controllers\ReservationController::class, 'reserve'])
    ->middleware(['auth'])
    ->name('reservation.reserve');

Route::post('/reservation/release', [App\Http\Controllers\ReservationController::class, 'release'])
    ->middleware(['auth'])
    ->name('reservation.release');

// API Route สำหรับดึงข้อมูลสินค้าที่อยู่ใน Location ที่ถูกคลิก (ใช้ในแผนที่คลังสินค้า)
Route::get('/api/locations/{id}/items', [App\Http\Controllers\LocationController::class, 'getItems']);

// Routes สำหรับจัดการสถานที่ (เฉพาะ Admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/locations', [App\Http\Controllers\LocationController::class, 'index'])->name('locations.index');
    Route::post('/locations', [App\Http\Controllers\LocationController::class, 'store'])->name('locations.store');
    Route::put('/locations/{id}', [App\Http\Controllers\LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{id}', [App\Http\Controllers\LocationController::class, 'destroy'])->name('locations.destroy');
});

// --- IGNORE --- (ส่วนนี้เป็นโค้ดที่ Laravel สร้างมาให้แล้ว ไม่ต้องแก้ไข)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
