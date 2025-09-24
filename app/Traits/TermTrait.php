<?php

namespace App\Traits;

use App\Models\Term;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait TermTrait
{
    /**
     * Get paginated terms
     */
    public function getTermsPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Term::query()
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Create a new term
     */
    public function createTerm(array $data): Term
    {
        // If setting this term as active, deactivate all others
        if ($data['is_active'] ?? false) {
            Term::where('is_active', true)->update(['is_active' => false]);
        }

        return Term::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    /**
     * Update an existing term
     */
    public function updateTerm(Term $term, array $data): bool
    {
        // If setting this term as active, deactivate all others
        if ($data['is_active'] ?? false) {
            Term::where('id', '!=', $term->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        return $term->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    /**
     * Delete a term
     */
    public function deleteTerm(Term $term): bool
    {
        return $term->delete();
    }

    /**
     * Find term by ID
     */
    public function findTerm(int $id): ?Term
    {
        return Term::find($id);
    }

    /**
     * Check if term code exists
     */
    public function termCodeExists(string $code, ?int $excludeId = null): bool
    {
        $query = Term::where('code', $code);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Check if term name exists
     */
    public function termNameExists(string $name, ?int $excludeId = null): bool
    {
        $query = Term::where('name', $name);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
