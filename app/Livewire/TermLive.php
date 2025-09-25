<?php

namespace App\Livewire;

use App\Models\Term;
use App\Traits\TermTrait;
use App\Livewire\Forms\TermForm;
use Livewire\Component;
use Livewire\WithPagination;

class TermLive extends Component
{
    use WithPagination, TermTrait;

    public TermForm $form;

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
                'text' => 'Período académico creado correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function edit(Term $term)
    {
        $this->form->setTerm($term);
        $this->isEdit = true;
        $this->isCreate = true; // Para mostrar el formulario
    }

    public function update()
    {
        if ($this->form->update()) {
            $this->hideCreateForm();

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Período académico actualizado correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function delete($termId)
    {
        try {
            $term = $this->findTerm($termId);

            if (!$term) {
                $this->dispatch('swal:error', [
                    'title' => 'Error',
                    'text' => 'Período académico no encontrado.',
                    'icon' => 'error'
                ]);
                return;
            }

            // Check if term has questions, draws, or exams
            $hasRelations = $term->questions()->count() > 0 ||
                           $term->draws()->count() > 0 ||
                           $term->exams()->count() > 0;

            if ($hasRelations) {
                $this->dispatch('swal:error', [
                    'title' => 'No se puede eliminar',
                    'text' => 'Este período académico tiene datos asociados (preguntas, sorteos o exámenes).',
                    'icon' => 'error'
                ]);
                return;
            }

            $this->deleteTerm($term);

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Período académico eliminado correctamente.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No se pudo eliminar el período académico.',
                'icon' => 'error'
            ]);
        }
    }

    public function confirmDelete($termId)
    {
        $this->dispatch('swal:confirm', [
            'title' => '¿Estás seguro?',
            'text' => 'Esta acción no se puede deshacer.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
            'method' => 'delete',
            'params' => $termId
        ]);
    }

    public function toggleActive($termId)
    {
        $term = $this->findTerm($termId);

        if ($term) {
            // Si el período está activo, lo desactivamos
            if ($term->is_active) {
                $this->updateTerm($term, ['is_active' => false]);
                $this->dispatch('swal:success', [
                    'title' => '¡Éxito!',
                    'text' => "Período académico desactivado correctamente.",
                    'icon' => 'success'
                ]);
            } else {
                // Si el período está inactivo, lo activamos (esto desactivará automáticamente los otros)
                $this->updateTerm($term, ['is_active' => true]);
                $this->dispatch('swal:success', [
                    'title' => '¡Éxito!',
                    'text' => "Período académico activado correctamente. Los demás períodos han sido desactivados automáticamente.",
                    'icon' => 'success'
                ]);
            }
        }
    }

    public function render()
    {
        $terms = $this->getTermsPaginated(10);

        return view('livewire.term-live', [
            'terms' => $terms
        ]);
    }
}
