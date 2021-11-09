<?php
namespace Modules\Ecommerce\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ecommerce\App\Enums\StatusEnum;
use Modules\Ecommerce\App\Models\Brand;

class BrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Brand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'status' => StatusEnum::PUBLISHED(),
        ];
    }
}

