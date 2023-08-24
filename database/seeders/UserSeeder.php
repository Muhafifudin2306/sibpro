<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Muhammad Afifudin',
            'email' => 'muhafifudin662@gmail.com',
            'password' => bcrypt('admin123'),
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);
    }
}
