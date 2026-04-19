<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presentation extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'name', 'units'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}