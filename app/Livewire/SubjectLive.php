<?php

namespace App\Livewire;

use App\Models\Subject;
use App\Traits\SubjectTrait;
use App\Traits\DdlTrait;
use App\Livewire\Forms\SubjectForm;
use Livewire\Component;
use Livewire\WithPagination;

class SubjectLive extends Component
{
    use WithPagination, SubjectTrait, DdlTrait;

    public SubjectForm $form;

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
                'text' => 'Asignatura creada correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function edit(Subject $subject)
    {
        $this->form->setSubject($subject);
        $this->isEdit = true;
        $this->isCreate = true; // Para mostrar el formulario
    }

    public function update()
    {
        if ($this->form->update()) {
            $this->hideCreateForm();

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Asignatura actualizada correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function delete($subjectId)
    {
        try {
            $subject = $this->findSubject($subjectId);

            if (!$subject) {
                $this->dispatch('swal:error', [
                    'title' => 'Error',
                    'text' => 'Asignatura no encontrada.',
                    'icon' => 'error'
                ]);
                return;
            }

            // Check if subject has chapters, questions, draws, or exams
            $hasRelations = $subject->chapters()->count() > 0 ||
                           $subject->questions()->count() > 0 ||
                           $subject->draws()->count() > 0 ||
                           $subject->exams()->count() > 0;

            if ($hasRelations) {
                $this->dispatch('swal:error', [
                    'title' => 'No se puede eliminar',
                    'text' => 'Esta asignatura tiene datos asociados (capítulos, preguntas, sorteos o exámenes).',
                    'icon' => 'error'
                ]);
                return;
            }

            $this->deleteSubject($subject);

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Asignatura eliminada correctamente.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No se pudo eliminar la asignatura.',
                'icon' => 'error'
            ]);
        }
    }

    public function confirmDelete($subjectId)
    {
        $this->dispatch('swal:confirm', [
            'title' => '¿Estás seguro?',
            'text' => 'Esta acción no se puede deshacer.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
            'method' => 'delete',
            'params' => $subjectId
        ]);
    }

    public function render()
    {
        return view('livewire.subject-live', [
            'subjects' => $this->getSubjectsPaginated(50),
            'categories' => $this->DdlSubjectCategories()
        ]);
    }
}
