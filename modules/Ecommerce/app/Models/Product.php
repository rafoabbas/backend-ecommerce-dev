<?php

namespace Modules\Ecommerce\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
//        return \Modules\Ecommerce\Database\factories\ProductFactory::new();
    }
}
