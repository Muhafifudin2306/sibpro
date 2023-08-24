<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AtributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('attributes')->insert([
            'attribute_name' => 'SPP',
            'attribute_price' => '80000',
            'user_id' => '1'
        ]);
        DB::table('attributes')->insert([
            'attribute_name' => 'Praktik',
            'attribute_price' => '80000',
            'user_id' => '1'
        ]);
        DB::table('attributes')->insert([
            'attribute_name' => 'OSIS & Pramuka',
            'attribute_price' => '80000',
            'user_id' => '1'
        ]);
        DB::table('attributes')->insert([
            'attribute_name' => 'Atribut OSIS dan Pramuka',
            'attribute_price' => '80000',
            'user_id' => '1'
        ]);
        DB::table('attributes')->insert([
            'attribute_name' => 'Dasi, Ikat Pinggang, dan Kaos Kaki',
            'attribute_price' => '50000',
            'user_id' => '1'
        ]);
        DB::table('attributes')->insert([
            'attribute_name' => 'Sampul Raport',
            'attribute_price' => '50000',
            'user_id' => '1'
        ]);
        DB::table('attributes')->insert([
            'attribute_name' => 'Kitab',
            'attribute_price' => '85000',
            'user_id' => '1'
        ]);
        DB::table('attributes')->insert([
            'attribute_name' => 'Seragam SMK',
            'attribute_price' => '495000',
            'user_id' => '1'
        ]);
        DB::table('attributes')->insert([
            'attribute_name' => 'Sepatu Pantofel',
            'attribute_price' => '150000',
            'user_id' => '1'
        ]);
        DB::table('attributes')->insert([
            'attribute_name' => 'Kartu OSIS',
            'attribute_price' => '20000',
            'user_id' => '1'
        ]);
        DB::table('attributes')->insert([
            'attribute_name' => 'Kerudung',
            'attribute_price' => '120000',
            'user_id' => '1'
        ]);
    }
}
