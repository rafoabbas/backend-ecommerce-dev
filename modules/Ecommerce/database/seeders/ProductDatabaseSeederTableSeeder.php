<?php

namespace Modules\Ecommerce\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Ecommerce\App\Models\Attribute;
use Modules\Ecommerce\App\Models\AttributeSet;
use Modules\Ecommerce\App\Models\Category;
use Modules\Ecommerce\App\Models\Product;
use Modules\Ecommerce\App\Models\ProductVariation;

class ProductDatabaseSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $pluckAttributeSetId = $this->pluckAttributeSetId();

        $makeProducts = Product::factory(10000)
            ->create();

        foreach ($makeProducts as $key => $product){

            $product->categories()->sync($this->randomCategoriesId());

            //Product variation random create
            if (! $product['is_variation']) continue;

            //Attributeset color/size
            $default = true;

            $crossJoins = $this->crossJoin();

            foreach ($crossJoins as $combinations){
                $productVariation = ProductVariation::factory()
                    ->productId($product->id)
                    ->default($default)
                    ->create([
                        'name'  => $product->name,
                        'slug'  => $product->slug
                    ]);

                foreach ($combinations as $combination){
                    $product->setProductAttributes([
                        'product_id'    => $product->id,
                        'product_variation_id' => $productVariation->id,
                        'attribute_id' => $combination,
                        'attribute_set_id' => $pluckAttributeSetId[$combination],
                        'default' => $default
                    ]);
                }
                $default = false;
            }
        }
    }

    public function crossJoin()
    {
        $color = AttributeSet::where('slug', 'color')->first();
        $size = AttributeSet::where('slug', 'size')->first();
        $ram = AttributeSet::where('slug', 'ram')->first();

        $colors = $color->attributes()->pluck('id');
        $sizes = $size->attributes()->pluck('id');
        $rams = $ram->attributes()->pluck('id');

        return  $colors->crossJoin($sizes, $rams);
    }

    public function randomCategoriesId(): array
    {
        $category = Category::whereNotNull('parent_id')->inRandomOrder()->first();
        return [$category->id, $category->parent_id];
    }

    private function pluckAttributeSetId(): array
    {
        return Attribute::pluck('attribute_set_id', 'id')->toArray();
    }
}
