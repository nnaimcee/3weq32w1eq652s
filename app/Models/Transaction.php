<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    // กำหนดความสัมพันธ์กับโมเดล User, Product, Location
    protected $fillable = [
        'user_id',
        'product_id',
        'from_location_id',
        'to_location_id',
        'quantity',
        'type',
        'status',
        'ref_doc_no',
        'notes',
    ];

    // เชื่อมไปหาคนทำรายการ
    public function user() {
        return $this->belongsTo(User::class);
    }

    // เชื่อมไปหาสินค้า
    public function product() {
        return $this->belongsTo(Product::class);
    }

    // เชื่อมไปหาตำแหน่งต้นทาง
    public function fromLocation() {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    // เชื่อมไปหาตำแหน่งปลายทาง
    public function toLocation() {
        return $this->belongsTo(Location::class, 'to_location_id');
    }
}
