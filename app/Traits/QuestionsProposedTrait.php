<?php

namespace App\Traits;

use App\Enums\QuestionStatus;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Professors;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

trait QuestionsProposedTrait
{
    /**
     * Crear propuesta de preguntas para un profesor
     */
    public function createQuestionProposal($professorId, $quantity, $subjectId)
    {
        // Validar que hay un banco activo (usando el método del BankTrait)
        if (!$this->hasActiveBank()) {
            return [
                'success' => false,
                'message' => 'No hay un banco activo para crear la propuesta.'
            ];
        }

        // Validar campos requeridos
        $validation = $this->validateProposalData($professorId, $quantity, $subjectId);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $validation['message']
            ];
        }

        try {
            $activeBank = $this->getActiveBank();
            $subject = Subject::find($subjectId);
            $professor = Professors::find($professorId);

            // Crear estructura de carpetas: (slug banco)/(slug asignatura)/(slug profesor)
            $basePath = $this->createProposalFolderStructure($activeBank, $subject, $professor);

            // Buscar preguntas aprobadas existentes para esta asignatura
            $existingQuestions = $this->getExistingApprovedQuestions($subjectId);

            // Generar códigos de preguntas faltantes
            $proposedQuestions = $this->generateQuestionCodes($existingQuestions, $quantity);

            // Crear carpetas vacías para las preguntas propuestas
            $createdFolders = $this->createQuestionFolders($basePath, $proposedQuestions);

            // Generar y guardar el archivo CSV
            $csvFileName = $this->generateProposalCsv($basePath, $proposedQuestions, $professor, $subject);

            return [
                'success' => true,
                'message' => "Se crearon {$quantity} carpetas de preguntas para el profesor {$professor->name} en la asignatura {$subject->name}.",
                'details' => [
                    'folders_created' => $createdFolders,
                    'csv_file' => $csvFileName,
                    'base_path' => $basePath
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Ocurrió un error al crear la propuesta: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validar datos de la propuesta
     */
    private function validateProposalData($professorId, $quantity, $subjectId)
    {
        if (empty($professorId)) {
            return ['valid' => false, 'message' => 'El profesor es requerido.'];
        }

        if (empty($quantity) || !is_numeric($quantity) || $quantity < 1 || $quantity > 100) {
            return ['valid' => false, 'message' => 'La cantidad debe ser un número entre 1 y 100.'];
        }

        if (empty($subjectId)) {
            return ['valid' => false, 'message' => 'La asignatura es requerida.'];
        }

        // Verificar que el profesor existe
        if (!Professors::where('id', $professorId)->exists()) {
            return ['valid' => false, 'message' => 'El profesor seleccionado no existe.'];
        }

        // Verificar que la asignatura existe
        if (!Subject::where('id', $subjectId)->exists()) {
            return ['valid' => false, 'message' => 'La asignatura seleccionada no existe.'];
        }

        return ['valid' => true];
    }

    /**
     * Crear estructura de carpetas para la propuesta
     */
    private function createProposalFolderStructure($activeBank, $subject, $professor)
    {
        $bankSlug = Str::slug($activeBank->name);
        $subjectSlug = Str::slug($subject->name);
        $professorSlug = Str::slug($professor->name);

        $basePath = "banks/{$bankSlug}/{$subjectSlug}/{$professorSlug}";

        // Crear la carpeta base si no existe
        if (!Storage::exists($basePath)) {
            Storage::makeDirectory($basePath);
        }

        return $basePath;
    }

    /**
     * Obtener preguntas aprobadas existentes para una asignatura
     */
    private function getExistingApprovedQuestions($subjectId)
    {
        return Question::where('subject_id', $subjectId)
            ->where('status', QuestionStatus::APPROVED->value)
            ->pluck('code')
            ->toArray();
    }

    /**
     * Crear carpetas vacías para las preguntas propuestas
     */
    private function createQuestionFolders($basePath, $proposedQuestions)
    {
        $createdFolders = [];

        foreach ($proposedQuestions as $questionCode) {
            $folderPath = "{$basePath}/{$questionCode}";
            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath);
                $createdFolders[] = $questionCode;
            }
        }

        return $createdFolders;
    }

    /**
     * Generar archivo CSV de la propuesta
     */
    private function generateProposalCsv($basePath, $proposedQuestions, $professor, $subject)
    {
        // Generar datos para el CSV
        $csvData = [];
        foreach ($proposedQuestions as $questionCode) {
            $csvData[] = [
                'codigo' => $questionCode,
                'capitulo' => '', // Vacío para que el profesor complete
                'tema' => '', // Vacío para que el profesor complete
                'dificultad' => '', // Vacío para que el profesor complete
            ];
        }

        // Crear nombre del archivo CSV
        $professorSlug = Str::slug($professor->name);
        $subjectSlug = Str::slug($subject->name);
        $csvFileName = "propuesta_{$professorSlug}_{$subjectSlug}_" . now()->format('Y-m-d_H-i-s') . '.csv';
        $csvPath = "{$basePath}/{$csvFileName}";

        // Usar Laravel Excel para crear el CSV
        Excel::store(new class($csvData) implements FromArray, WithHeadings {
            private $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }

            public function headings(): array
            {
                return ['codigo', 'capitulo', 'tema', 'dificultad'];
            }
        }, $csvPath);

        return $csvFileName;
    }

    /**
     * Generar códigos de preguntas faltantes
     */
    private function generateQuestionCodes(array $existingQuestions, int $quantity): array
    {
        $proposedQuestions = [];
        $counter = 1;

        // Extraer números de las preguntas existentes
        $existingNumbers = [];
        foreach ($existingQuestions as $code) {
            if (preg_match('/p(\d+)/i', $code, $matches)) {
                $existingNumbers[] = (int)$matches[1];
            }
        }

        // Ordenar números existentes
        sort($existingNumbers);

        // Generar códigos faltantes
        while (count($proposedQuestions) < $quantity) {
            $questionCode = 'p' . $counter;

            // Si el código no existe en las preguntas aprobadas, agregarlo
            if (!in_array($counter, $existingNumbers)) {
                $proposedQuestions[] = $questionCode;
            }

            $counter++;
        }

        return $proposedQuestions;
    }
}
