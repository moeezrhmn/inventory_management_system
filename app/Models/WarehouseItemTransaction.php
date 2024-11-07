<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseItemTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_item_id',
        'supplier_id',
        'quantity',
        'reference',
        'per_piece_price',
        'total_payment',
        'total_paid',
    ];

    public function supplier()
    { 
        return $this->belongsTo(Supplier::class);
    } 
    public function warehouse_item()
    { 
        return $this->belongsTo(WarehouseItem::class)->with('unit');
    } 


    public static function get_stock($warehouse_item_id = null){
        if ($warehouse_item_id) {
            return self::where('warehouse_item_id', $warehouse_item_id)->sum('quantity');
        }
        return self::sum('quantity');
    }
}
