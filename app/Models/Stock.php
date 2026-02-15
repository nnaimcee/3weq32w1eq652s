<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;

    // กำหนดความสัมพันธ์กับโมเดล Product
    protected $fillable = [
        'product_id',
        'location_id',
        'quantity',
        'reserved_qty',
        'lot_number',
        'received_date',
        'expiry_date',
    ];

    // เพิ่มฟังก์ชันเชื่อมต่อไปยัง Product 
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // เพิ่มฟังก์ชันเชื่อมต่อไปยัง Location 
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
