<?php
namespace Modules\Ecommerce\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Ecommerce\App\Enums\StatusEnum;
use Modules\Ecommerce\App\Models\Category;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name;

        return [
            'parent_id' => null,
            'name' => $name,
            'slug' => Str::slug($name),
            'position' => 1,
            'status' => StatusEnum::PUBLISHED(),
        ];
    }

    public function parentId($parentId): Factory
    {
        return $this->state(function (array $attributes) use ($parentId){
            return [
                'parent_id' => $parentId,
            ];
        });
    }
}

