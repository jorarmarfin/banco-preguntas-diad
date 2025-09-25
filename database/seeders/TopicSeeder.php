<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [];

        // Obtener todos los capítulos existentes
        $chapters = DB::table('chapters')->get();

        foreach ($chapters as $chapter) {
            // Generar 5 temas para cada capítulo
            for ($topicNumber = 1; $topicNumber <= 5; $topicNumber++) {
                $topics[] = [
                    'code' => "{$topicNumber}",
                    'name' => "Tema {$topicNumber}",
                    'chapter_id' => $chapter->id,
                    'order' => $topicNumber,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insertar todos los temas en lotes para mejor rendimiento
        DB::table('topics')->insert($topics);
    }
}
