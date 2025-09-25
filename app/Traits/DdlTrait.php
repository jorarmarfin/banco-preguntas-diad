<?php

namespace App\Traits;

use App\Models\Subject;
use App\Models\SubjectCategories;
use App\Models\Term;
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
        return [
            'easy' => 'Fácil',
            'medium' => 'Medio',
            'hard' => 'Difícil'
        ];
    }
    public function DdlSubjects()
    {
        return Subject::select('id', 'name')->orderBy('name')->pluck('name', 'id');
    }
}
