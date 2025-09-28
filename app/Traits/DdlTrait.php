<?php

namespace App\Traits;

use App\Models\Subject;
use App\Models\SubjectCategories;
use App\Models\Term;
use App\Models\Question;
use App\Enums\QuestionDifficulty;
use App\Enums\QuestionStatus;
use Illuminate\Database\Eloquent\Collection;

trait DdlTrait
{
    /**
     * Get all subject categories for dropdown
     */
    public function DdlSubjectCategories(): Collection
    {
        return SubjectCategories::orderBy('name')->get();
    }

    public function DdlTerms(): Collection
    {
        return Term::orderBy('name')->get();
    }

    /**
     * Get difficulty options for questions
     */
    public function DdlDifficultyOptions(): array
    {
        return QuestionDifficulty::toArray();
    }

    /**
     * Get difficulties for dropdown (alias for consistency)
     */
    public function getDifficulties(): array
    {
        return $this->DdlDifficultyOptions();
    }

    /**
     * Get status options for questions
     */
    public function DdlStatusOptions(): array
    {
        return QuestionStatus::options();
    }

    public function DdlSubjects()
    {
        return Subject::select('id', 'name')->orderBy('name')->pluck('name', 'id');
    }

    /**
     * Get subjects that have approved questions
     */
    public function DdlSubjectsWithQuestions()
    {
        return Subject::whereHas('questions', function ($query) {
            $query->where('status', QuestionStatus::APPROVED->value);
        })->select('id', 'name', 'code')
        ->orderBy('name')
        ->get()
        ->mapWithKeys(function ($subject) {
            return [$subject->id => $subject->name . ' (' . $subject->code . ')'];
        });
    }

    /**
     * Get chapters that have approved questions for a specific subject
     */
    public function DdlChaptersWithQuestions($subjectId)
    {
        if (!$subjectId) return collect();

        return Question::where('subject_id', $subjectId)
            ->where('status', QuestionStatus::APPROVED->value)
            ->with('chapter')
            ->get()
            ->pluck('chapter')
            ->unique('id')
            ->sortBy('name')
            ->mapWithKeys(function ($chapter) {
                return [$chapter->id => $chapter->name];
            });
    }

    /**
     * Get topics that have approved questions for a specific chapter
     */
    public function DdlTopicsWithQuestions($chapterId)
    {
        if (!$chapterId) return collect();

        return Question::where('chapter_id', $chapterId)
            ->where('status', QuestionStatus::APPROVED->value)
            ->with('topic')
            ->get()
            ->pluck('topic')
            ->unique('id')
            ->sortBy('name')
            ->mapWithKeys(function ($topic) {
                return [$topic->id => $topic->name];
            });
    }
    public function DdlBanks()
    {
        return \App\Models\Bank::orderBy('name')->get();
    }

    /**
     * Get all professors for dropdown
     */
    public function DdlProfessors()
    {
        return \App\Models\Professors::where('active', true)
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function ($professor) {
                return [$professor->id => $professor->name . ' (' . $professor->code . ')'];
            });
    }
}
