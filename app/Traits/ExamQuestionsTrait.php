<?php

namespace App\Traits;

use App\Models\Question;
use App\Enums\QuestionStatus;

trait ExamQuestionsTrait
{
    /**
     * Get available questions for an exam based on filters
     */
    public function getAvailableQuestions($examId, $topicId, $difficulty = null)
    {
        if (!$topicId) {
            return collect();
        }

        $query = Question::where('topic_id', $topicId)
            ->where('status', QuestionStatus::APPROVED->value)
            ->whereNotIn('id', function ($subQuery) use ($examId) {
                $subQuery->select('question_id')
                    ->from('exam_questions')
                    ->where('exam_id', $examId);
            });

        // Filtrar por dificultad si está seleccionada
        if ($difficulty) {
            $query->where('difficulty', $difficulty);
        }

        return $query->with(['subject', 'chapter', 'topic'])->get();
    }

    /**
     * Count available questions for an exam based on filters
     */
    public function countAvailableQuestions($examId, $topicId, $difficulty = null)
    {
        if (!$topicId) {
            return 0;
        }

        $query = Question::where('topic_id', $topicId)
            ->where('status', QuestionStatus::APPROVED->value)
            ->whereNotIn('id', function ($subQuery) use ($examId) {
                $subQuery->select('question_id')
                    ->from('exam_questions')
                    ->where('exam_id', $examId);
            });

        // Filtrar por dificultad si está seleccionada
        if ($difficulty) {
            $query->where('difficulty', $difficulty);
        }

        return $query->count();
    }

    /**
     * Check if a question already exists in an exam
     */
    public function questionExistsInExam($examId, $questionId)
    {
        return \App\Models\ExamQuestion::where('exam_id', $examId)
            ->where('question_id', $questionId)
            ->exists();
    }

    /**
     * Add a question to an exam
     */
    public function addQuestionToExam($examId, $questionId)
    {
        return \App\Models\ExamQuestion::create([
            'exam_id' => $examId,
            'question_id' => $questionId,
        ]);
    }

    /**
     * Get the next order number for a question in an exam
     */
    public function getNextQuestionOrder($examId)
    {
        return \App\Models\ExamQuestion::where('exam_id', $examId)->max('order') + 1;
    }
}
