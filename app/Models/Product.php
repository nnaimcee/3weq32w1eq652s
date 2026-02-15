<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sku', 'barcode', 'description', 'category', 'unit', 'min_stock','barcode_image'];
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
    //
}
