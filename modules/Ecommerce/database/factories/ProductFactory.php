<?php
namespace Modules\Ecommerce\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Ecommerce\App\Enums\StatusEnum;
use Modules\Ecommerce\App\Models\Brand;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Ecommerce\App\Models\Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name;
        $price = rand(1000, 3000);
        return [
            'vendor_id'             => null,
            'brand_id'              => Brand::inRandomOrder()->value('id'),
            'name'                  => $name,
            'description'           => $this->faker->sentence(3),
            'content'               => $this->faker->sentence(10),
            'slug'                  => Str::slug($name),
            'discount_price'        => $price - rand(80, 200),
            'original_price'        => $price,
            'quantity'              => rand(1,9),
            'model_no'              => Str::random(12),
            'barcode'               => rand(100000000,900000000),
            'sku'                   => 'SKU-'.rand(1000000,9000000),
            'status'                => StatusEnum::PUBLISHED(),
            'approved'              => true,
            'auto_approve'          => true,
            'is_variation'          => rand(0,1)
        ];
    }


}

