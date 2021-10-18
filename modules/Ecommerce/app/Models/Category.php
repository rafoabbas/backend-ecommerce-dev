<?php

namespace Modules\Ecommerce\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Ecommerce\App\Enums\StatusEnum;
use Modules\Ecommerce\Database\Factories\CategoryFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [ 'parent_id', 'name', 'slug', 'position', 'status'];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,'category_product', 'product_id', 'category_id');
    }


    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}
