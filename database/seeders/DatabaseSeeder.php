<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Location;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. สร้างผู้ใช้งาน (Admin)
        User::create([
            'name' => 'Admin WMS',
            'email' => 'admin@wms.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // 2. สร้างตำแหน่งจัดเก็บ (Location)
        $loc1 = Location::create([
            'name' => 'Z1-S1-B01', 'zone' => 'Z1', 'shelf' => 'S1', 'bin' => 'B01', 'type' => 'storage'
        ]);
        $loc2 = Location::create([
            'name' => 'Z1-S1-B02', 'zone' => 'Z1', 'shelf' => 'S1', 'bin' => 'B02', 'type' => 'storage'
        ]);
        $locTransit = Location::create([
            'name' => 'Transit-Area', 'type' => 'transit' // พื้นที่พักของระหว่างทาง
        ]);

        // 3. สร้างข้อมูลสินค้า (Product)
        $product1 = Product::create([
            'barcode' => '885000000001', 
            'name' => 'แก้วเก็บความเย็น', 
            'unit' => 'ใบ',
            'min_stock' => 10
        ]);

        // 4. สร้างสต็อกสินค้า (Stocks) - จำลอง 2 ล็อตเพื่อทดสอบ FIFO
        // ล็อตที่ 1: ของเก่า (รับเข้ามา 10 วันที่แล้ว) วางอยู่ช่อง B01
        Stock::create([
            'product_id' => $product1->id,
            'location_id' => $loc1->id,
            'quantity' => 20,
            'reserved_qty' => 0,
            'received_date' => Carbon::now()->subDays(10), 
        ]);

        // ล็อตที่ 2: ของใหม่ (รับเข้ามา 2 วันที่แล้ว) วางอยู่ช่อง B02
        Stock::create([
            'product_id' => $product1->id,
            'location_id' => $loc2->id,
            'quantity' => 50,
            'reserved_qty' => 0,
            'received_date' => Carbon::now()->subDays(2), 
        ]);
    }
}