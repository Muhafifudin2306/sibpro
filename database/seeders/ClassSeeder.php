<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('student_classes')->insert([
            'class_name' => 'X TKJ A',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);
        DB::table('student_classes')->insert([
            'class_name' => 'X TKJ B',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);
        DB::table('student_classes')->insert([
            'class_name' => 'XI TKJ A',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);
        DB::table('student_classes')->insert([
            'class_name' => 'XI TKJ B',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);
        DB::table('student_classes')->insert([
            'class_name' => 'XII TKJ A',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);
        DB::table('student_classes')->insert([
            'class_name' => 'XII TKJ B',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);

        DB::table('student_classes')->insert([
            'class_name' => 'X TSM A',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);
        DB::table('student_classes')->insert([
            'class_name' => 'X TSM B',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);

        DB::table('student_classes')->insert([
            'class_name' => 'XI TSM A',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);
        DB::table('student_classes')->insert([
            'class_name' => 'XI TSM B',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);

        DB::table('student_classes')->insert([
            'class_name' => 'XII TSM A',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);
        DB::table('student_classes')->insert([
            'class_name' => 'XII TSM B',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);


        DB::table('student_classes')->insert([
            'class_name' => 'X BB',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);
        DB::table('student_classes')->insert([
            'class_name' => 'XI BB',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);
        DB::table('student_classes')->insert([
            'class_name' => 'XII BB',
            'user_id' => '1',
            'created_at' => '2023-08-24 21:33:36',
            'updated_at' => '2023-08-24 21:33:36'
        ]);
    }
}
