<?php

namespace Modules\Ecommerce\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttribute extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'product_variation_id',
        'attribute_id',
        'attribute_set_id',
        'default'
    ];

    public function productVariation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class)->select('id','title', 'slug', 'color')->orderBy('order', 'asc');
    }

    public function attributeSet(): BelongsTo
    {
        return $this->belongsTo(AttributeSet::class)->select('id','title', 'slug','display_layout');
    }

    protected static function newFactory()
    {
//        return \Modules\Ecommerce\Database\factories\ProductAttributeFactory::new();
    }
}
