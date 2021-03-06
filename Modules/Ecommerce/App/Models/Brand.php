<?php

namespace Modules\Ecommerce\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Ecommerce\App\Enums\StatusEnum;
use Modules\Ecommerce\Database\Factories\BrandFactory;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'enabled'
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    protected static function newFactory()
    {
        return BrandFactory::new();
    }
}
