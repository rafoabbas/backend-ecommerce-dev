<?php

namespace Modules\Ecommerce\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Ecommerce\App\Enums\StatusEnum;

/**
 * @method findSlugId($slug, $id)
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'brand_id',
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
        'approved',
        'auto_approve',
        'is_variation'
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];


    public function defaultProduct(): HasOne
    {
        return $this->hasOne(ProductVariation::class);
    }

    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return HasMany
     */
    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }


    /**
     * @return HasMany
     */
    public function productAttributes() : HasMany
    {
        return $this->hasMany(ProductAttribute::class)->orderBy('product_id', 'ASC');
    }

    /**
     * @param array $array
     * @return Model
     */
    public function setProductAttributes(array $array): Model
    {
        return $this->productAttributes()->create([
            'product_variation_id'  => $array['product_variation_id'],
            'attribute_id'          => $array['attribute_id'],
            'attribute_set_id'      => $array['attribute_set_id'],
            'default'               => $array['default']
        ]);
    }

    /**
     * @return \Modules\Ecommerce\Database\Factories\ProductFactory
     */
    protected static function newFactory()
    {
        return \Modules\Ecommerce\Database\Factories\ProductFactory::new();
    }

    /**
     * @param $model
     * @param $slug
     * @param $id
     * @return mixed
     */
    public function scopeFindSlugId($model, $slug, $id){
        return $model->where('slug', $slug)
            ->where('id', $id);
    }
}
