<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Unit::factory(50)->create();
        \App\Models\Rule::factory(1)->create();
        // \App\Models\User::factory(100)->create();

        $this->call([
            UserSeeder::class
        ]);
    }
}
