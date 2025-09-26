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
     * Add multiple questions to an exam
     */
    public function addMultipleQuestionsToExam($examId, $questions)
    {
        $examQuestions = [];

        foreach ($questions as $question) {
            $examQuestions[] = [
                'exam_id' => $examId,
                'question_id' => $question->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return \App\Models\ExamQuestion::insert($examQuestions);
    }

    /**
     * Get the next order number for a question in an exam
     */
    public function getNextQuestionOrder($examId)
    {
        return \App\Models\ExamQuestion::where('exam_id', $examId)->max('order') + 1;
    }

    /**
     * Get all questions for an exam
     */
    public function getExamQuestions($examId)
    {
        return \App\Models\ExamQuestion::where('exam_id', $examId)
            ->with(['question.subject', 'question.chapter', 'question.topic', 'question.bank'])
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Remove a question from an exam
     */
    public function removeQuestionFromExam($examId, $questionId)
    {
        return \App\Models\ExamQuestion::where('exam_id', $examId)
            ->where('question_id', $questionId)
            ->delete();
    }

    /**
     * Get available questions count for multiple chapters by codes
     */
    public function countAvailableQuestionsByChapterCodes($examId, $subjectId, $chapterCodes, $difficulty = null)
    {
        if (!$subjectId || empty($chapterCodes)) {
            return 0;
        }

        // Parse chapter codes from comma-separated string
        $chapterCodesArray = $this->parseChapterCodes($chapterCodes);
        if (empty($chapterCodesArray)) {
            return 0;
        }

        // Get chapter IDs from codes
        $chapterIds = \App\Models\Chapter::where('subject_id', $subjectId)
            ->whereIn('code', $chapterCodesArray)
            ->pluck('id');

        if ($chapterIds->isEmpty()) {
            return 0;
        }

        // Build query for counting questions
        $query = Question::whereIn('chapter_id', $chapterIds)
            ->where('status', QuestionStatus::APPROVED->value)
            ->whereNotIn('id', function ($subQuery) use ($examId) {
                $subQuery->select('question_id')
                    ->from('exam_questions')
                    ->where('exam_id', $examId);
            });

        // Apply difficulty filter if specified
        if ($difficulty) {
            $query->where('difficulty', $difficulty);
        }

        return $query->count();
    }

    /**
     * Get available questions for multiple chapters by codes
     */
    public function getAvailableQuestionsByChapterCodes($examId, $subjectId, $chapterCodes, $difficulty = null, $limit = null)
    {
        if (!$subjectId || empty($chapterCodes)) {
            return collect();
        }

        // Parse chapter codes from comma-separated string
        $chapterCodesArray = $this->parseChapterCodes($chapterCodes);
        if (empty($chapterCodesArray)) {
            return collect();
        }

        // Get chapter IDs from codes
        $chapterIds = \App\Models\Chapter::where('subject_id', $subjectId)
            ->whereIn('code', $chapterCodesArray)
            ->pluck('id');

        if ($chapterIds->isEmpty()) {
            return collect();
        }

        // Build query for getting questions
        $query = Question::whereIn('chapter_id', $chapterIds)
            ->where('status', QuestionStatus::APPROVED->value)
            ->whereNotIn('id', function ($subQuery) use ($examId) {
                $subQuery->select('question_id')
                    ->from('exam_questions')
                    ->where('exam_id', $examId);
            })
            ->with(['subject', 'chapter', 'topic', 'bank']);

        // Apply difficulty filter if specified
        if ($difficulty) {
            $query->where('difficulty', $difficulty);
        }

        // Apply limit if specified
        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Parse chapter codes from comma-separated string
     */
    private function parseChapterCodes($chapterCodes)
    {
        if (empty($chapterCodes)) {
            return [];
        }

        return array_map('trim', array_filter(explode(',', $chapterCodes)));
    }

    /**
     * Get random questions from multiple chapters by codes
     */
    public function getRandomQuestionsByChapterCodes($examId, $subjectId, $chapterCodes, $quantity, $difficulty = null)
    {
        if (!$subjectId || empty($chapterCodes) || $quantity <= 0) {
            return collect();
        }

        // Parse chapter codes from comma-separated string
        $chapterCodesArray = $this->parseChapterCodes($chapterCodes);
        if (empty($chapterCodesArray)) {
            return collect();
        }

        // Get chapter IDs from codes
        $chapterIds = \App\Models\Chapter::where('subject_id', $subjectId)
            ->whereIn('code', $chapterCodesArray)
            ->pluck('id');

        if ($chapterIds->isEmpty()) {
            return collect();
        }

        // Build query for getting questions
        $query = Question::whereIn('chapter_id', $chapterIds)
            ->where('status', QuestionStatus::APPROVED->value)
            ->whereNotIn('id', function ($subQuery) use ($examId) {
                $subQuery->select('question_id')
                    ->from('exam_questions')
                    ->where('exam_id', $examId);
            })
            ->with(['subject', 'chapter', 'topic', 'bank']);

        // Apply difficulty filter if specified
        if ($difficulty) {
            $query->where('difficulty', $difficulty);
        }

        // Get random questions with the specified quantity
        return $query->inRandomOrder()->limit($quantity)->get();
    }

    /**
     * Get a single random question optimized for large datasets
     */
    public function getRandomQuestion($examId, $topicId, $difficulty = null)
    {
        if (!$topicId) {
            return null;
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

        // Usar inRandomOrder()->first() que es más eficiente para obtener solo 1 registro
        return $query->with(['subject', 'chapter', 'topic', 'bank'])
                    ->inRandomOrder()
                    ->first();
    }

    /**
     * Get multiple random questions optimized for large datasets
     */
    public function getRandomQuestions($examId, $topicId, $quantity, $difficulty = null)
    {
        if (!$topicId || $quantity <= 0) {
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

        // Usar inRandomOrder()->limit() que es más eficiente que traer todo y luego filtrar
        return $query->with(['subject', 'chapter', 'topic', 'bank'])
                    ->inRandomOrder()
                    ->limit($quantity)
                    ->get();
    }
}
