<?php

namespace Modules\Ecommerce\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryProduct extends Model
{
    use HasFactory;

    protected $table = 'category_product';

    protected $fillable = [];

    protected static function newFactory()
    {
//        return \Modules\Ecommerce\Database\factories\CategoryProductFactory::new();
    }
}
