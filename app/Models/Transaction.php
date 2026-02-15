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
    ];
}
