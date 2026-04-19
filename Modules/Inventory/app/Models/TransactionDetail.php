<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'product_id', 'batch_id', 'presentation_id', 'quantity', 'quantity_presentation', 'units_per_presentation', 'unit_price', 'subtotal', 'name', 'description', 'code'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function presentation()
    {
        return $this->belongsTo(Presentation::class);
    }
}