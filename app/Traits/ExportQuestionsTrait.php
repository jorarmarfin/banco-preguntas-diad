<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait ExportQuestionsTrait
{
    use ActiveTermTrait;

    /**
     * Exportar preguntas físicas del examen
     */
    public function exportExamQuestions($examId)
    {
        try {
            // Obtener el examen con sus preguntas
            $exam = $this->findExam($examId);
            if (!$exam) {
                return [
                    'success' => false,
                    'message' => 'Examen no encontrado'
                ];
            }

            // Obtener período activo
            $activeTerm = $this->getActiveTerm();
            if (!$activeTerm) {
                return [
                    'success' => false,
                    'message' => 'No hay período activo configurado'
                ];
            }

            // Crear la estructura de directorios de destino usando Storage
            $basePath = \App\Models\Setting::where('key', 'path_exams')->value('value') ?? 'private/exams';
            $termPath = $basePath . '/' . $activeTerm->code;
            $examPath = $termPath . '/' . $exam->code;

            // Crear directorios si no existen usando Storage
            if (!Storage::exists($basePath)) {
                Storage::makeDirectory($basePath);
            }
            if (!Storage::exists($termPath)) {
                Storage::makeDirectory($termPath);
            }
            if (!Storage::exists($examPath)) {
                Storage::makeDirectory($examPath);
            }

            $exportedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;
            $subjectPaths = [];

            // Obtener preguntas del examen agrupadas por asignatura
            $questionsBySubject = $exam->questions()
                ->with(['question.subject', 'question.chapter', 'question.topic', 'question.bank'])
                ->get()
                ->groupBy('question.subject.code');

            foreach ($questionsBySubject as $subjectCode => $examQuestions) {
                $subjectName = Str::of($examQuestions->first()->question->subject->name)->slug('-');
                $subjectPath = $examPath . '/' . $subjectName;
                $subjectPaths[] = [
                    'code' => $subjectCode,
                    'name' => $subjectName,
                    'path' => $subjectPath,
                    'count' => $examQuestions->count()
                ];

                // Crear directorio de asignatura si no existe
                if (!Storage::exists($subjectPath)) {
                    Storage::makeDirectory($subjectPath);
                }

                foreach ($examQuestions as $examQuestion) {
                    $question = $examQuestion->question;

                    // Construir la ruta de origen basada en el path de la pregunta usando Storage
                    $sourcePath = \App\Models\Setting::where('key', 'path_banks')->value('value') ?? 'private/banks';
                    // Verificar si la carpeta de origen existe
                    if (!Storage::exists($sourcePath) || !Storage::directoryExists($sourcePath)) {
                        Log::warning("Carpeta de pregunta no encontrada: {$sourcePath}");
                        $errorCount++;
                        continue;
                    }

                    // Construir la ruta de destino
                    $destinationPath = $subjectPath . '/' . $question->code;

                    // Verificar si ya existe en destino
                    if (Storage::exists($destinationPath)) {
                        Log::info("Pregunta ya exportada: {$question->code}");
                        $skippedCount++;
                        continue;
                    }

                    // Copiar la carpeta completa de la pregunta usando Storage
                    if ($this->copyDirectoryWithStorage($sourcePath, $destinationPath)) {
                        $exportedCount++;
                        Log::info("Pregunta exportada exitosamente: {$question->code}");
                    } else {
                        $errorCount++;
                        Log::error("Error al exportar pregunta: {$question->code}");
                    }
                }
            }

            return [
                'success' => true,
                'message' => 'Exportación completada',
                'data' => [
                    'exported' => $exportedCount,
                    'skipped' => $skippedCount,
                    'errors' => $errorCount,
                    'total' => $exportedCount + $skippedCount + $errorCount,
                    'exam_path' => $examPath,
                    'subjects' => $subjectPaths
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Error en exportación de preguntas: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error interno durante la exportación: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Copiar directorio recursivamente usando Storage
     */
    private function copyDirectoryWithStorage($sourcePath, $destinationPath)
    {
        try {
            if (!Storage::exists($sourcePath) || !Storage::directoryExists($sourcePath)) {
                return false;
            }

            // Crear directorio de destino
            if (!Storage::exists($destinationPath)) {
                Storage::makeDirectory($destinationPath);
            }

            // Obtener todos los archivos del directorio origen
            $files = Storage::allFiles($sourcePath);

            // Copiar cada archivo
            foreach ($files as $file) {
                $relativePath = str_replace($sourcePath . '/', '', $file);
                $destinationFile = $destinationPath . '/' . $relativePath;

                // Crear subdirectorios si es necesario
                $subdirectory = dirname($destinationFile);
                if ($subdirectory !== $destinationPath && !Storage::exists($subdirectory)) {
                    Storage::makeDirectory($subdirectory);
                }

                // Copiar el archivo
                Storage::copy($file, $destinationFile);
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Error copiando directorio con Storage: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener información de exportación de un examen
     */
    public function getExportInfo($examId)
    {
        try {
            $exam = $this->findExam($examId);
            if (!$exam) {
                return null;
            }

            $activeTerm = $this->getActiveTerm();
            if (!$activeTerm) {
                return null;
            }

            $basePath = \App\Models\Setting::where('key', 'path_exams')->value('value') ?? 'private/exams';
            $examPath = $basePath . '/' . $activeTerm->code . '/' . $exam->code;

            return [
                'exam_code' => $exam->code,
                'term_code' => $activeTerm->code,
                'export_path' => $examPath,
                'exists' => Storage::exists($examPath),
                'questions_count' => $exam->questions()->count()
            ];

        } catch (\Exception $e) {
            Log::error('Error obteniendo información de exportación: ' . $e->getMessage());
            return null;
        }
    }
}
