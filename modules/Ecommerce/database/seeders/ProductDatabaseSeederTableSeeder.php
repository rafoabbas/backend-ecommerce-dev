<?php

namespace Modules\Ecommerce\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Ecommerce\App\Models\Attribute;
use Modules\Ecommerce\App\Models\AttributeSet;
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

        $makeProducts = Product::factory(10000)->create();

        foreach ($makeProducts as $key => $product){

            //Product variation random create
            if (! $product['is_variation']) continue;

            //Attributeset color/size
            $attributeSets = AttributeSet::all();

            $default = true;

            $color = AttributeSet::where('slug', 'color')->first();
            $size = AttributeSet::where('slug', 'size')->first();
            $ram = AttributeSet::where('slug', 'ram')->first();
//            $gpu = AttributeSet::where('slug', 'gpu')->first();
//            $kamera = AttributeSet::where('slug', 'kamera')->first();

            $colors = $color->attributes()->pluck('id');
            $sizes = $size->attributes()->pluck('id');
            $rams = $ram->attributes()->pluck('id');
//            $gpus = $gpu->attributes()->pluck('id');
//            $kameras = $kamera->attributes()->pluck('id');

//            $crossJoins = $colors->crossJoin($sizes, $rams, $gpus, $kameras);
            $crossJoins = $colors->crossJoin($sizes, $rams);

            foreach ($crossJoins as $cobinations){
                $productVariation = ProductVariation::factory()
                    ->productId($product->id)
                    ->default($default)
                    ->create([
                        'name'  => $product->name,
                        'slug'  => $product->slug
                    ]);

                foreach ($cobinations as $cobination){
                    $product->setProductAttributes([
                        'product_id'    => $product->id,
                        'product_variation_id' => $productVariation->id,
                        'attribute_id' => $cobination,
                        'attribute_set_id' => $pluckAttributeSetId[$cobination],
                        'default' => $default
                    ]);
                }
                $default = false;
            }
        }
    }

    private function pluckAttributeSetId(){
        return Attribute::pluck('attribute_set_id', 'id')->toArray();
    }
}
