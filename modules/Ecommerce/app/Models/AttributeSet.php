<?php

namespace Modules\Ecommerce\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'status',
        'order',
        'display_layout',
        'is_searchable',
        'is_comparable',
        'is_use_in_product_listing',
    ];

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }

    protected static function newFactory()
    {
//        return \Modules\Ecommerce\Database\factories\AttributeSetFactory::new();
    }
}
