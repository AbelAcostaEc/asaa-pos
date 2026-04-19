<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Inventory\Database\Factories\UnitFactory;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'abbreviation'];
}
