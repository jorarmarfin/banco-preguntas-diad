<?php

namespace App\Traits;

use App\Models\Question;
use App\Models\Chapter;
use App\Models\Topic;
use App\Models\Term;
use App\Models\Bank;
use App\Enums\QuestionStatus;
use App\Enums\QuestionDifficulty;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
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

            if (!$activeTerm || !$activeBank) {
                throw new Exception('No hay término o banco activo.');
            }

            // Leer el archivo CSV
            $csvContent = file_get_contents($csvFile->getRealPath());
            $rows = str_getcsv($csvContent, "\n");

            $imported = 0;
            $errors = [];

            // Procesar cada fila del CSV (saltando la primera si es header)
            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Saltar header

                $columns = str_getcsv($row);

                if (count($columns) < 3) {
                    $errors[] = "Fila " . ($index + 1) . ": Datos insuficientes";
                    continue;
                }

                $codigo = trim($columns[0]);
                $capituloCode = trim($columns[1]);  // Código del capítulo
                $temaCode = trim($columns[2]);      // Código del tema
                $dificultad = isset($columns[3]) ? trim($columns[3]) : 'N'; // Por defecto Normal
                $tiempoEstimado = isset($columns[4]) ? (int)trim($columns[4]) : 300;
                $comentarios = isset($columns[5]) ? trim($columns[5]) : '';

                try {
                    // Buscar o crear el capítulo por código
                    $chapter = $this->findOrCreateChapterByCode($capituloCode, $subjectId);

                    // Buscar o crear el tema por código
                    $topic = $this->findOrCreateTopicByCode($temaCode, $chapter->id);

                    // Crear la pregunta usando el status del formulario
                    $question = $this->createQuestion([
                        'code' => $codigo,
                        'subject_id' => $subjectId,
                        'chapter_id' => $chapter->id,
                        'topic_id' => $topic->id,
                        'term_id' => $activeTerm->id,
                        'bank_id' => $activeBank->id,
                        'difficulty' => $this->mapDifficulty($dificultad),
                        'status' => $this->mapStatus($this->selectedStatus ?? 'draft'), // Usar status del formulario
                        'estimated_time' => $tiempoEstimado,
                        'comments' => $comentarios,
                        'path' => $this->generateQuestionPath($activeTerm, $subjectId, $codigo)
                    ]);

                    $imported++;

                } catch (Exception $e) {
                    $errors[] = "Fila " . ($index + 1) . " (código: {$codigo}): " . $e->getMessage();
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
