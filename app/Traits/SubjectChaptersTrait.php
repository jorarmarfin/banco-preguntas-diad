<?php

namespace App\Traits;

use App\Models\Chapter;
use App\Models\Subject;

trait SubjectChaptersTrait
{
    /**
     * Obtener capítulos paginados por asignatura
     */
    public function getChaptersPaginated($subjectId, $perPage = 50)
    {
        return Chapter::where('subject_id', $subjectId)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->paginate($perPage);
    }

    /**
     * Obtener todos los capítulos de una asignatura
     */
    public function getChaptersBySubject($subjectId)
    {
        return Chapter::where('subject_id', $subjectId)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Buscar un capítulo por ID
     */
    public function findChapter($chapterId)
    {
        return Chapter::find($chapterId);
    }

    /**
     * Crear un nuevo capítulo
     */
    public function createChapter($data)
    {
        return Chapter::create($data);
    }

    /**
     * Actualizar un capítulo
     */
    public function updateChapter(Chapter $chapter, $data)
    {
        return $chapter->update($data);
    }

    /**
     * Eliminar un capítulo
     */
    public function deleteChapter(Chapter $chapter)
    {
        return $chapter->delete();
    }

    /**
     * Obtener la asignatura por ID
     */
    public function getSubjectById($subjectId)
    {
        return Subject::find($subjectId);
    }

    /**
     * Obtener el siguiente número de orden para un capítulo
     */
    public function getNextChapterOrder($subjectId)
    {
        return Chapter::where('subject_id', $subjectId)->max('order') + 1;
    }

    /**
     * Verificar si un capítulo tiene relaciones (temas, preguntas, etc.)
     */
    public function chapterHasRelations(Chapter $chapter)
    {
        return $chapter->topics()->count() > 0 ||
               $chapter->questions()->count() > 0;
    }
}
