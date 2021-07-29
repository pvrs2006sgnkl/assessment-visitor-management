<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB, Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'System Manager',
            'email' => 's-admin@mailinator.com',
            'password' => bcrypt('sPassword'),
            'mobile_number' => substr(mt_rand(111111, 9999999), 0, 8),
            'nric' => substr(mt_rand(111111, 9999999), 0, 5),
            'user_type' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
