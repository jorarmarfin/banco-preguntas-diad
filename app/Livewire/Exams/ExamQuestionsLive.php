<?php

namespace App\Livewire\Exams;

use App\Traits\ExamTrait;
use App\Traits\DdlTrait;
use App\Traits\BankTrait;
use App\Traits\ExamQuestionsTrait;
use App\Traits\ExportQuestionsTrait;
use Livewire\Component;

class ExamQuestionsLive extends Component
{
    use ExamTrait, DdlTrait, BankTrait, ExamQuestionsTrait, ExportQuestionsTrait;

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

    // Propiedades para preguntas sorteadas en grupo
    public $selectedQuestions = [];
    public $showGroupQuestions = false;

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

        // Usar el método optimizado que obtiene solo 1 pregunta aleatoria desde la DB
        $randomQuestion = $this->getRandomQuestion($this->examId, $this->selectedTopicId, $this->selectedDifficulty);

        if (!$randomQuestion) {
            $this->dispatch('swal:error', [
                'title' => 'Sin preguntas aprobadas',
                'text' => 'No hay preguntas aprobadas disponibles con los criterios seleccionados.',
                'icon' => 'error'
            ]);
            return;
        }

        // Asignar la pregunta sorteada directamente
        $this->selectedQuestion = $randomQuestion;
        $this->showQuestionDetails = true;

        $this->dispatch('swal:success', [
            'title' => '¡Pregunta sorteada!',
            'text' => 'Se ha seleccionado una pregunta aprobada al azar.',
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
                'title' => 'Sin preguntas aprobadas',
                'text' => 'No hay preguntas aprobadas disponibles con los criterios seleccionados.',
                'icon' => 'error'
            ]);
            return;
        }

        if ($this->groupQuantity > $availableCount) {
            $this->dispatch('swal:error', [
                'title' => 'Cantidad excedida',
                'text' => "Solo hay {$availableCount} preguntas aprobadas disponibles, pero solicitas {$this->groupQuantity}.",
                'icon' => 'error'
            ]);
            return;
        }

        try {
            // Obtener preguntas aleatorias usando el trait (solo preguntas aprobadas)
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
                    'text' => 'No se pudieron obtener preguntas aprobadas aleatorias.',
                    'icon' => 'error'
                ]);
                return;
            }

            // Mostrar las preguntas sorteadas para que el usuario decida
            $this->selectedQuestions = $randomQuestions->toArray();
            $this->showGroupQuestions = true;

            $this->dispatch('swal:success', [
                'title' => '¡Preguntas sorteadas!',
                'text' => "Se han sorteado {$randomQuestions->count()} preguntas aprobadas. Revisa y decide cuáles agregar.",
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No se pudieron sortear las preguntas.',
                'icon' => 'error'
            ]);
        }
    }

    public function agregarPreguntaIndividual($questionId)
    {
        try {
            // Verificar si la pregunta ya está en el examen
            if ($this->questionExistsInExam($this->examId, $questionId)) {
                $this->dispatch('swal:error', [
                    'title' => 'Pregunta duplicada',
                    'text' => 'Esta pregunta ya está agregada al examen.',
                    'icon' => 'error'
                ]);
                return;
            }

            // Agregar la pregunta al examen
            $this->addQuestionToExam($this->examId, $questionId);

            // Remover la pregunta de la lista de sorteadas
            $this->selectedQuestions = array_filter($this->selectedQuestions, function($question) use ($questionId) {
                return $question['id'] !== $questionId;
            });

            // Si no quedan preguntas, ocultar la vista
            if (empty($this->selectedQuestions)) {
                $this->showGroupQuestions = false;
                $this->hideSelectForm();
            }

            $this->dispatch('swal:success', [
                'title' => '¡Pregunta agregada!',
                'text' => 'La pregunta se ha agregado al examen.',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No se pudo agregar la pregunta.',
                'icon' => 'error'
            ]);
        }
    }

    public function agregarPregunta()
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
            // Verificar si la pregunta ya está en el examen
            if ($this->questionExistsInExam($this->examId, $this->selectedQuestion->id)) {
                $this->dispatch('swal:error', [
                    'title' => 'Pregunta duplicada',
                    'text' => 'Esta pregunta ya está agregada al examen.',
                    'icon' => 'error'
                ]);
                return;
            }

            // Agregar la pregunta al examen
            $this->addQuestionToExam($this->examId, $this->selectedQuestion->id);

            $this->dispatch('swal:success', [
                'title' => '¡Pregunta agregada!',
                'text' => 'La pregunta se ha agregado al examen correctamente.',
                'icon' => 'success'
            ]);

            // Limpiar selección
            $this->cancelarSeleccion();

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

    public function guardarTodasLasPreguntas()
    {
        if (empty($this->selectedQuestions)) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No hay preguntas para guardar.',
                'icon' => 'error'
            ]);
            return;
        }

        try {
            $questionsToAdd = [];
            $duplicatedCount = 0;

            // Verificar duplicados y preparar lista
            foreach ($this->selectedQuestions as $question) {
                if (!$this->questionExistsInExam($this->examId, $question['id'])) {
                    $questionsToAdd[] = (object)$question;
                } else {
                    $duplicatedCount++;
                }
            }

            if (!empty($questionsToAdd)) {
                // Agregar todas las preguntas válidas
                $this->addMultipleQuestionsToExam($this->examId, collect($questionsToAdd));

                $addedCount = count($questionsToAdd);
                $message = "Se han agregado {$addedCount} preguntas al examen.";

                if ($duplicatedCount > 0) {
                    $message .= " ({$duplicatedCount} ya existían)";
                }

                $this->dispatch('swal:success', [
                    'title' => '¡Preguntas guardadas!',
                    'text' => $message,
                    'icon' => 'success'
                ]);
            } else {
                $this->dispatch('swal:info', [
                    'title' => 'Sin cambios',
                    'text' => 'Todas las preguntas ya estaban en el examen.',
                    'icon' => 'info'
                ]);
            }

            // Limpiar y cerrar
            $this->selectedQuestions = [];
            $this->showGroupQuestions = false;
            $this->hideSelectForm();

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No se pudieron guardar las preguntas.',
                'icon' => 'error'
            ]);
        }
    }

    public function cancelarGrupo()
    {
        $this->selectedQuestions = [];
        $this->showGroupQuestions = false;
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

    public function exportarPreguntas()
    {
        // Verificar que hay preguntas en el examen
        if ($this->examQuestions->count() === 0) {
            $this->dispatch('swal:error', [
                'title' => 'Sin preguntas',
                'text' => 'No hay preguntas en el examen para exportar.',
                'icon' => 'error'
            ]);
            return;
        }

        // Verificar que hay un período activo
        if (!$this->hasActiveTerm()) {
            $this->dispatch('swal:error', [
                'title' => 'Error',
                'text' => 'No hay un período activo configurado. Configure un período activo antes de exportar.',
                'icon' => 'error'
            ]);
            return;
        }

        try {
            // Mostrar mensaje de procesamiento
            $this->dispatch('swal:info', [
                'title' => 'Exportando preguntas...',
                'text' => 'Por favor espere mientras se exportan las preguntas físicas.',
                'icon' => 'info'
            ]);

            // Ejecutar la exportación usando el trait
            $result = $this->exportExamQuestions($this->examId);

            if ($result['success']) {
                $data = $result['data'];
                $message = "Exportación completada:\n";
                $message .= "• {$data['exported']} preguntas exportadas\n";

                if ($data['skipped'] > 0) {
                    $message .= "• {$data['skipped']} preguntas ya existían\n";
                }

                if ($data['errors'] > 0) {
                    $message .= "• {$data['errors']} preguntas con errores\n";
                }

                $message .= "\nRuta de exportación: {$data['exam_path']}";

                // Mostrar detalles por asignatura
                if (!empty($data['subjects'])) {
                    $message .= "\n\nPor asignatura:";
                    foreach ($data['subjects'] as $subject) {
                        $message .= "\n• {$subject['name']} ({$subject['code']}): {$subject['count']} preguntas";
                    }
                }

                $this->dispatch('swal:success', [
                    'title' => '¡Exportación exitosa!',
                    'text' => $message,
                    'icon' => 'success'
                ]);

            } else {
                $this->dispatch('swal:error', [
                    'title' => 'Error en la exportación',
                    'text' => $result['message'],
                    'icon' => 'error'
                ]);
            }

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error inesperado',
                'text' => 'Ocurrió un error durante la exportación: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function confirmCerrarSorteo()
    {
        // Verificar que hay preguntas en el examen
        if ($this->examQuestions->count() === 0) {
            $this->dispatch('swal:error', [
                'title' => 'Sin preguntas',
                'text' => 'No hay preguntas en el examen para cerrar el sorteo.',
                'icon' => 'error'
            ]);
            return;
        }

        $this->dispatch('swal:confirm', [
            'title' => '¿Cerrar sorteo del examen?',
            'text' => "Se archivarán todas las {$this->examQuestions->count()} preguntas del examen. Esta acción NO se puede deshacer.",
            'icon' => 'warning',
            'confirmButtonText' => 'Sí, cerrar sorteo',
            'cancelButtonText' => 'Cancelar',
            'method' => 'cerrarSorteo',
            'params' => []
        ]);
    }

    public function cerrarSorteo()
    {
        try {
            // Obtener todas las preguntas del examen
            $examQuestions = $this->examQuestions;

            if ($examQuestions->count() === 0) {
                $this->dispatch('swal:error', [
                    'title' => 'Sin preguntas',
                    'text' => 'No hay preguntas en el examen para cerrar el sorteo.',
                    'icon' => 'error'
                ]);
                return;
            }

            $archivedCount = 0;
            $alreadyArchivedCount = 0;
            $errorCount = 0;

            // Recorrer cada pregunta y cambiar su estado a archived
            foreach ($examQuestions as $examQuestion) {
                try {
                    $question = $examQuestion->question;

                    // Verificar si ya está archivada
                    if ($question->status === \App\Enums\QuestionStatus::ARCHIVED->value) {
                        $alreadyArchivedCount++;
                        continue;
                    }

                    // Cambiar el estado a archived
                    $question->update([
                        'status' => \App\Enums\QuestionStatus::ARCHIVED->value
                    ]);

                    $archivedCount++;

                } catch (\Exception $e) {
                    $errorCount++;
                    \Log::error("Error archivando pregunta {$examQuestion->question->code}: " . $e->getMessage());
                }
            }

            // Construir mensaje de resultado
            $message = "Cierre de sorteo completado:\n";
            $message .= "• {$archivedCount} preguntas archivadas\n";

            if ($alreadyArchivedCount > 0) {
                $message .= "• {$alreadyArchivedCount} preguntas ya estaban archivadas\n";
            }

            if ($errorCount > 0) {
                $message .= "• {$errorCount} preguntas con errores\n";
            }

            if ($archivedCount > 0) {
                $this->dispatch('swal:success', [
                    'title' => '¡Sorteo cerrado exitosamente!',
                    'text' => $message,
                    'icon' => 'success'
                ]);
            } else if ($alreadyArchivedCount > 0 && $errorCount === 0) {
                $this->dispatch('swal:info', [
                    'title' => 'Sorteo ya cerrado',
                    'text' => 'Todas las preguntas del examen ya estaban archivadas.',
                    'icon' => 'info'
                ]);
            } else {
                $this->dispatch('swal:error', [
                    'title' => 'Error en el cierre',
                    'text' => 'No se pudieron archivar las preguntas. Revise los registros del sistema.',
                    'icon' => 'error'
                ]);
            }

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error inesperado',
                'text' => 'Ocurrió un error durante el cierre del sorteo: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.exams.exam-questions-live');
    }
}
