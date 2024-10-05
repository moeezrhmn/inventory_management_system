<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabourWork extends Model
{
    use HasFactory;

    protected $table = "labourwork";

    protected $fillable = ["labour_id","p_id", "pieces", "payment", "description"];

    public function labour()
    {
        return $this->belongsTo(Labour::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'p_id');
    }
}
