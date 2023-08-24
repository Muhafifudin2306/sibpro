<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'category_name' => 'Reguler Putra',
            'user_id' => '1'
        ]);
        DB::table('categories')->insert([
            'category_name' => 'Reguler Putri',
            'user_id' => '1'
        ]);
        DB::table('categories')->insert([
            'category_name' => 'Gratis All',
            'user_id' => '1'
        ]);
    }
}
