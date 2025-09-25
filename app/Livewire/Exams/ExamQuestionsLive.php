<?php

namespace App\Livewire\Exams;

use App\Models\Exam;
use App\Traits\ExamTrait;
use Livewire\Component;

class ExamQuestionsLive extends Component
{
    use ExamTrait;

    public $examId;
    public $exam;

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

    public function render()
    {
        return view('livewire.exams.exam-questions-live');
    }
}
