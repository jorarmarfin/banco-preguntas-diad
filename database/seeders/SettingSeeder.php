<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            [
                'key' => 'path_imports',
                'value' => 'imports',
            ],
            [
                'key' => 'path_banks',
                'value' => 'banks',
            ],
            [
                'key' => 'path_exams',
                'value' => 'exams',
            ]
        ]);
    }
}
