<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
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

            // Crear la estructura de directorios de destino
            $basePath = storage_path(config('app.paths.exam_export'));
            $termPath = $basePath . '/' . $activeTerm->code;
            $examPath = $termPath . '/' . $exam->code;

            // Crear directorios si no existen
            if (!File::exists($basePath)) {
                File::makeDirectory($basePath, 0755, true);
            }
            if (!File::exists($termPath)) {
                File::makeDirectory($termPath, 0755, true);
            }
            if (!File::exists($examPath)) {
                File::makeDirectory($examPath, 0755, true);
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
                if (!File::exists($subjectPath)) {
                    File::makeDirectory($subjectPath, 0755, true);
                }

                foreach ($examQuestions as $examQuestion) {
                    $question = $examQuestion->question;

                    // Construir la ruta de origen basada en el path de la pregunta
                    $sourcePath = storage_path(config('app.paths.questions_storage') . '/' . $question->path);

                    // Verificar si la carpeta de origen existe
                    if (!File::exists($sourcePath) || !File::isDirectory($sourcePath)) {
                        Log::warning("Carpeta de pregunta no encontrada: {$sourcePath}");
                        $errorCount++;
                        continue;
                    }

                    // Construir la ruta de destino
                    $destinationPath = $subjectPath . '/' . $question->code;

                    // Verificar si ya existe en destino
                    if (File::exists($destinationPath)) {
                        Log::info("Pregunta ya exportada: {$question->code}");
                        $skippedCount++;
                        continue;
                    }

                    // Copiar la carpeta completa de la pregunta
                    if ($this->copyDirectory($sourcePath, $destinationPath)) {
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
     * Copiar directorio recursivamente
     */
    private function copyDirectory($source, $destination)
    {
        try {
            if (!File::exists($source) || !File::isDirectory($source)) {
                return false;
            }

            // Crear directorio de destino
            File::makeDirectory($destination, 0755, true);

            // Obtener todos los archivos y directorios
            $items = File::allFiles($source);
            $directories = File::directories($source);

            // Copiar directorios
            foreach ($directories as $dir) {
                $relativePath = str_replace($source, '', $dir);
                $newDir = $destination . $relativePath;
                File::makeDirectory($newDir, 0755, true);
            }

            // Copiar archivos
            foreach ($items as $file) {
                $relativePath = str_replace($source, '', $file->getPathname());
                $newFile = $destination . $relativePath;
                File::copy($file->getPathname(), $newFile);
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Error copiando directorio: ' . $e->getMessage());
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

            $basePath = storage_path(config('app.paths.exam_export'));
            $examPath = $basePath . '/' . $activeTerm->code . '/' . $exam->code;

            return [
                'exam_code' => $exam->code,
                'term_code' => $activeTerm->code,
                'export_path' => $examPath,
                'exists' => File::exists($examPath),
                'questions_count' => $exam->questions()->count()
            ];

        } catch (\Exception $e) {
            Log::error('Error obteniendo información de exportación: ' . $e->getMessage());
            return null;
        }
    }
}
