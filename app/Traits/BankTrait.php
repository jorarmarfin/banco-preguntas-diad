<?php

namespace App\Traits;

use App\Models\Bank;
use Illuminate\Support\Str;

trait BankTrait
{
    /**
     * Obtener bancos paginados
     */
    public function getBanksPaginated($perPage = 50)
    {
        return Bank::orderBy('name', 'asc')
            ->paginate($perPage);
    }

    /**
     * Obtener todos los bancos
     */
    public function getAllBanks()
    {
        return Bank::orderBy('name', 'asc')->get();
    }

    /**
     * Buscar un banco por ID
     */
    public function findBank($bankId)
    {
        return Bank::find($bankId);
    }

    /**
     * Crear un nuevo banco
     */
    public function createBank($data)
    {
        // Si el banco se está creando como activo, desactivar todos los demás
        if (isset($data['active']) && $data['active']) {
            $this->deactivateAllBanks();
        }

        return Bank::create($data);
    }

    /**
     * Actualizar un banco
     */
    public function updateBank(Bank $bank, $data)
    {
        // Si el banco se está activando, desactivar todos los demás
        if (isset($data['active']) && $data['active'] && !$bank->active) {
            $this->deactivateAllBanks();
        }

        return $bank->update($data);
    }

    /**
     * Eliminar un banco
     */
    public function deleteBank(Bank $bank)
    {
        return $bank->delete();
    }

    /**
     * Desactivar todos los bancos
     */
    public function deactivateAllBanks()
    {
        return Bank::where('active', true)->update(['active' => false]);
    }

    /**
     * Obtener el banco activo
     */
    public function getActiveBank()
    {
        return Bank::where('active', true)->first();
    }

    /**
     * Verificar si hay un banco activo
     */
    public function hasActiveBank()
    {
        return Bank::where('active', true)->exists();
    }

    /**
     * Verificar si un banco tiene relaciones
     */
    public function bankHasRelations(Bank $bank)
    {
        // Agregar verificaciones de relaciones cuando se definan
        return false;
    }

    /**
     * Crear una nueva carpeta de banco
     */
    public function createBankFolder($bankName)
    {
        $folderName = Str::slug($bankName);
        $folderPath = storage_path('app/private/' . $folderName);

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        return $folderName;
    }

    /**
     * Eliminar la carpeta de un banco si está vacía
     */
    public function deleteBankFolder($folderName)
    {
        if (empty($folderName)) {
            return ['success' => false, 'message' => 'Nombre de carpeta vacío'];
        }

        $folderPath = storage_path('app/private/' . $folderName);

        if (!file_exists($folderPath) || !is_dir($folderPath)) {
            return ['success' => true, 'message' => 'La carpeta no existe'];
        }

        // Verificar si la carpeta está vacía
        $files = array_diff(scandir($folderPath), ['.', '..']);

        if (!empty($files)) {
            return [
                'success' => false,
                'message' => 'La carpeta contiene archivos y no puede ser eliminada automáticamente. Debe ser manipulada manualmente.',
                'hasFiles' => true
            ];
        }

        // Si está vacía, eliminar la carpeta
        if (rmdir($folderPath)) {
            return ['success' => true, 'message' => 'Carpeta eliminada correctamente'];
        }

        return ['success' => false, 'message' => 'No se pudo eliminar la carpeta'];
    }

    /**
     * Actualizar el nombre de la carpeta cuando se cambia el nombre del banco
     */
    public function updateBankFolder($oldSlug, $newBankName)
    {
        $newSlug = Str::slug($newBankName);

        if ($oldSlug === $newSlug) {
            return $newSlug; // No hay cambios
        }

        $oldPath = storage_path('app/private/' . $oldSlug);
        $newPath = storage_path('app/private/' . $newSlug);

        // Si la carpeta antigua existe, renombrarla
        if (file_exists($oldPath) && is_dir($oldPath)) {
            if (rename($oldPath, $newPath)) {
                return $newSlug;
            }
        } else {
            // Si no existe la carpeta antigua, crear la nueva
            $this->createBankFolder($newBankName);
            return $newSlug;
        }

        return $oldSlug; // En caso de error, mantener el slug anterior
    }

    /**
     * Contar carpetas dentro de la carpeta de un banco
     */
    public function countBankFolders($folderSlug)
    {
        if (empty($folderSlug)) {
            return 0;
        }

        $folderPath = storage_path('app/private/' . $folderSlug);

        if (!file_exists($folderPath) || !is_dir($folderPath)) {
            return 0;
        }

        $items = scandir($folderPath);
        $folderCount = 0;

        foreach ($items as $item) {
            if ($item !== '.' && $item !== '..') {
                $itemPath = $folderPath . '/' . $item;
                if (is_dir($itemPath)) {
                    $folderCount++;
                }
            }
        }

        return $folderCount;
    }
}
