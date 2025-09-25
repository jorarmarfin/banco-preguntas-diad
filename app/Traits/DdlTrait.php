<?php

namespace App\Traits;

use App\Models\Subject;
use App\Models\SubjectCategories;
use App\Models\Term;
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
}
