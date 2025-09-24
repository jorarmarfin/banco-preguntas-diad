<?php

namespace App\Traits;

use App\Models\Setting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait SettingTrait
{
    /**
     * Get paginated settings
     */
    public function getSettingsPaginated(int $perPage = 10, string $search = ''): LengthAwarePaginator
    {
        return Setting::query()
            ->when($search, function ($query, $search) {
                return $query->where('key', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%");
            })
            ->orderBy('key')
            ->paginate($perPage);
    }

    /**
     * Create a new setting
     */
    public function createSetting(array $data): Setting
    {
        return Setting::create([
            'key' => $data['key'],
            'value' => $data['value'],
        ]);
    }

    /**
     * Update an existing setting
     */
    public function updateSetting(Setting $setting, array $data): bool
    {
        return $setting->update([
            'key' => $data['key'],
            'value' => $data['value'],
        ]);
    }

    /**
     * Delete a setting
     */
    public function deleteSetting(Setting $setting): bool
    {
        return $setting->delete();
    }

    /**
     * Find setting by ID
     */
    public function findSetting(int $id): ?Setting
    {
        return Setting::find($id);
    }

    /**
     * Check if setting key exists
     */
    public function settingKeyExists(string $key, ?int $excludeId = null): bool
    {
        $query = Setting::where('key', $key);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
