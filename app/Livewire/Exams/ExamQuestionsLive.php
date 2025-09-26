<?php

namespace App\Livewire\Exams;

use App\Models\Exam;
use App\Models\Question;
use App\Traits\ExamTrait;
use App\Traits\DdlTrait;
use App\Traits\BankTrait;
use Livewire\Component;

class ExamQuestionsLive extends Component
{
    use ExamTrait, DdlTrait, BankTrait;

    public $examId;
    public $exam;

    // Propiedades para selects dependientes
    public $selectedSubjectId = null;
    public $selectedChapterId = null;
    public $selectedTopicId = null;
    public $showSelectForm = false;

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
    }

    // Métodos para selects dependientes
    public function updatedSelectedSubjectId()
    {
        $this->selectedChapterId = null;
        $this->selectedTopicId = null;
    }

    public function updatedSelectedChapterId()
    {
        $this->selectedTopicId = null;
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
        if (!$this->selectedTopicId) {
            return collect();
        }

        return Question::where('topic_id', $this->selectedTopicId)
            ->where('status', \App\Enums\QuestionStatus::APPROVED->value)
            ->whereNotIn('id', $this->exam->questions()->pluck('question_id'))
            ->with(['subject', 'chapter', 'topic'])
            ->get();
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

    public function render()
    {
        return view('livewire.exams.exam-questions-live');
    }
}
