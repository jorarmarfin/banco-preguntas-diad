<?php

namespace App\Livewire;

use App\Traits\DdlTrait;
use App\Traits\BankTrait;
use App\Traits\ImportQuestionsTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImportQuestionsLive extends Component
{
    use WithFileUploads, DdlTrait, BankTrait, ImportQuestionsTrait;

    // Propiedades del formulario
    public $folderName = '';
    public $selectedSubject = '';
    public $selectedStatus = 'draft'; // Nuevo campo para status
    public $csvFile;

    // Control de estado
    public $isImporting = false;
    public $hasActiveBank = false;
    public $isValidated = false; // Indica si el dry-run pasó y se puede importar

    public function mount()
    {
        // Verificar si hay un banco activo
        $this->hasActiveBank = $this->getActiveBank() !== null;
    }

    // Invalidar validación cuando cambien inputs relevantes
    public function updatedFolderName()
    {
        $this->isValidated = false;
    }

    public function updatedSelectedSubject()
    {
        $this->isValidated = false;
    }

    public function updatedCsvFile()
    {
        $this->isValidated = false;
    }

    public function import()
    {
        // seguridad: no permitir importar si no se validó primero
        if (!$this->isValidated) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'title' => 'Validación requerida',
                'message' => 'Debe ejecutar la validación antes de importar.'
            ]);
            return;
        }

        // Validar que hay un banco activo antes de proceder
        if (!$this->hasActiveBank) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'No hay un banco activo para realizar la importación.'
            ]);
            return;
        }

        $this->validate([
            'folderName' => 'required|string|min:3',
            'selectedSubject' => 'required',
            'csvFile' => 'required|file|mimes:csv,txt|max:10240', // 10MB máximo
        ]);

        $this->isImporting = true;

        try {
            // Importar preguntas usando el trait
            $result = $this->importQuestionsFromCsv(
                $this->csvFile,
                $this->folderName,
                $this->selectedSubject
            );

            if ($result['success']) {
                $imported = $result['imported'];
                $errorCount = count($result['errors']);

                if ($errorCount > 0) {
                    // Importación parcialmente exitosa
                    $this->dispatch('show-alert', [
                        'type' => 'warning',
                        'title' => 'Importación Parcial',
                        'message' => "Se importaron {$imported} preguntas correctamente, pero hubo {$errorCount} errores.",
                        'details' => implode('<br>', array_slice($result['errors'], 0, 5)) .
                                   ($errorCount > 5 ? "<br>... y " . ($errorCount - 5) . " errores más." : "")
                    ]);
                } else {
                    // Importación completamente exitosa
                    $this->dispatch('show-alert', [
                        'type' => 'success',
                        'title' => '¡Éxito!',
                        'message' => "Se importaron {$imported} preguntas exitosamente."
                    ]);
                }

                // Limpiar el formulario después de una importación exitosa
                $this->resetForm();

            } else {
                // Error general en la importación — mostrar detalles si existen
                $message = $result['message'] ?? 'Error desconocido durante la importación.';
                $details = '';
                if (!empty($result['errors']) && is_array($result['errors'])) {
                    $countErrors = count($result['errors']);
                    $details = implode('<br>', array_slice($result['errors'], 0, 10));
                    if ($countErrors > 10) {
                        $details .= "<br>... y " . ($countErrors - 10) . " errores más.";
                    }
                }

                $payload = [
                    'type' => 'error',
                    'title' => 'Error en la importación',
                    'message' => $message
                ];
                if (!empty($details)) {
                    $payload['details'] = $details;
                }

                $this->dispatch('show-alert', $payload);
            }

        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'title' => 'Error inesperado',
                'message' => 'Error durante la importación: ' . $e->getMessage()
            ]);
        } finally {
            $this->isImporting = false;
        }
    }

    public function validateImport()
    {
        // Validar campos mínimos
        $this->validate([
            'folderName' => 'required|string|min:3',
            'selectedSubject' => 'required',
            'csvFile' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $this->isImporting = true;

        try {
            $result = $this->validateImportCsv(
                $this->csvFile,
                $this->folderName,
                $this->selectedSubject
            );

            if ($result['valid']) {
                $this->dispatch('show-alert', [
                    'type' => 'success',
                    'title' => 'Validación correcta',
                    'message' => "La validación pasó. Se detectaron {$result['count']} preguntas listas para importar."
                ]);
                $this->isValidated = true;
            } else {
                $this->isValidated = false;
                $errors = $result['errors'] ?? [];
                $countErrors = count($errors);
                $details = implode('<br>', array_slice($errors, 0, 50));
                if ($countErrors > 50) {
                    $details .= "<br>... y " . ($countErrors - 50) . " errores más.";
                }

                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'title' => 'Errores de validación',
                    'message' => "Se encontraron {$countErrors} errores durante la validación. No se procesó ninguna pregunta.",
                    'details' => $details
                ]);
            }

        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'title' => 'Error inesperado',
                'message' => 'Error durante la validación: ' . $e->getMessage()
            ]);
        } finally {
            $this->isImporting = false;
        }
    }

    public function resetForm()
    {
        $this->folderName = '';
        $this->selectedSubject = '';
        $this->selectedStatus = 'draft';
        $this->csvFile = null;
        $this->isImporting = false;
        $this->isValidated = false;
    }

    public function confirmImport()
    {
        // Validar campos mínimos antes de mostrar confirmación
        $this->validate([
            'folderName' => 'required|string|min:3',
            'selectedSubject' => 'required',
            'csvFile' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        if (!$this->isValidated) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'title' => 'Validación requerida',
                'message' => 'Debe validar el archivo y la carpeta antes de confirmar la importación.'
            ]);
            return;
        }

        // Obtener datos para mostrar en el modal
        $subjectName = $this->getSubjectNameById($this->selectedSubject) ?? 'Asignatura no encontrada';
        $fileName = $this->csvFile ? $this->csvFile->getClientOriginalName() : 'Sin archivo seleccionado';

        $html = "<p>Va a importar el archivo <strong>{$fileName}</strong></p>";
        $html .= "<p>Carpeta de importación: <code>{$this->folderName}</code></p>";
        $html .= "<p>Asignatura seleccionada: <strong>{$subjectName}</strong></p>";
        $html .= "<p>Estado asignado a las preguntas: <strong>{$this->selectedStatus}</strong></p>";
        $html .= "<p class='mt-2 text-sm text-red-600'>Verifique que la carpeta y la asignatura son correctas. Esta acción copiará archivos y creará registros en la base de datos.</p>";

        $this->dispatch('swal:confirm', [
            'title' => '¿Confirmar importación?',
            'html' => $html,
            'icon' => 'warning',
            'confirmButtonText' => 'Sí, importar',
            'cancelButtonText' => 'Cancelar',
            'method' => 'import',
            'params' => []
        ]);
    }

    public function render()
    {
        return view('livewire.import-questions-live', [
            'subjects' => $this->DdlSubjects(),
            'statusOptions' => $this->DdlStatusOptions()
        ]);
    }
}
