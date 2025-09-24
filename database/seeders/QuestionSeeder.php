<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [];

        // Obtener todos los temas existentes con sus relaciones
        $topics = DB::table('topics')
            ->join('chapters', 'topics.chapter_id', '=', 'chapters.id')
            ->join('subjects', 'chapters.subject_id', '=', 'subjects.id')
            ->select('topics.id as topic_id', 'chapters.id as chapter_id', 'subjects.id as subject_id', 'subjects.code')
            ->get();

        // Obtener un período por defecto (el primero disponible)
        $defaultTerm = DB::table('terms')->first();

        if (!$defaultTerm) {
            $this->command->error('No hay períodos disponibles. Ejecute primero el seeder de términos.');
            return;
        }

        $difficulties = ['easy', 'medium', 'hard'];
        $statuses = ['draft', 'approved', 'archived'];

        foreach ($topics as $topic) {
            // Generar 3 preguntas para cada tema
            for ($questionNumber = 1; $questionNumber <= 3; $questionNumber++) {
                $code = $topic->code . str_pad(
                    ($topic->topic_id * 10) + $questionNumber,
                    3,
                    '0',
                    STR_PAD_LEFT
                );

                $difficulty = $difficulties[array_rand($difficulties)];
                $status = $statuses[array_rand($statuses)];

                $questions[] = [
                    'code' => $code,
                    'subject_id' => $topic->subject_id,
                    'chapter_id' => $topic->chapter_id,
                    'topic_id' => $topic->topic_id,
                    'term_id' => $defaultTerm->id,
                    'difficulty' => $difficulty,
                    'latex_body' => "¿Cuál es la respuesta correcta para la pregunta {$questionNumber} del tema {$topic->topic_id}?",
                    'latex_solution' => "La solución para la pregunta {$questionNumber} es...",
                    'status' => $status,
                    'estimated_time' => rand(180, 600), // Entre 3 y 10 minutos
                    'comments' => "Comentario para la pregunta {$questionNumber}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insertar todas las preguntas en lotes para mejor rendimiento
        if (!empty($questions)) {
            DB::table('questions')->insert($questions);
        }
    }
}
