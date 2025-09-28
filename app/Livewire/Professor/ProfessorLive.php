<?php

namespace App\Livewire\Professor;

use App\Models\Professors;
use App\Traits\ProfessorTrait;
use App\Livewire\Forms\ProfessorForm;
use Livewire\Component;
use Livewire\WithPagination;

class ProfessorLive extends Component
{
    use WithPagination, ProfessorTrait;

    public ProfessorForm $form;

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
                'text' => 'Profesor creado correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function edit(Professors $professor)
    {
        $this->form->setProfessor($professor);
        $this->isEdit = true;
        $this->isCreate = true; // Para mostrar el formulario
    }

    public function update()
    {
        if ($this->form->update()) {
            $this->hideCreateForm();

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Profesor actualizado correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function delete($professorId)
    {
        try {
            $professor = $this->findProfessor($professorId);

            if (!$professor) {
                $this->dispatch('swal:error', [
                    'title' => 'Error',
                    'text' => 'Profesor no encontrado.',
                    'icon' => 'error'
                ]);
                return;
            }

            $this->deleteProfessor($professor);

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Profesor eliminado correctamente.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No se pudo eliminar el profesor.',
                'icon' => 'error'
            ]);
        }
    }

    public function confirmDelete($professorId)
    {
        $this->dispatch('swal:confirm', [
            'title' => '¿Estás seguro?',
            'text' => 'Esta acción no se puede deshacer.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
            'method' => 'delete',
            'params' => $professorId
        ]);
    }

    public function render()
    {
        $professors = Professors::orderBy('name')->paginate(10);

        return view('livewire.professor.professor-live', [
            'professors' => $professors
        ]);
    }
}