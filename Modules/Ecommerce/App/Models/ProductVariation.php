<?php

namespace Modules\Ecommerce\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'description',
        'content',
        'slug',
        'discount_price',
        'original_price',
        'quantity',
        'model_no',
        'barcode',
        'sku',
        'status',
        'default'
    ];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    protected static function newFactory()
    {
        return \Modules\Ecommerce\Database\Factories\ProductVariationFactory::new();
    }
}
