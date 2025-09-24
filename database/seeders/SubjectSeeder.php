<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subjects')->insert([
            // Matemáticas (subject_category_id: 1)
            ['code' => 'AR', 'name' => 'Aritmética', 'subject_category_id' => 1],
            ['code' => 'AL', 'name' => 'Álgebra', 'subject_category_id' => 1],
            ['code' => 'GE', 'name' => 'Geometría', 'subject_category_id' => 1],
            ['code' => 'TR', 'name' => 'Trigonometría', 'subject_category_id' => 1],
            ['code' => 'CD', 'name' => 'Cálculo Diferencial', 'subject_category_id' => 1],
            ['code' => 'CI', 'name' => 'Cálculo Integral', 'subject_category_id' => 1],
            ['code' => 'MD', 'name' => 'Matemática Básica 1', 'subject_category_id' => 1],
            ['code' => 'MU', 'name' => 'Matemática Básica 2', 'subject_category_id' => 1],

            // Ciencias (subject_category_id: 2)
            ['code' => 'FI', 'name' => 'Física', 'subject_category_id' => 2],
            ['code' => 'QU', 'name' => 'Química', 'subject_category_id' => 2],

            // Letras/Humanidades (subject_category_id: 3)
            ['code' => 'CL', 'name' => 'Comunicación y Lengua', 'subject_category_id' => 3],
            ['code' => 'EC', 'name' => 'Economía', 'subject_category_id' => 3],
            ['code' => 'FL', 'name' => 'Filosofía', 'subject_category_id' => 3],
            ['code' => 'GD', 'name' => 'Geografía y Desarrollo', 'subject_category_id' => 3],
            ['code' => 'HI', 'name' => 'Historia del Perú y del Mundo', 'subject_category_id' => 3],
            ['code' => 'IN', 'name' => 'Inglés', 'subject_category_id' => 3],
            ['code' => 'LI', 'name' => 'Literatura', 'subject_category_id' => 3],
            ['code' => 'LO', 'name' => 'Lógica', 'subject_category_id' => 3],
            ['code' => 'PS', 'name' => 'Psicología', 'subject_category_id' => 3],

            // Razonamiento Matemático (subject_category_id: 4)
            ['code' => 'RM', 'name' => 'Raz. Matemático', 'subject_category_id' => 4],

            // Razonamiento Verbal (subject_category_id: 5)
            ['code' => 'RV', 'name' => 'Raz. Verbal', 'subject_category_id' => 5],
        ]);
    }
}
