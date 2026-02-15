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
}
