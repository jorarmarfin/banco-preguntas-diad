<?php

namespace App\Livewire;

use App\Models\SubjectCategories;
use App\Traits\SubjectCategoriesTrait;
use App\Livewire\Forms\SubjectCategoriesForm;
use Livewire\Component;
use Livewire\WithPagination;

class SubjectCategoriesLive extends Component
{
    use WithPagination, SubjectCategoriesTrait;

    public SubjectCategoriesForm $form;

    // Control properties
    public $isCreate = false;
    public $isEdit = false;

    protected $paginationTheme = 'bootstrap';

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
                'text' => 'Categoría de materia creada correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function edit(SubjectCategories $subjectCategory)
    {
        $this->form->setSubjectCategory($subjectCategory);
        $this->isEdit = true;
        $this->isCreate = true; // Para mostrar el formulario
    }

    public function update()
    {
        if ($this->form->update()) {
            $this->hideCreateForm();

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Categoría de materia actualizada correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function delete($subjectCategoryId)
    {
        try {
            $subjectCategory = $this->findSubjectCategory($subjectCategoryId);

            if (!$subjectCategory) {
                $this->dispatch('swal:error', [
                    'title' => 'Error',
                    'text' => 'Categoría de materia no encontrada.',
                    'icon' => 'error'
                ]);
                return;
            }

            // Check if category has subjects
            if ($subjectCategory->subjects()->count() > 0) {
                $this->dispatch('swal:error', [
                    'title' => 'No se puede eliminar',
                    'text' => 'Esta categoría tiene materias asociadas. Elimine las materias primero.',
                    'icon' => 'error'
                ]);
                return;
            }

            $this->deleteSubjectCategory($subjectCategory);

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Categoría de materia eliminada correctamente.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No se pudo eliminar la categoría de materia.',
                'icon' => 'error'
            ]);
        }
    }

    public function confirmDelete($subjectCategoryId)
    {
        $this->dispatch('swal:confirm', [
            'title' => '¿Estás seguro?',
            'text' => 'Esta acción no se puede deshacer.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
            'method' => 'delete',
            'params' => $subjectCategoryId
        ]);
    }

    public function render()
    {
        return view('livewire.subject-categories-live', [
            'subjectCategories' => $this->getSubjectCategoriesPaginated(10)
        ]);
    }
}
