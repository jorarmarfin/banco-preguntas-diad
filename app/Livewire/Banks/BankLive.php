<?php

namespace App\Livewire\Banks;

use App\Models\Bank;
use App\Traits\BankTrait;
use App\Livewire\Forms\BankForm;
use Livewire\Component;
use Livewire\WithPagination;

class BankLive extends Component
{
    use WithPagination, BankTrait;

    public BankForm $form;

    // Control properties
    public $isCreate = false;
    public $isEdit = false;

    public function showCreateForm()
    {
        $this->form->reset();
        $this->isEdit = false;
        $this->isCreate = true;
    }

    public function hideCreateForm()
    {
        $this->form->reset();
        $this->isCreate = false;
        $this->isEdit = false;
    }

    public function store()
    {
        if ($this->form->store()) {
            $this->isCreate = false;

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Banco de preguntas creado correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function edit(Bank $bank)
    {
        $this->form->setBank($bank);
        $this->isEdit = true;
        $this->isCreate = true;
    }

    public function update()
    {
        if ($this->form->update()) {
            $this->hideCreateForm();

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Banco de preguntas actualizado correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function delete($bankId)
    {
        try {
            $bank = $this->findBank($bankId);

            if (!$bank) {
                $this->dispatch('swal:error', [
                    'title' => 'Error',
                    'text' => 'Banco de preguntas no encontrado.',
                    'icon' => 'error'
                ]);
                return;
            }

            if ($this->bankHasRelations($bank)) {
                $this->dispatch('swal:error', [
                    'title' => 'No se puede eliminar',
                    'text' => 'Este banco tiene preguntas asociadas.',
                    'icon' => 'error'
                ]);
                return;
            }

            // Intentar eliminar la carpeta del banco
            $folderResult = $this->deleteBankFolder($bank->folder_slug);

            // Eliminar el registro de la base de datos
            $this->deleteBank($bank);

            if ($folderResult['success']) {
                $this->dispatch('swal:success', [
                    'title' => '¡Éxito!',
                    'text' => 'Banco de preguntas y su carpeta eliminados correctamente.',
                    'icon' => 'success'
                ]);
            } else if (isset($folderResult['hasFiles']) && $folderResult['hasFiles']) {
                $this->dispatch('swal:warning', [
                    'title' => 'Banco eliminado',
                    'text' => 'El registro fue eliminado, pero la carpeta contiene archivos y debe ser manipulada manualmente: storage/app/private/' . $bank->folder_slug,
                    'icon' => 'warning'
                ]);
            } else {
                $this->dispatch('swal:success', [
                    'title' => '¡Éxito!',
                    'text' => 'Banco de preguntas eliminado correctamente.',
                    'icon' => 'success'
                ]);
            }

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No se pudo eliminar el banco de preguntas.',
                'icon' => 'error'
            ]);
        }
    }

    public function confirmDelete($bankId)
    {
        $this->dispatch('swal:confirm', [
            'title' => '¿Estás seguro?',
            'text' => 'Esta acción no se puede deshacer.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
            'method' => 'delete',
            'params' => $bankId
        ]);
    }

    public function toggleActive($bankId)
    {
        $bank = $this->findBank($bankId);

        if ($bank) {
            // Si el banco está activo, lo desactivamos
            if ($bank->active) {
                $this->updateBank($bank, ['active' => false]);
                $this->dispatch('swal:success', [
                    'title' => '¡Éxito!',
                    'text' => "Banco desactivado correctamente.",
                    'icon' => 'success'
                ]);
            } else {
                // Si el banco está inactivo, lo activamos (esto desactivará automáticamente los otros)
                $this->updateBank($bank, ['active' => true]);
                $this->dispatch('swal:success', [
                    'title' => '¡Éxito!',
                    'text' => "Banco activado correctamente. Los demás bancos han sido desactivados automáticamente.",
                    'icon' => 'success'
                ]);
            }
        }
    }

    public function render()
    {
        $banks = $this->getBanksPaginated(15);

        // Agregar el conteo de carpetas a cada banco
        $banks->getCollection()->transform(function ($bank) {
            $bank->folders_count = $this->countBankFolders($bank->folder_slug);
            return $bank;
        });

        return view('livewire.banks.bank-live', [
            'banks' => $banks
        ]);
    }
}
