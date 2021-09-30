<?php

namespace Modules\Ecommerce\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Ecommerce\Database\Factories\CategoryFactory;

class Category extends Model
{
    use HasFactory;

    public const PUBLISHED = 'published';
    public const DRAFT = 'draft';
    public const PENDING = 'pending';

    protected $fillable = [ 'parent_id', 'name', 'slug', 'position', 'status'];

    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}
