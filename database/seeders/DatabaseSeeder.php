<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        // \App\Models\User::factory(10)->create();
    }
}
