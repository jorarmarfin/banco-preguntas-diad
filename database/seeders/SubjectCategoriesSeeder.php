<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subject_categories')->insert([
            ['name' => 'Matemáticas', 'description' => 'Asignaturas relacionadas con matemáticas.'],
            ['name' => 'Ciencias', 'description' => 'Asignaturas relacionadas con ciencias.'],
            ['name' => 'Letras', 'description' => 'Asignaturas relacionadas con aptitud académica.'],
            ['name' => 'Razonamiento Matemático', 'description' => 'Asignaturas relacionadas con razonamiento matemático.'],
            ['name' => 'Razonamiento Verbal', 'description' => 'Asignaturas relacionadas con razonamiento verbal.'],
        ]);
    }
}
