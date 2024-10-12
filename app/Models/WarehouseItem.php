<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseItem extends Model
{
    use HasFactory;
    protected $table = 'warehouse_items';
    protected $fillable = ['warehouse_id', 'item_name', 'pieces', 'unit_id', 'measurements'];
    
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    // Relationship with the Unit model
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function transactions()
    {
        return $this->hasMany(WarehouseItemTransaction::class, 'warehouse_item_id');
    }

    public function getTotalQuantityAttribute()
    {
        return $this->transactions()->sum('quantity');
    }
}
