<?php

namespace App\Traits;

use App\Models\Subject;
use App\Models\SubjectCategories;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

trait SubjectTrait
{
    /**
     * Get paginated subjects
     */
    public function getSubjectsPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Subject::query()
            ->with('category')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Create a new subject
     */
    public function createSubject(array $data): Subject
    {
        return Subject::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'subject_category_id' => $data['subject_category_id'],
        ]);
    }

    /**
     * Update an existing subject
     */
    public function updateSubject(Subject $subject, array $data): bool
    {
        return $subject->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'subject_category_id' => $data['subject_category_id'],
        ]);
    }

    /**
     * Delete a subject
     */
    public function deleteSubject(Subject $subject): bool
    {
        return $subject->delete();
    }

    /**
     * Find subject by ID
     */
    public function findSubject(int $id): ?Subject
    {
        return Subject::find($id);
    }

    /**
     * Check if subject code exists
     */
    public function subjectCodeExists(string $code, ?int $excludeId = null): bool
    {
        $query = Subject::where('code', $code);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Check if subject name exists
     */
    public function subjectNameExists(string $name, ?int $excludeId = null): bool
    {
        $query = Subject::where('name', $name);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get all subject categories for dropdown
     */
    public function getAllSubjectCategories(): Collection
    {
        return SubjectCategories::orderBy('name')->get();
    }
}
