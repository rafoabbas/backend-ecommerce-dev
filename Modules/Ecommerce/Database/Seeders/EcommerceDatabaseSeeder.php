<?php

namespace Modules\Ecommerce\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Ecommerce\App\Models\Brand;
use Modules\Ecommerce\App\Models\Category;

class EcommerceDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->brands();

//        $this->categories();

        $this->call(CategoryDatabaseTableTableSeeder::class);
        $this->call(AttributeDatabaseSeederTableSeeder::class);
        $this->products(10);
    }

    public function products($recursive){
        $recursive -= 1;
        $this->call(ProductDatabaseSeederTableSeeder::class);
        if (! $recursive) return;
        $this->products($recursive);
    }



    public function brands(){
        if (! Brand::count()){
            Brand::factory(5)->create();
        }
    }



}
