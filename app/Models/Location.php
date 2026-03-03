<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    // กำหนดความสัมพันธ์กับโมเดล Stock
    protected $fillable = ['name', 'zone', 'shelf', 'bin', 'type', 'status', 'capacity'];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * ตรวจสอบและอัปเดตสถานะ 'full' โดยอัตโนมัติตาม capacity
     * เรียกหลังรับสินค้าเข้าหรือเบิกสินค้าออก
     */
    public function checkAndUpdateStatus(): void
    {
        // ไม่แตะ inactive — ต้องแก้ manual เท่านั้น
        if ($this->status === 'inactive') return;

        $totalQty = $this->stocks()->sum('quantity');
        $capacity = $this->capacity ?? 5000;

        if ($totalQty >= $capacity && $this->status !== 'full') {
            $this->update(['status' => 'full']);
        } elseif ($totalQty < $capacity && $this->status === 'full') {
            // คืนสถานะเป็น active ถ้าของลดลงต่ำกว่า capacity
            $this->update(['status' => 'active']);
        }
    }
}
