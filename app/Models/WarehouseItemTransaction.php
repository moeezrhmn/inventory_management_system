<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseItemTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_item_id',
        'quantity',
        'reference',
    ];

    public static function get_stock(){
        return self::sum('quantity');
    }
}
