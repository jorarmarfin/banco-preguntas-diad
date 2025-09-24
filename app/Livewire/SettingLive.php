<?php

namespace App\Livewire;

use App\Models\Setting;
use App\Traits\SettingTrait;
use App\Livewire\Forms\SettingForm;
use Livewire\Component;
use Livewire\WithPagination;

class SettingLive extends Component
{
    use WithPagination, SettingTrait;

    public SettingForm $form;

    // Control properties
    public $isCreate = false;
    public $isEdit = false;

    // Quitamos el paginationTheme para que use el por defecto de Tailwind

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
                'text' => 'Configuración creada correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function edit(Setting $setting)
    {
        $this->form->setSetting($setting);
        $this->isEdit = true;
        $this->isCreate = true; // Para mostrar el formulario
    }

    public function update()
    {
        if ($this->form->update()) {
            $this->hideCreateForm();

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Configuración actualizada correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function delete($settingId)
    {
        try {
            $setting = $this->findSetting($settingId);

            if (!$setting) {
                $this->dispatch('swal:error', [
                    'title' => 'Error',
                    'text' => 'Configuración no encontrada.',
                    'icon' => 'error'
                ]);
                return;
            }

            $this->deleteSetting($setting);

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Configuración eliminada correctamente.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No se pudo eliminar la configuración.',
                'icon' => 'error'
            ]);
        }
    }

    public function confirmDelete($settingId)
    {
        $this->dispatch('swal:confirm', [
            'title' => '¿Estás seguro?',
            'text' => 'Esta acción no se puede deshacer.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
            'method' => 'delete',
            'params' => $settingId
        ]);
    }

    public function render()
    {
        $settings = Setting::orderBy('key')->paginate(10);

        return view('livewire.setting-live', [
            'settings' => $settings
        ]);
    }
}
