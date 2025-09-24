<?php

namespace App\Traits;

use App\Models\Topic;
use App\Models\Chapter;

trait SubjectTopicsTrait
{
    /**
     * Obtener temas paginados por capítulo
     */
    public function getTopicsPaginated($chapterId, $perPage = 50)
    {
        return Topic::where('chapter_id', $chapterId)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->paginate($perPage);
    }

    /**
     * Obtener todos los temas de un capítulo
     */
    public function getTopicsByChapter($chapterId)
    {
        return Topic::where('chapter_id', $chapterId)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Buscar un tema por ID
     */
    public function findTopic($topicId)
    {
        return Topic::find($topicId);
    }

    /**
     * Crear un nuevo tema
     */
    public function createTopic($data)
    {
        return Topic::create($data);
    }

    /**
     * Actualizar un tema
     */
    public function updateTopic(Topic $topic, $data)
    {
        return $topic->update($data);
    }

    /**
     * Eliminar un tema
     */
    public function deleteTopic(Topic $topic)
    {
        return $topic->delete();
    }

    /**
     * Obtener el capítulo por ID
     */
    public function getChapterById($chapterId)
    {
        return Chapter::find($chapterId);
    }

    /**
     * Obtener el siguiente número de orden para un tema
     */
    public function getNextTopicOrder($chapterId)
    {
        return Topic::where('chapter_id', $chapterId)->max('order') + 1;
    }

    /**
     * Verificar si un tema tiene relaciones (preguntas, etc.)
     */
    public function topicHasRelations(Topic $topic)
    {
        return $topic->questions()->count() > 0;
    }
}
