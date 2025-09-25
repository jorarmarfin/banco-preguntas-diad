<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chapters = [];

        // Generar 10 capítulos para cada una de las 21 asignaturas
        for ($subjectId = 1; $subjectId <= 21; $subjectId++) {
            for ($chapterNumber = 1; $chapterNumber <= 10; $chapterNumber++) {
                $chapters[] = [
                    'code' => "{$chapterNumber}",
                    'name' => "Capítulo {$chapterNumber}",
                    'subject_id' => $subjectId,
                    'order' => $chapterNumber,
                ];
            }
        }

        DB::table('chapters')->insert($chapters);
    }
}
