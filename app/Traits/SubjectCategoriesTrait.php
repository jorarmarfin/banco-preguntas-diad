<?php

namespace App\Traits;

use App\Models\SubjectCategories;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait SubjectCategoriesTrait
{
    /**
     * Get paginated subject categories
     */
    public function getSubjectCategoriesPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return SubjectCategories::query()
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Create a new subject category
     */
    public function createSubjectCategory(array $data): SubjectCategories
    {
        return SubjectCategories::create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
    }

    /**
     * Update an existing subject category
     */
    public function updateSubjectCategory(SubjectCategories $subjectCategory, array $data): bool
    {
        return $subjectCategory->update([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
    }

    /**
     * Delete a subject category
     */
    public function deleteSubjectCategory(SubjectCategories $subjectCategory): bool
    {
        return $subjectCategory->delete();
    }

    /**
     * Find subject category by ID
     */
    public function findSubjectCategory(int $id): ?SubjectCategories
    {
        return SubjectCategories::find($id);
    }

    /**
     * Check if subject category name exists
     */
    public function subjectCategoryNameExists(string $name, ?int $excludeId = null): bool
    {
        $query = SubjectCategories::where('name', $name);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
