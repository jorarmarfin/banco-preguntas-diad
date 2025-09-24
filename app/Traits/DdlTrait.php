<?php

namespace App\Traits;

use App\Models\SubjectCategories;
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
}
