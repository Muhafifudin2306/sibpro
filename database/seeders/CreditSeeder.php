<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('credits')->insert([
            'credit_name' => 'SPP Agustus',
            'credit_price' => '80000',
            'semester' => 'Gasal',
            'user_id' => '1'
        ]);
        DB::table('credits')->insert([
            'credit_name' => 'SPP September',
            'credit_price' => '80000',
            'semester' => 'Gasal',
            'user_id' => '1'
        ]);
        DB::table('credits')->insert([
            'credit_name' => 'SPP Oktober',
            'credit_price' => '80000',
            'semester' => 'Gasal',
            'user_id' => '1'
        ]);
        DB::table('credits')->insert([
            'credit_name' => 'SPP November',
            'credit_price' => '80000',
            'semester' => 'Gasal',
            'user_id' => '1'
        ]);
        DB::table('credits')->insert([
            'credit_name' => 'SPP Desember',
            'credit_price' => '80000',
            'semester' => 'Gasal',
            'user_id' => '1'
        ]);
        DB::table('credits')->insert([
            'credit_name' => 'SPP Maret',
            'credit_price' => '80000',
            'semester' => 'Gasal',
            'user_id' => '1'
        ]);
        DB::table('credits')->insert([
            'credit_name' => 'SPP April',
            'credit_price' => '80000',
            'semester' => 'Gasal',
            'user_id' => '1'
        ]);
        DB::table('credits')->insert([
            'credit_name' => 'SPP Mei',
            'credit_price' => '80000',
            'semester' => 'Gasal',
            'user_id' => '1'
        ]);
        DB::table('credits')->insert([
            'credit_name' => 'SPP Juni',
            'credit_price' => '80000',
            'semester' => 'Gasal',
            'user_id' => '1'
        ]);
        DB::table('credits')->insert([
            'credit_name' => 'SPP Desember',
            'credit_price' => '80000',
            'semester' => 'Gasal',
            'user_id' => '1'
        ]);
        DB::table('credits')->insert([
            'credit_name' => 'SPP Desember',
            'credit_price' => '80000',
            'semester' => 'Gasal',
            'user_id' => '1'
        ]);
        DB::table('credits')->insert([
            'credit_name' => 'SPP Desember',
            'credit_price' => '80000',
            'semester' => 'Gasal',
            'user_id' => '1'
        ]);
        DB::table('credits')->insert([
            'credit_name' => 'Gratis All',
            'credit_price' => '0',
            'semester' => 'Gasal',
            'user_id' => '1'
        ]);
        DB::table('credits')->insert([
            'credit_name' => 'Gratis All',
            'credit_price' => '0',
            'semester' => 'Genap',
            'user_id' => '1'
        ]);
    }
}
