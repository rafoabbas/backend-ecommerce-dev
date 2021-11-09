<?php

namespace Modules\Ecommerce\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Ecommerce\App\Models\Category;

class CategoryDatabaseTableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        if (! Category::count()){
            $categories = Category::factory(20)->create();

            foreach ($categories as $category){
                Category::factory(20)->parentId($category->id)->create();
            }
        }
    }
}
