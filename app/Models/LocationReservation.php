<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocationReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'product_id',
        'reserved_by',
        'expected_qty',
        'note',
        'status',
        'expected_at',
    ];

    protected $casts = [
        'expected_at' => 'datetime',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function reserver()
    {
        return $this->belongsTo(User::class, 'reserved_by');
    }

    // Scope: only pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
