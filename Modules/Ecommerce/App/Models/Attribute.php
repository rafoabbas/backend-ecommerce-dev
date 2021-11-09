<?php

namespace Modules\Ecommerce\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Ecommerce\App\Enums\StatusEnum;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'color',
        'status',
        'order',
        'attribute_set_id',
        'is_default',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    protected static function newFactory()
    {
//        return \Modules\Ecommerce\Database\Factories\AttributeFactory::new();
    }
}
