<?php

namespace App\Livewire;

use App\Models\Question;
use App\Models\Topic;
use App\Traits\SubjectQuestionsTrait;
use App\Traits\DdlTrait;
use App\Traits\ActiveTermTrait;
use App\Traits\BankTrait;
use App\Livewire\Forms\QuestionForm;
use App\Enums\QuestionStatus;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class SubjectQuestionsLive extends Component
{
    use WithPagination, WithFileUploads, SubjectQuestionsTrait, DdlTrait, ActiveTermTrait, BankTrait;

    public QuestionForm $form;
    public $topic;
    public $topicId;
    public $uploadedFiles = [];
    public $existingFiles = []; // Nueva propiedad para archivos existentes

    // Control properties
    public $isCreate = false;
    public $isEdit = false;

    public function mount($topic_id)
    {
        $this->topicId = $topic_id;
        $this->topic = $this->getTopicById($topic_id);

        if (!$this->topic) {
            abort(404, 'Tema no encontrado');
        }

        // Pre-cargar datos relacionados
        $this->form->topic_id = $topic_id;
        $this->form->chapter_id = $this->topic->chapter_id;
        $this->form->subject_id = $this->topic->chapter->subject_id;

        // Asignar período activo automáticamente
        $activeTermId = $this->getActiveTermId();
        if (!$activeTermId) {
            session()->flash('error', 'No hay un período activo configurado.');
        }
        $this->form->term_id = $activeTermId;
    }

    public function showCreateForm()
    {
        // Verificar que hay un período activo
        if (!$this->hasActiveTerm()) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No hay un período activo configurado. Configure un período antes de agregar preguntas.',
                'icon' => 'error'
            ]);
            return;
        }

        // Verificar que hay un banco activo
        if (!$this->hasActiveBank()) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No hay un banco de preguntas activo. Active un banco antes de agregar preguntas.',
                'icon' => 'error'
            ]);
            return;
        }

        $this->form->reset();
        $this->uploadedFiles = [];
        $this->existingFiles = [];
        $this->form->topic_id = $this->topicId;
        $this->form->chapter_id = $this->topic->chapter_id;
        $this->form->subject_id = $this->topic->chapter->subject_id;
        $this->form->term_id = $this->getActiveTermId();
        $this->form->code = $this->getNextQuestionCode($this->topicId);
        $this->isEdit = false;
        $this->isCreate = true;
    }

    public function hideCreateForm()
    {
        $this->form->reset();
        $this->uploadedFiles = [];
        $this->existingFiles = [];
        $this->form->topic_id = $this->topicId;
        $this->form->chapter_id = $this->topic->chapter_id;
        $this->form->subject_id = $this->topic->chapter->subject_id;
        $this->form->term_id = $this->getActiveTermId();
        $this->isCreate = false;
        $this->isEdit = false;
    }

    public function store()
    {
        // Verificar que hay un período activo
        if (!$this->hasActiveTerm()) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No hay un período activo configurado.',
                'icon' => 'error'
            ]);
            return;
        }

        if ($this->form->store()) {
            // Obtener la pregunta recién creada
            $question = Question::latest()->first();

            // Procesar archivos subidos
            if (!empty($this->uploadedFiles)) {
                $this->processUploadedFiles($question);
            }

            $this->isCreate = false;
            $this->uploadedFiles = [];

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Pregunta creada correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function edit(Question $question)
    {
        $this->uploadedFiles = [];
        $this->form->setQuestion($question);

        // Cargar archivos existentes si los hay
        if ($question->path) {
            $this->existingFiles = $this->getQuestionFilesPath($question->path);
        } else {
            $this->existingFiles = [];
        }

        $this->isEdit = true;
        $this->isCreate = true; // Para mostrar el formulario
    }

    public function update()
    {
        if ($this->form->update()) {
            // Procesar archivos subidos si hay alguno
            if (!empty($this->uploadedFiles)) {
                $this->processUploadedFiles($this->form->question);
            }

            $this->hideCreateForm();

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Pregunta actualizada correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    public function delete($questionId)
    {
        try {
            $question = $this->findQuestion($questionId);

            if (!$question) {
                $this->dispatch('swal:error', [
                    'title' => 'Error',
                    'text' => 'Pregunta no encontrada.',
                    'icon' => 'error'
                ]);
                return;
            }

            // Verificar si la pregunta tiene relaciones
            if ($this->questionHasRelations($question)) {
                $this->dispatch('swal:error', [
                    'title' => 'No se puede eliminar',
                    'text' => 'Esta pregunta está asociada a exámenes o sorteos.',
                    'icon' => 'error'
                ]);
                return;
            }

            // Eliminar la carpeta de archivos si existe
            if (!empty($question->path)) {
                $this->deleteQuestionFolder($question->path);
            }

            // Eliminar el registro de la base de datos
            $this->deleteQuestion($question);

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Pregunta y archivos eliminados correctamente.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No se pudo eliminar la pregunta.',
                'icon' => 'error'
            ]);
        }
    }

    public function confirmDelete($questionId)
    {
        $this->dispatch('swal:confirm', [
            'title' => '¿Estás seguro?',
            'text' => 'Esta acción no se puede deshacer.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
            'method' => 'delete',
            'params' => $questionId
        ]);
    }

    public function removeUploadedFile($index)
    {
        unset($this->uploadedFiles[$index]);
        $this->uploadedFiles = array_values($this->uploadedFiles);
    }

    // Eliminar un archivo existente
    public function deleteExistingFile($filePath)
    {
        $fullPath = storage_path('app/' . $filePath);

        if (file_exists($fullPath)) {
            unlink($fullPath);

            // Recargar archivos existentes
            if ($this->form->question && $this->form->question->path) {
                $this->existingFiles = $this->getQuestionFilesPath($this->form->question->path);
            }

            $this->dispatch('swal:success', [
                'title' => '¡Éxito!',
                'text' => 'Archivo eliminado correctamente.',
                'icon' => 'success'
            ]);
        }
    }

    private function processUploadedFiles(Question $question)
    {
        if (empty($this->uploadedFiles)) return;

        $activeTerm = $this->getActiveTerm();
        $subjectName = $this->topic->chapter->subject->name;

        // Generar la ruta de la carpeta usando el código del periodo
        $folderPath = $this->generateQuestionFolderPath(
            $question->code,
            $activeTerm->code, // Usar el código del periodo, no el nombre
            $subjectName
        );

        // Crear el directorio si no existe
        $this->ensureQuestionDirectoryExists($folderPath);

        // Guardar cada archivo en la carpeta de la pregunta usando el disco 'local'
        foreach ($this->uploadedFiles as $file) {
            $originalName = $file->getClientOriginalName();

            // Guardar el archivo en storage/app/ usando el disco 'local'
            $file->storeAs($folderPath, $originalName, 'local');
        }

        // Actualizar el path de la pregunta con la ruta de la carpeta
        $question->update([
            'path' => 'private/' . $folderPath
        ]);
    }

    public function render()
    {
        return view('livewire.subject-questions-live', [
            'questions' => $this->getQuestionsPaginated($this->topicId, 50),
            'difficultyOptions' => $this->DdlDifficultyOptions(),
            'statusOptions' => QuestionStatus::options(),
            'activeBank' => $this->getActiveBank()
        ]);
    }
}
