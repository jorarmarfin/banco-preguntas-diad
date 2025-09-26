<?php

namespace App\Traits;

use App\Models\Question;
use App\Models\Chapter;
use App\Models\Topic;
use App\Models\Term;
use App\Models\Bank;
use App\Models\Subject;
use App\Enums\QuestionStatus;
use App\Enums\QuestionDifficulty;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;

trait ImportQuestionsTrait
{
    /**
     * Importar preguntas desde CSV
     */
    public function importQuestionsFromCsv($csvFile, $folderName, $subjectId)
    {
        try {
            // Obtener el término y banco activos
            $activeTerm = Term::where('is_active', true)->first();
            $activeBank = Bank::where('active', true)->first();
            $subject = Subject::find($subjectId);

            if (!$activeTerm || !$activeBank || !$subject) {
                throw new Exception('No hay término, banco activo o asignatura válida.');
            }

            // Validar que existe la carpeta de importación (donde están las carpetas de preguntas)
            $importPath = "import/{$folderName}";
            if (!Storage::exists($importPath)) {
                throw new Exception("No se encontró la carpeta de importación: {$folderName}");
            }

            // Generar ruta de destino
            $subjectSlug = Str::slug($subject->name);
            $destinationPath = "banks/{$activeBank->folder_slug}/{$subjectSlug}";

            // Leer el archivo CSV subido por la plataforma
            $csvContent = file_get_contents($csvFile->getRealPath());
            $rows = str_getcsv($csvContent, "\n");

            $imported = 0;
            $errors = [];
            $questionsToProcess = [];

            // Primera pasada: validar datos y archivos
            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Saltar header

                $columns = str_getcsv($row);

                if (count($columns) < 3) {
                    $errors[] = "Fila " . ($index + 1) . ": Datos insuficientes";
                    continue;
                }

                $codigo = trim($columns[0]);
                $capituloCode = trim($columns[1]);
                $temaCode = trim($columns[2]);
                $dificultad = isset($columns[3]) ? trim($columns[3]) : 'N';
                $tiempoEstimado = isset($columns[4]) ? (int)trim($columns[4]) : 300;
                $comentarios = isset($columns[5]) ? trim($columns[5]) : '';

                // Validar que existe la carpeta de la pregunta en importación
                $questionImportPath = "{$importPath}/{$codigo}";
                if (!Storage::exists($questionImportPath)) {
                    $errors[] = "Fila " . ($index + 1) . " (código: {$codigo}): No se encontró la carpeta de archivos en {$questionImportPath}";
                    continue;
                }

                // Validar que la carpeta tiene archivos
                $questionFiles = Storage::allFiles($questionImportPath);
                if (empty($questionFiles)) {
                    $errors[] = "Fila " . ($index + 1) . " (código: {$codigo}): La carpeta está vacía, no contiene archivos";
                    continue;
                }

                // Validar que no existan archivos en destino
                $questionDestinationPath = "{$destinationPath}/{$codigo}";
                if (Storage::exists($questionDestinationPath)) {
                    $existingFiles = Storage::files($questionDestinationPath);
                    if (!empty($existingFiles)) {
                        $errors[] = "Fila " . ($index + 1) . " (código: {$codigo}): Ya existen archivos en el destino";
                        continue;
                    }
                }

                // Validar que no existe pregunta duplicada en DB
                $existingQuestion = Question::where('code', $codigo)
                    ->where('term_id', $activeTerm->id)
                    ->where('bank_id', $activeBank->id)
                    ->first();

                if ($existingQuestion) {
                    $errors[] = "Fila " . ($index + 1) . " (código: {$codigo}): Ya existe una pregunta con este código";
                    continue;
                }

                // Agregar a la lista de preguntas válidas para procesar
                $questionsToProcess[] = [
                    'rowIndex' => $index + 1,
                    'codigo' => $codigo,
                    'capituloCode' => $capituloCode,
                    'temaCode' => $temaCode,
                    'dificultad' => $dificultad,
                    'tiempoEstimado' => $tiempoEstimado,
                    'comentarios' => $comentarios,
                    'questionImportPath' => $questionImportPath,
                    'questionDestinationPath' => $questionDestinationPath
                ];
            }

            // Si hay errores críticos, no procesar nada
            if (!empty($errors)) {
                return [
                    'success' => false,
                    'imported' => 0,
                    'errors' => $errors,
                    'message' => 'Se encontraron errores de validación. No se procesó ninguna pregunta.'
                ];
            }

            // Segunda pasada: procesar en transacción
            DB::transaction(function () use ($questionsToProcess, $activeTerm, $activeBank, $subjectId, &$imported, &$errors) {
                foreach ($questionsToProcess as $questionData) {
                    try {
                        // Buscar o crear el capítulo por código
                        $chapter = $this->findOrCreateChapterByCode($questionData['capituloCode'], $subjectId);

                        // Buscar o crear el tema por código
                        $topic = $this->findOrCreateTopicByCode($questionData['temaCode'], $chapter->id);

                        // Crear la pregunta en la base de datos
                        $question = Question::create([
                            'code' => $questionData['codigo'],
                            'subject_id' => $subjectId,
                            'chapter_id' => $chapter->id,
                            'topic_id' => $topic->id,
                            'term_id' => $activeTerm->id,
                            'bank_id' => $activeBank->id,
                            'difficulty' => $this->mapDifficulty($questionData['dificultad']),
                            'status' => $this->mapStatus($this->selectedStatus ?? 'draft'),
                            'estimated_time' => $questionData['tiempoEstimado'],
                            'comments' => $questionData['comentarios'],
                            'path' => $questionData['questionDestinationPath']
                        ]);

                        // Copiar archivos de la pregunta
                        $this->copyQuestionFiles($questionData['questionImportPath'], $questionData['questionDestinationPath']);

                        $imported++;

                    } catch (Exception $e) {
                        $errors[] = "Fila {$questionData['rowIndex']} (código: {$questionData['codigo']}): " . $e->getMessage();
                        throw $e; // Re-lanzar para rollback de la transacción
                    }
                }
            });

            return [
                'success' => true,
                'imported' => $imported,
                'errors' => $errors
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'imported' => 0,
                'errors' => [],
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Copiar archivos de pregunta desde import hacia destino final
     */
    private function copyQuestionFiles($sourcePath, $destinationPath)
    {
        // Obtener todos los archivos de la carpeta de origen
        $files = Storage::allFiles($sourcePath);

        if (empty($files)) {
            throw new Exception("No se encontraron archivos en la carpeta de importación");
        }

        // Crear carpeta de destino si no existe
        if (!Storage::exists($destinationPath)) {
            Storage::makeDirectory($destinationPath);
        }

        // Copiar cada archivo
        foreach ($files as $file) {
            $fileName = basename($file);
            $destinationFile = "{$destinationPath}/{$fileName}";

            if (Storage::exists($destinationFile)) {
                throw new Exception("El archivo {$fileName} ya existe en el destino");
            }

            if (!Storage::copy($file, $destinationFile)) {
                throw new Exception("Error al copiar el archivo {$fileName}");
            }
        }
    }

    /**
     * Importar preguntas desde carpeta
     */
    public function importQuestionsFromFolder($folderPath, $subjectId)
    {
        try {
            // Obtener el término y banco activos
            $activeTerm = Term::where('is_active', true)->first();
            $activeBank = Bank::where('active', true)->first();
            $subject = Subject::find($subjectId);

            if (!$activeTerm || !$activeBank || !$subject) {
                throw new Exception('No hay término, banco activo o asignatura válida.');
            }

            // Validar que existe la carpeta de importación
            $importPath = "private/import/banks/{$folderPath}";
            if (!Storage::exists($importPath)) {
                throw new Exception("No se encontró la carpeta de importación: {$folderPath}");
            }

            // Generar ruta de destino
            $subjectSlug = Str::slug($subject->name);
            $destinationPath = "private/banks/{$activeBank->folder_slug}/{$subjectSlug}";

            $imported = 0;
            $errors = [];

            // Obtener todos los archivos CSV en la carpeta
            $files = Storage::files($importPath);
            $csvFiles = array_filter($files, function($file) {
                return Str::endsWith($file, '.csv');
            });

            if (empty($csvFiles)) {
                throw new Exception("No se encontraron archivos CSV en la carpeta de importación");
            }

            // Procesar cada archivo CSV
            foreach ($csvFiles as $csvFile) {
                // Leer el archivo CSV
                $csvContent = Storage::get($csvFile);
                $rows = str_getcsv($csvContent, "\n");

                $questionsToProcess = [];

                // Validar cada fila del CSV
                foreach ($rows as $index => $row) {
                    if ($index === 0) continue; // Saltar header

                    $columns = str_getcsv($row);

                    if (count($columns) < 3) {
                        $errors[] = "Archivo: {$csvFile}, Fila " . ($index + 1) . ": Datos insuficientes";
                        continue;
                    }

                    $codigo = trim($columns[0]);
                    $capituloCode = trim($columns[1]);
                    $temaCode = trim($columns[2]);
                    $dificultad = isset($columns[3]) ? trim($columns[3]) : 'N';
                    $tiempoEstimado = isset($columns[4]) ? (int)trim($columns[4]) : 300;
                    $comentarios = isset($columns[5]) ? trim($columns[5]) : '';

                    // Validar que existe la carpeta de la pregunta en importación
                    $questionImportPath = "{$importPath}/{$codigo}";
                    if (!Storage::exists($questionImportPath)) {
                        $errors[] = "Archivo: {$csvFile}, Fila " . ($index + 1) . " (código: {$codigo}): No se encontró la carpeta de archivos";
                        continue;
                    }

                    // Validar que la carpeta tiene archivos
                    $questionFiles = Storage::allFiles($questionImportPath);
                    if (empty($questionFiles)) {
                        $errors[] = "Archivo: {$csvFile}, Fila " . ($index + 1) . " (código: {$codigo}): La carpeta está vacía, no contiene archivos";
                        continue;
                    }

                    // Validar que no existan archivos en destino
                    $questionDestinationPath = "{$destinationPath}/{$codigo}";
                    if (Storage::exists($questionDestinationPath)) {
                        $existingFiles = Storage::files($questionDestinationPath);
                        if (!empty($existingFiles)) {
                            $errors[] = "Archivo: {$csvFile}, Fila " . ($index + 1) . " (código: {$codigo}): Ya existen archivos en el destino";
                            continue;
                        }
                    }

                    // Validar que no existe pregunta duplicada en DB
                    $existingQuestion = Question::where('code', $codigo)
                        ->where('term_id', $activeTerm->id)
                        ->where('bank_id', $activeBank->id)
                        ->first();

                    if ($existingQuestion) {
                        $errors[] = "Archivo: {$csvFile}, Fila " . ($index + 1) . " (código: {$codigo}): Ya existe una pregunta con este código";
                        continue;
                    }

                    // Agregar a la lista de preguntas válidas para procesar
                    $questionsToProcess[] = [
                        'codigo' => $codigo,
                        'capituloCode' => $capituloCode,
                        'temaCode' => $temaCode,
                        'dificultad' => $dificultad,
                        'tiempoEstimado' => $tiempoEstimado,
                        'comentarios' => $comentarios,
                        'questionImportPath' => $questionImportPath,
                        'questionDestinationPath' => $questionDestinationPath
                    ];
                }

                // Procesar preguntas válidas en transacción
                if (!empty($questionsToProcess)) {
                    DB::transaction(function () use ($questionsToProcess, $activeTerm, $activeBank, $subjectId, &$imported, &$errors) {
                        foreach ($questionsToProcess as $questionData) {
                            try {
                                // Buscar o crear el capítulo por código
                                $chapter = $this->findOrCreateChapterByCode($questionData['capituloCode'], $subjectId);

                                // Buscar o crear el tema por código
                                $topic = $this->findOrCreateTopicByCode($questionData['temaCode'], $chapter->id);

                                // Crear la pregunta en la base de datos
                                $question = Question::create([
                                    'code' => $questionData['codigo'],
                                    'subject_id' => $subjectId,
                                    'chapter_id' => $chapter->id,
                                    'topic_id' => $topic->id,
                                    'term_id' => $activeTerm->id,
                                    'bank_id' => $activeBank->id,
                                    'difficulty' => $this->mapDifficulty($questionData['dificultad']),
                                    'status' => $this->mapStatus($this->selectedStatus ?? 'draft'),
                                    'estimated_time' => $questionData['tiempoEstimado'],
                                    'comments' => $questionData['comentarios'],
                                    'path' => $questionData['questionDestinationPath']
                                ]);

                                // Copiar archivos de la pregunta
                                $this->copyQuestionFiles($questionData['questionImportPath'], $questionData['questionDestinationPath']);

                                $imported++;

                            } catch (Exception $e) {
                                $errors[] = "Archivo: {$csvFile}, Código: {$questionData['codigo']}: " . $e->getMessage();
                                throw $e; // Re-lanzar para rollback de la transacción
                            }
                        }
                    });
                }
            }

            return [
                'success' => true,
                'imported' => $imported,
                'errors' => $errors
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'imported' => 0,
                'errors' => [],
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Buscar o crear un capítulo por código
     */
    private function findOrCreateChapterByCode($code, $subjectId)
    {
        $chapter = Chapter::where('code', $code)
            ->where('subject_id', $subjectId)
            ->first();

        if (!$chapter) {
            // Obtener el siguiente order para el nuevo capítulo
            $maxOrder = Chapter::where('subject_id', $subjectId)->max('order') ?? 0;

            $chapter = Chapter::create([
                'code' => $code, // Usar el código del CSV, no generar uno nuevo
                'name' => 'Capítulo ' . $code,
                'subject_id' => $subjectId,
                'order' => $maxOrder + 1
            ]);
        }

        return $chapter;
    }

    /**
     * Buscar o crear un tema por código
     */
    private function findOrCreateTopicByCode($code, $chapterId)
    {
        $topic = Topic::where('code', $code)
            ->where('chapter_id', $chapterId)
            ->first();

        if (!$topic) {
            // Obtener el siguiente order para el nuevo tema
            $maxOrder = Topic::where('chapter_id', $chapterId)->max('order') ?? 0;

            $topic = Topic::create([
                'code' => $code, // Usar el código del CSV, no generar uno nuevo
                'name' => 'Tema ' . $code,
                'chapter_id' => $chapterId,
                'order' => $maxOrder + 1
            ]);
        }

        return $topic;
    }

    /**
     * Crear una pregunta
     */
    private function createQuestion($data)
    {
        // Verificar que no existe una pregunta con el mismo código en el mismo término y banco
        $existingQuestion = Question::where('code', $data['code'])
            ->where('term_id', $data['term_id'])
            ->where('bank_id', $data['bank_id'])
            ->first();

        if ($existingQuestion) {
            throw new Exception("Ya existe una pregunta con el código {$data['code']}");
        }

        return Question::create($data);
    }

    /**
     * Mapear dificultad del CSV al enum QuestionDifficulty
     */
    private function mapDifficulty($difficulty)
    {
        return QuestionDifficulty::fromString($difficulty);
    }

    /**
     * Mapear estado del CSV al enum QuestionStatus
     */
    private function mapStatus($status)
    {
        $statuses = [
            'draft' => QuestionStatus::DRAFT,
            'borrador' => QuestionStatus::DRAFT,
            'active' => QuestionStatus::APPROVED,
            'activo' => QuestionStatus::APPROVED,
            'approved' => QuestionStatus::APPROVED,
            'aprobada' => QuestionStatus::APPROVED,
            'inactive' => QuestionStatus::ARCHIVED,
            'inactivo' => QuestionStatus::ARCHIVED,
            'archived' => QuestionStatus::ARCHIVED,
            'archivada' => QuestionStatus::ARCHIVED
        ];

        return $statuses[strtolower($status)] ?? QuestionStatus::DRAFT;
    }

    /**
     * Generar ruta para los archivos de la pregunta
     */
    private function generateQuestionPath($term, $subjectId, $questionCode)
    {
        // Obtener el subject para el slug
        $subject = \App\Models\Subject::find($subjectId);
        $subjectSlug = $subject ? Str::slug($subject->name) : 'subject-' . $subjectId;

        return "public/{$term->code}/{$subjectSlug}/p{$questionCode}";
    }
}
