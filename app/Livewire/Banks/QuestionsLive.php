<?php

namespace App\Livewire\Banks;

use App\Models\Question;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\Topic;
use App\Models\Bank;
use App\Traits\DdlTrait;
use App\Traits\QuestionsTrait;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Enums\QuestionStatus;
use App\Models\Setting;

class QuestionsLive extends Component
{
    use WithPagination, DdlTrait, QuestionsTrait;

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

    // Contador público para que la vista lo use (evita computed property)
    public $archivedCount = 0;


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
        // Actualizamos el contador público al seleccionar banco
        if ($this->selectedBank) {
            $this->archivedCount = $this->drawCount((int)$this->selectedBank);
        } else {
            $this->archivedCount = 0;
        }
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
        $this->archivedCount = 0; // reset contador
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
            'banks' => $this->DdlBanks(),
            'statusOptions' => $this->DdlStatusOptions(),
            'difficultyOptions' => $this->DdlDifficultyOptions(),
        ]);
    }

    public function confirmArchive()
    {
        try {
            if (!$this->selectedBank) {
                throw new \Exception('Seleccione primero un banco para archivar sus preguntas.');
            }

            // Obtener banco y conteo directamente para evitar dependencia en otro método
            $bank = Bank::find((int)$this->selectedBank);
            if (!$bank) {
                throw new \Exception('Banco no encontrado');
            }
            $count = $this->drawCount((int)$this->selectedBank);

            if ($count === 0) {
                $this->dispatch('show-alert', [
                    'type' => 'info',
                    'title' => 'Nada que archivar',
                    'message' => 'No se encontraron preguntas con estado Sorteadas en el banco seleccionado.'
                ]);
                return;
            }

            // Log for debugging: confirmArchive called with bank and count
            \Log::debug('confirmArchive triggered', ['bank_id' => $this->selectedBank, 'bank_name' => $bank->name ?? null, 'count' => $count]);

            $html = "<p>Se encontraron <strong>{$count}</strong> preguntas con estado <strong>Sorteada</strong> en el banco <strong>{$bank->name}</strong>.</p>";
            $html .= "<p>Se copiarán sus archivos a la ruta de archivos archivados configurada en la aplicación.</p>";
            $html .= "<p class='mt-2 text-sm text-red-600'>Esta operación moverá físicamente los archivos y actualizará la ruta ('path') de cada pregunta en la base de datos.</p>";

            $this->dispatch('swal:confirm', [
                'title' => '¿Archivar preguntas físicas?',
                'html' => $html,
                'icon' => 'warning',
                'confirmButtonText' => 'Sí, archivar',
                'cancelButtonText' => 'Cancelar',
                'method' => 'archiveQuestions',
                'params' => []
            ]);

        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'title' => 'Error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Archivar preguntas físicas: mover archivos y actualizar path en DB
     */
    public function archiveQuestions()
    {
        try {
            if (!$this->selectedBank) {
                throw new \Exception('Seleccione primero un banco para archivar sus preguntas.');
            }

            $result = $this->archiveQuestionsForBank((int)$this->selectedBank);

            $moved = $result['moved'] ?? 0;
            $skipped = $result['skipped'] ?? 0;
            $errors = $result['errors'] ?? [];

            $message = "Proceso terminado: {$moved} movidas, {$skipped} omitidas.";
            $details = !empty($errors) ? implode('<br>', array_slice($errors, 0, 50)) : null;

            $payload = [
                'type' => 'success',
                'title' => 'Archivar finalizado',
                'message' => $message,
            ];
            if ($details) $payload['details'] = $details;

            $this->dispatch('show-alert', $payload);

        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'title' => 'Error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
