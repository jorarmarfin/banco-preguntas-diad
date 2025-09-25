<?php

namespace App\Traits;

use App\Models\Bank;
use App\Models\Question;
use App\Models\Topic;
use App\Enums\QuestionStatus;
use Illuminate\Support\Str;

trait SubjectQuestionsTrait
{
    /**
     * Obtener preguntas paginadas por tema
     */
    public function getQuestionsPaginated($topicId, $perPage = 50)
    {
        return Question::where('topic_id', $topicId)
            ->orderBy('code', 'asc')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Obtener todas las preguntas de un tema
     */
    public function getQuestionsByTopic($topicId)
    {
        return Question::where('topic_id', $topicId)
            ->orderBy('code', 'asc')
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Buscar una pregunta por ID
     */
    public function findQuestion($questionId)
    {
        return Question::find($questionId);
    }

    /**
     * Crear una nueva pregunta
     */
    public function createQuestion($data)
    {
        return Question::create($data);
    }

    /**
     * Actualizar una pregunta
     */
    public function updateQuestion(Question $question, $data)
    {
        return $question->update($data);
    }

    /**
     * Eliminar una pregunta
     */
    public function deleteQuestion(Question $question)
    {
        return $question->delete();
    }

    /**
     * Obtener el tema por ID
     */
    public function getTopicById($topicId)
    {
        return Topic::find($topicId);
    }

    /**
     * Generar el siguiente código de pregunta para un tema
     */
    public function getNextQuestionCode($topicId)
    {
        $topic = $this->getTopicById($topicId);
        if (!$topic) return 'p1';

        // Buscar la última pregunta del tema para obtener el número secuencial
        $lastQuestion = Question::where('topic_id', $topicId)
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastQuestion) {
            return 'p1';
        }

        // Extraer el número del código (p1 -> 1, p2 -> 2, etc.)
        $lastNumber = intval(str_replace('p', '', $lastQuestion->code));
        $newNumber = $lastNumber + 1;

        return 'p' . $newNumber;
    }

    /**
     * Verificar si una pregunta tiene relaciones (exámenes, sorteos, etc.)
     */
    public function questionHasRelations(Question $question)
    {
        return $question->examQuestions()->count() > 0 ||
               $question->drawQuestions()->count() > 0;
    }

    /**
     * Generar la ruta de carpeta para una pregunta (sin el archivo)
     */
    public function generateQuestionFolderPath($questionId, $termCode, $subjectName)
    {
        // Convertir nombre de asignatura a slug
        $subjectSlug = Str::slug($subjectName);
        $activeBank = Bank::where('active', true)->first()->folder_slug;

        // Crear estructura de carpetas: codigo_periodo/asignatura-slug/p{id}/
        return "{$activeBank}/{$subjectSlug}/p{$questionId}";
    }

    /**
     * Obtener la ruta completa de archivos para una pregunta
     */
    public function getQuestionFilesPath($questionFolderPath)
    {
        $fullPath = storage_path('app/' . $questionFolderPath);

        if (!file_exists($fullPath)) {
            return [];
        }

        $files = [];
        $fileList = scandir($fullPath);

        foreach ($fileList as $file) {
            if ($file !== '.' && $file !== '..') {
                $files[] = [
                    'name' => $file,
                    'path' => $questionFolderPath . '/' . $file,
                    'url' => '#', // Los archivos privados no tienen URL pública directa
                    'size' => filesize($fullPath . '/' . $file)
                ];
            }
        }

        return $files;
    }

    /**
     * Crear directorio para la pregunta si no existe
     */
    public function ensureQuestionDirectoryExists($folderPath)
    {
        $fullPath = storage_path('app/private/' . $folderPath);

        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        return $fullPath;
    }

    /**
     * Obtener opciones de estado usando el Enum
     */
    public function getStatusOptions()
    {
        return QuestionStatus::options();
    }

    /**
     * Eliminar la carpeta de archivos de una pregunta
     */
    public function deleteQuestionFolder($questionFolderPath)
    {
        if (empty($questionFolderPath)) {
            return false;
        }

        $fullPath = storage_path('app/' . $questionFolderPath);

        if (file_exists($fullPath) && is_dir($fullPath)) {
            // Eliminar todos los archivos dentro de la carpeta
            $files = scandir($fullPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $filePath = $fullPath . '/' . $file;
                    if (is_file($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            // Eliminar la carpeta vacía
            rmdir($fullPath);
            return true;
        }

        return false;
    }
}
