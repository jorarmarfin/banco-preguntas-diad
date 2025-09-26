<?php

namespace App\Livewire\Exams;

use App\Models\Exam;
use App\Models\Question;
use App\Traits\ExamTrait;
use App\Traits\DdlTrait;
use App\Traits\BankTrait;
use App\Traits\ExamQuestionsTrait;
use Livewire\Component;

class ExamQuestionsLive extends Component
{
    use ExamTrait, DdlTrait, BankTrait, ExamQuestionsTrait;

    public $examId;
    public $exam;

    // Propiedades para selects dependientes
    public $selectedSubjectId = null;
    public $selectedChapterId = null;
    public $selectedTopicId = null;
    public $selectedDifficulty = null;
    public $showSelectForm = false;

    // Propiedades para pregunta sorteada
    public $selectedQuestion = null;
    public $showQuestionDetails = false;

    // Propiedades para modo de selección
    public $selectionMode = 'individual'; // 'individual' o 'group'
    public $groupChapters = '';
    public $groupQuantity = 1;

    public function mount($examId)
    {
        $this->examId = $examId;
        $this->loadExam();
    }

    public function loadExam()
    {
        $this->exam = $this->findExam($this->examId);

        if (!$this->exam) {
            session()->flash('error', 'Examen no encontrado.');
            return redirect()->route('exams.index');
        }
    }

    public function toggleSelectForm()
    {
        // Verificar que hay un banco activo
        if (!$this->hasActiveBank()) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No hay un banco de preguntas activo. Active un banco antes de agregar preguntas.',
                'icon' => 'error'
            ]);
            return;
        }

        $this->showSelectForm = true;
        $this->selectedSubjectId = null;
        $this->selectedChapterId = null;
        $this->selectedTopicId = null;
    }

    public function hideSelectForm()
    {
        $this->showSelectForm = false;
        $this->selectedSubjectId = null;
        $this->selectedChapterId = null;
        $this->selectedTopicId = null;
        $this->selectedDifficulty = null;
    }

    // Métodos para selects dependientes
    public function updatedSelectedSubjectId()
    {
        $this->selectedChapterId = null;
        $this->selectedTopicId = null;
        $this->selectedDifficulty = null;
    }

    public function updatedSelectedChapterId()
    {
        $this->selectedTopicId = null;
        $this->selectedDifficulty = null;
    }

    public function updatedSelectedTopicId()
    {
        $this->selectedDifficulty = null;
    }

    public function getChaptersProperty()
    {
        return $this->DdlChaptersWithQuestions($this->selectedSubjectId);
    }

    public function getTopicsProperty()
    {
        return $this->DdlTopicsWithQuestions($this->selectedChapterId);
    }

    public function getSubjectsWithQuestionsProperty()
    {
        return $this->DdlSubjectsWithQuestions();
    }

    public function getAvailableQuestionsProperty()
    {
        return $this->getAvailableQuestions($this->examId, $this->selectedTopicId, $this->selectedDifficulty);
    }

    public function getDifficultiesProperty()
    {
        return $this->getDifficulties();
    }

    public function getAvailableQuestionsCountProperty()
    {
        return $this->countAvailableQuestions($this->examId, $this->selectedTopicId, $this->selectedDifficulty);
    }

    public function getExamQuestionsProperty()
    {
        return $this->getExamQuestions($this->examId);
    }

    public function getAvailableGroupQuestionsCountProperty()
    {
        if ($this->selectionMode !== 'group' || !$this->selectedSubjectId || empty($this->groupChapters)) {
            return 0;
        }

        return $this->countAvailableQuestionsByChapterCodes(
            $this->examId,
            $this->selectedSubjectId,
            $this->groupChapters,
            $this->selectedDifficulty
        );
    }

    // Método para resetear valores cuando cambia el modo de selección
    public function updatedSelectionMode()
    {
        $this->selectedSubjectId = null;
        $this->selectedChapterId = null;
        $this->selectedTopicId = null;
        $this->selectedDifficulty = null;
        $this->groupChapters = '';
        $this->groupQuantity = 1;
    }

    // Método para resetear en modo grupo cuando cambia la asignatura
    public function updatedSelectedSubjectIdForGroup()
    {
        if ($this->selectionMode === 'group') {
            $this->selectedDifficulty = null;
            $this->groupChapters = '';
            $this->groupQuantity = 1;
        }
    }

    public function chooseQuestions()
    {
        if (!$this->selectedTopicId) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'Debe seleccionar un tema.',
                'icon' => 'error'
            ]);
            return;
        }

        // Lógica para mostrar preguntas disponibles - implementar después
        $this->dispatch('swal:info', [
            'title' => 'Información',
            'text' => 'Funcionalidad de selección de preguntas en desarrollo.',
            'icon' => 'info'
        ]);
    }

    public function sortearPregunta()
    {
        if (!$this->selectedTopicId) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'Debe seleccionar un tema.',
                'icon' => 'error'
            ]);
            return;
        }

        $availableQuestions = $this->getAvailableQuestions($this->examId, $this->selectedTopicId, $this->selectedDifficulty);

        if ($availableQuestions->isEmpty()) {
            $this->dispatch('swal:error', [
                'title' => 'Sin preguntas',
                'text' => 'No hay preguntas disponibles con los criterios seleccionados.',
                'icon' => 'error'
            ]);
            return;
        }

        // Sortear una pregunta al azar
        $this->selectedQuestion = $availableQuestions->random();
        $this->showQuestionDetails = true;

        $this->dispatch('swal:success', [
            'title' => '¡Pregunta sorteada!',
            'text' => 'Se ha seleccionado una pregunta al azar.',
            'icon' => 'success'
        ]);
    }

    public function sortearGrupo()
    {
        // Validaciones
        if (!$this->selectedSubjectId) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'Debe seleccionar una asignatura.',
                'icon' => 'error'
            ]);
            return;
        }

        if (empty($this->groupChapters)) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'Debe ingresar los códigos de capítulos.',
                'icon' => 'error'
            ]);
            return;
        }

        if (!$this->groupQuantity || $this->groupQuantity <= 0) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'Debe ingresar una cantidad válida.',
                'icon' => 'error'
            ]);
            return;
        }

        // Verificar que hay suficientes preguntas disponibles
        $availableCount = $this->availableGroupQuestionsCount;
        if ($availableCount == 0) {
            $this->dispatch('swal:error', [
                'title' => 'Sin preguntas',
                'text' => 'No hay preguntas disponibles con los criterios seleccionados.',
                'icon' => 'error'
            ]);
            return;
        }

        if ($this->groupQuantity > $availableCount) {
            $this->dispatch('swal:error', [
                'title' => 'Cantidad excedida',
                'text' => "Solo hay {$availableCount} preguntas disponibles, pero solicitas {$this->groupQuantity}.",
                'icon' => 'error'
            ]);
            return;
        }

        try {
            // Obtener preguntas aleatorias usando el trait
            $randomQuestions = $this->getRandomQuestionsByChapterCodes(
                $this->examId,
                $this->selectedSubjectId,
                $this->groupChapters,
                $this->groupQuantity,
                $this->selectedDifficulty
            );

            if ($randomQuestions->isEmpty()) {
                $this->dispatch('swal:error', [
                    'title' => 'Error',
                    'text' => 'No se pudieron obtener preguntas aleatorias.',
                    'icon' => 'error'
                ]);
                return;
            }

            // Agregar todas las preguntas al examen usando inserción masiva
            $this->addMultipleQuestionsToExam($this->examId, $randomQuestions);

            $this->dispatch('swal:success', [
                'title' => '¡Preguntas agregadas!',
                'text' => "Se han agregado {$randomQuestions->count()} preguntas al examen exitosamente.",
                'icon' => 'success'
            ]);

            // Limpiar formulario
            $this->hideSelectForm();

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No se pudieron agregar las preguntas al examen.',
                'icon' => 'error'
            ]);
        }
    }

    public function elegirPregunta()
    {
        if (!$this->selectedQuestion) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No hay pregunta seleccionada.',
                'icon' => 'error'
            ]);
            return;
        }

        try {
            // Verificar si la pregunta ya está en el examen usando el trait
            if ($this->questionExistsInExam($this->examId, $this->selectedQuestion->id)) {
                $this->dispatch('swal:error', [
                    'title' => 'Pregunta duplicada',
                    'text' => 'Esta pregunta ya está agregada al examen.',
                    'icon' => 'error'
                ]);
                return;
            }

            // Agregar la pregunta al examen usando el trait
            $this->addQuestionToExam($this->examId, $this->selectedQuestion->id);

            $this->dispatch('swal:success', [
                'title' => '¡Pregunta agregada!',
                'text' => 'La pregunta se ha agregado exitosamente al examen.',
                'icon' => 'success'
            ]);

            // Limpiar la selección
            $this->selectedQuestion = null;
            $this->showQuestionDetails = false;
            $this->hideSelectForm();

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No se pudo agregar la pregunta al examen.',
                'icon' => 'error'
            ]);
        }
    }

    public function cancelarSeleccion()
    {
        $this->selectedQuestion = null;
        $this->showQuestionDetails = false;
    }

    public function confirmDeleteQuestion($questionId)
    {
        $this->dispatch('swal:confirm', [
            'title' => '¿Estás seguro?',
            'text' => 'Esta pregunta será eliminada del examen.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
            'method' => 'deleteQuestion',
            'params' => $questionId
        ]);
    }

    public function deleteQuestion($questionId)
    {
        try {
            if ($this->removeQuestionFromExam($this->examId, $questionId)) {
                $this->dispatch('swal:success', [
                    'title' => '¡Pregunta eliminada!',
                    'text' => 'La pregunta se ha eliminado del examen.',
                    'icon' => 'success'
                ]);
            } else {
                $this->dispatch('swal:error', [
                    'title' => 'Error',
                    'text' => 'No se pudo eliminar la pregunta.',
                    'icon' => 'error'
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'Ocurrió un error al eliminar la pregunta.',
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.exams.exam-questions-live');
    }
}
