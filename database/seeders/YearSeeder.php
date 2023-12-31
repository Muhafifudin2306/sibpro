<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('years')->insert([
            'year_name' => '2022/2023',
            'year_status' => 'active',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);
    }
}
