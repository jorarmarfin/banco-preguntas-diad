<?php

namespace App\Livewire\Exams;

use App\Models\Exam;
use App\Traits\ExamTrait;
use App\Livewire\Forms\ExamForm;
use Livewire\Component;
use Livewire\WithPagination;

class ExamLive extends Component
{
    use WithPagination, ExamTrait;

    public ExamForm $form;

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
                'text' => 'Examen creado correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function edit(Exam $exam)
    {
        $this->form->setExam($exam);
        $this->isEdit = true;
        $this->isCreate = true;
    }

    public function update()
    {
        if ($this->form->update()) {
            $this->hideCreateForm();

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Examen actualizado correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function delete($examId)
    {
        try {
            // Debug: verificar que el ID llegue correctamente
            if (!$examId) {
                $this->dispatch('swal:error', [
                    'title' => 'Error',
                    'text' => 'ID de examen no válido.',
                    'icon' => 'error'
                ]);
                return;
            }

            $exam = $this->findExam($examId);

            if (!$exam) {
                $this->dispatch('swal:error', [
                    'title' => 'Error',
                    'text' => 'Examen no encontrado.',
                    'icon' => 'error'
                ]);
                return;
            }
            if ($this->examHasRelations($exam)) {
                $this->dispatch('swal:error', [
                    'title' => 'No se puede eliminar',
                    'text' => 'Este examen tiene preguntas asociadas.',
                    'icon' => 'error'
                ]);
                return;
            }

            // Intentar eliminar y verificar el resultado
            $deleted = $this->deleteExam($exam);

            if ($deleted) {
                $this->dispatch('swal:success', [
                    'title' => '¡Éxito!',
                    'text' => 'Examen eliminado correctamente.',
                    'icon' => 'success'
                ]);
            } else {
                $this->dispatch('swal:error', [
                    'title' => 'Error',
                    'text' => 'No se pudo eliminar el examen. Intente nuevamente.',
                    'icon' => 'error'
                ]);
            }

        } catch (\Exception $e) {
            // Mostrar el error específico para debugging
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'Error al eliminar: ' . $e->getMessage(),
                'icon' => 'error'
            ]);

            // Log del error para debugging
            \Log::error('Error al eliminar examen: ' . $e->getMessage(), [
                'exam_id' => $examId,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function confirmDelete($examId)
    {
        $this->dispatch('swal:confirm', [
            'title' => '¿Estás seguro?',
            'text' => 'Esta acción no se puede deshacer.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
            'method' => 'delete',
            'params' => $examId
        ]);
    }

    public function viewQuestions($examId)
    {
        return redirect()->route('exams.show', ['id' => $examId]);
    }

    public function generateCode()
    {
        $this->form->code = $this->generateExamCode();
    }

    public function render()
    {
        return view('livewire.exams.exam-live', [
            'exams' => $this->getExamsPaginated(),
            'activeTerm' => $this->getActiveTerm()
        ]);
    }
}
