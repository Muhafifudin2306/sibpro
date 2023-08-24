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
            'password' => bcrypt('admin123')
        ]);
    }
}
