<?php

namespace App\Traits;

use App\Models\Professors;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait ProfessorTrait
{
    /**
     * Get paginated professors
     */
    public function getProfessorsPaginated(int $perPage = 10, string $search = ''): LengthAwarePaginator
    {
        return Professors::query()
            ->when($search, function ($query, $search) {
                return $query->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Create a new professor
     */
    public function createProfessor(array $data): Professors
    {
        return Professors::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'active' => $data['active'] ?? true,
        ]);
    }

    /**
     * Update an existing professor
     */
    public function updateProfessor(Professors $professor, array $data): bool
    {
        return $professor->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'active' => $data['active'] ?? true,
        ]);
    }

    /**
     * Delete a professor
     */
    public function deleteProfessor(Professors $professor): bool
    {
        return $professor->delete();
    }

    /**
     * Find professor by ID
     */
    public function findProfessor(int $id): ?Professors
    {
        return Professors::find($id);
    }

    /**
     * Check if professor email exists
     */
    public function professorEmailExists(string $email, ?int $excludeId = null): bool
    {
        $query = Professors::where('email', $email);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Check if professor code exists
     */
    public function professorCodeExists(string $code, ?int $excludeId = null): bool
    {
        $query = Professors::where('code', $code);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}