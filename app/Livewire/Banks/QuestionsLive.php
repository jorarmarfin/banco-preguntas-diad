<?php

namespace App\Livewire\Banks;

use App\Models\Question;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\Topic;
use App\Models\Bank;
use App\Traits\DdlTrait;
use Livewire\Component;
use Livewire\WithPagination;

class QuestionsLive extends Component
{
    use WithPagination, DdlTrait;

    // Filtros
    public $search = '';
    public $selectedSubject = '';
    public $selectedChapter = '';
    public $selectedTopic = '';
    public $selectedBank = '';
    public $selectedStatus = '';
    public $selectedDifficulty = '';

    // Para actualizar capítulos y temas dinámicamente
    public $chapters = [];
    public $topics = [];

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->resetFilters();
    }

    public function updatedSelectedSubject()
    {
        $this->selectedChapter = '';
        $this->selectedTopic = '';
        $this->loadChapters();
        $this->topics = [];
        $this->resetPage();
    }

    public function updatedSelectedChapter()
    {
        $this->selectedTopic = '';
        $this->loadTopics();
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedBank()
    {
        $this->resetPage();
    }

    public function updatedSelectedStatus()
    {
        $this->resetPage();
    }

    public function updatedSelectedDifficulty()
    {
        $this->resetPage();
    }

    public function updatedSelectedTopic()
    {
        $this->resetPage();
    }

    private function loadChapters()
    {
        if ($this->selectedSubject) {
            $this->chapters = Chapter::where('subject_id', $this->selectedSubject)
                ->orderBy('order')
                ->get();
        } else {
            $this->chapters = [];
        }
    }

    private function loadTopics()
    {
        if ($this->selectedChapter) {
            $this->topics = Topic::where('chapter_id', $this->selectedChapter)
                ->orderBy('order')
                ->get();
        } else {
            $this->topics = [];
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->selectedSubject = '';
        $this->selectedChapter = '';
        $this->selectedTopic = '';
        $this->selectedBank = '';
        $this->selectedStatus = '';
        $this->selectedDifficulty = '';
        $this->chapters = [];
        $this->topics = [];
        $this->resetPage();
    }

    public function getQuestionsProperty()
    {
        return Question::query()
            ->with(['subject', 'chapter', 'topic', 'bank'])
            ->when($this->search, function ($query) {
                $query->where('code', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedSubject, function ($query) {
                $query->where('subject_id', $this->selectedSubject);
            })
            ->when($this->selectedChapter, function ($query) {
                $query->where('chapter_id', $this->selectedChapter);
            })
            ->when($this->selectedTopic, function ($query) {
                $query->where('topic_id', $this->selectedTopic);
            })
            ->when($this->selectedBank, function ($query) {
                $query->where('bank_id', $this->selectedBank);
            })
            ->when($this->selectedStatus, function ($query) {
                $query->where('status', $this->selectedStatus);
            })
            ->when($this->selectedDifficulty, function ($query) {
                $query->where('difficulty', $this->selectedDifficulty);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);
    }

    public function render()
    {
        return view('livewire.banks.questions-live', [
            'questions' => $this->questions,
            'subjects' => $this->DdlSubjects(),
            'banks' => Bank::orderBy('name')->get(),
            'statusOptions' => $this->DdlStatusOptions(),
            'difficultyOptions' => $this->DdlDifficultyOptions(),
        ]);
    }
}
