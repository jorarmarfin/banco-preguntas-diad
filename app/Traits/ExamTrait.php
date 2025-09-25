<?php

namespace App\Traits;

use App\Models\Exam;

trait ExamTrait
{
    use ActiveTermTrait;

    /**
     * Obtener exámenes paginados
     */
    public function getExamsPaginated($perPage = 50)
    {
        return Exam::with('term')
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }

    /**
     * Obtener todos los exámenes
     */
    public function getAllExams()
    {
        return Exam::with('term')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Buscar un examen por ID
     */
    public function findExam($examId)
    {
        return Exam::with('term')->find($examId);
    }

    /**
     * Crear un nuevo examen
     */
    public function createExam($data)
    {
        return Exam::create($data);
    }

    /**
     * Actualizar un examen
     */
    public function updateExam(Exam $exam, $data)
    {
        return $exam->update($data);
    }

    /**
     * Eliminar un examen
     */
    public function deleteExam(Exam $exam)
    {
        return $exam->delete();
    }

    /**
     * Verificar si el examen tiene relaciones que impidan su eliminación
     */
    public function examHasRelations(Exam $exam)
    {
        return $exam->questions()->exists();
    }

    /**
     * Generar código único para el examen
     */
    public function generateExamCode()
    {
        do {
            $code = 'EX-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Exam::where('code', $code)->exists());

        return $code;
    }

    /**
     * Generar path automático para el examen basado en período activo y código
     */
    public function generateExamPath($code)
    {
        $activeTerm = $this->getActiveTerm();
        if (!$activeTerm) {
            throw new \Exception('No hay un período activo configurado.');
        }

        // Crear slug del código del examen (convertir a minúsculas y reemplazar guiones por guiones bajos o similar)
        $codeSlug = strtolower(str_replace('-', '_', $code));

        // Formato: periodoactivo/slug_del_codigo_examen/
        return strtolower('exams/'.$activeTerm->code) . '/' . $codeSlug . '/';
    }
}
