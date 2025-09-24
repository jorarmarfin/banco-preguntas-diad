<?php

namespace App\Traits;

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
        if (!$topic) return 'Q001';

        $subject = $topic->chapter->subject;
        $lastQuestion = Question::where('topic_id', $topicId)
            ->orderBy('code', 'desc')
            ->first();

        if (!$lastQuestion) {
            return $subject->code . '001';
        }

        // Extraer el número del último código y incrementar
        $lastNumber = intval(substr($lastQuestion->code, -3));
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return $subject->code . $newNumber;
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
     * Generar la ruta de archivo para una pregunta
     */
    public function generateQuestionFilePath($questionId, $termName, $subjectName, $filename)
    {
        // Convertir nombre de asignatura a slug
        $subjectSlug = Str::slug($subjectName);

        // Crear estructura de carpetas: periodo/asignatura-slug/p{id}/
        $folderPath = "questions/{$termName}/{$subjectSlug}/p{$questionId}";

        return "{$folderPath}/{$filename}";
    }

    /**
     * Obtener opciones de estado usando el Enum
     */
    public function getStatusOptions()
    {
        return QuestionStatus::options();
    }
}
