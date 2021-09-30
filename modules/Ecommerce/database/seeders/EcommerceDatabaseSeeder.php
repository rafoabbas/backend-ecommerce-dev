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
        $this->categories();

        // $this->call("OthersTableSeeder");
    }

    public function brands(){
        if (! Brand::count()){
            Brand::factory(5)->create();
        }
    }
    public function categories(){
        if (! Category::count()){
            Category::factory(20)->create();
        }
    }
}
