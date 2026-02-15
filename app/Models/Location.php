<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    // กำหนดความสัมพันธ์กับโมเดล Stock
    protected $fillable = ['name', 'zone', 'shelf', 'bin', 'type', 'status'];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
