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
                'key' => 'path_questions_storage',
                'value' => 'app/private/banks',
            ],
            [
                'key' => 'path_exam_export',
                'value' => 'app/private/exams',
            ],
            [
                'key' => 'path_import_base',
                'value' => 'import',
            ],
            [
                'key' => 'path_import_banks',
                'value' => 'private/import/banks',
            ],
            [
                'key' => 'path_banks_base',
                'value' => 'banks',
            ],
            [
                'key' => 'path_private_banks',
                'value' => 'private/banks',
            ]
        ]);
    }
}
