<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Ecommerce\App\Models\Category;
use Modules\Ecommerce\App\Models\Product;
use Modules\Ecommerce\Database\Seeders\EcommerceDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EcommerceDatabaseSeeder::class);
//
//        $makeProduct = Product::factory()
//            ->create();
//
//        $makeProduct->categories()->sync($this->randomCategoriesId());
        // \App\Models\User::factory(10)->create();
    }

    public function randomCategoriesId(){
        $category = Category::whereNotNull('parent_id')->inRandomOrder()->first();
        return [$category->id, $category->parent_id];
    }
}
