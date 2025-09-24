<?php

namespace App\Livewire;

use App\Models\Topic;
use App\Models\Chapter;
use App\Traits\SubjectTopicsTrait;
use App\Livewire\Forms\TopicForm;
use Livewire\Component;
use Livewire\WithPagination;

class SubjectTopicsLive extends Component
{
    use WithPagination, SubjectTopicsTrait;

    public TopicForm $form;
    public $chapter;
    public $chapterId;

    // Control properties
    public $isCreate = false;
    public $isEdit = false;

    public function mount($chapter_id)
    {
        $this->chapterId = $chapter_id;
        $this->chapter = $this->getChapterById($chapter_id);

        if (!$this->chapter) {
            abort(404, 'Capítulo no encontrado');
        }

        $this->form->chapter_id = $chapter_id;
    }

    public function showCreateForm()
    {
        $this->form->reset();
        $this->form->chapter_id = $this->chapterId;
        $this->form->order = $this->getNextTopicOrder($this->chapterId);
        $this->isEdit = false;
        $this->isCreate = true;
    }

    public function hideCreateForm()
    {
        $this->form->reset();
        $this->form->chapter_id = $this->chapterId;
        $this->isCreate = false;
        $this->isEdit = false;
    }

    public function store()
    {
        if ($this->form->store()) {
            $this->isCreate = false;

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Tema creado correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function edit(Topic $topic)
    {
        $this->form->setTopic($topic);
        $this->isEdit = true;
        $this->isCreate = true; // Para mostrar el formulario
    }

    public function update()
    {
        if ($this->form->update()) {
            $this->hideCreateForm();

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Tema actualizado correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function delete($topicId)
    {
        try {
            $topic = $this->findTopic($topicId);

            if (!$topic) {
                $this->dispatch('swal:error', [
                    'title' => 'Error',
                    'text' => 'Tema no encontrado.',
                    'icon' => 'error'
                ]);
                return;
            }

            // Verificar si el tema tiene relaciones
            if ($this->topicHasRelations($topic)) {
                $this->dispatch('swal:error', [
                    'title' => 'No se puede eliminar',
                    'text' => 'Este tema tiene datos asociados (preguntas).',
                    'icon' => 'error'
                ]);
                return;
            }

            $this->deleteTopic($topic);

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Tema eliminado correctamente.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No se pudo eliminar el tema.',
                'icon' => 'error'
            ]);
        }
    }

    public function confirmDelete($topicId)
    {
        $this->dispatch('swal:confirm', [
            'title' => '¿Estás seguro?',
            'text' => 'Esta acción no se puede deshacer.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
            'method' => 'delete',
            'params' => $topicId
        ]);
    }

    public function render()
    {
        return view('livewire.subject-topics-live', [
            'topics' => $this->getTopicsPaginated($this->chapterId, 50)
        ]);
    }
}
