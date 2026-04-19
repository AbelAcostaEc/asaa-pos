<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'code', 'initial_stock', 'current_stock', 'purchase_date', 'cost_price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}