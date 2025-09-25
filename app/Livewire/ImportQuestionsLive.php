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

    public function mount()
    {
        // Verificar si hay un banco activo
        $this->hasActiveBank = $this->getActiveBank() !== null;
    }

    public function import()
    {
        // Validar que hay un banco activo antes de proceder
        if (!$this->hasActiveBank) {
            session()->flash('error', 'No hay un banco activo para realizar la importación.');
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
                    session()->flash('warning',
                        "Se importaron {$imported} preguntas correctamente, pero hubo {$errorCount} errores. " .
                        "Errores: " . implode('; ', array_slice($result['errors'], 0, 3)) .
                        ($errorCount > 3 ? "... y " . ($errorCount - 3) . " más." : "")
                    );
                } else {
                    // Importación completamente exitosa
                    session()->flash('success',
                        "¡Importación completada! Se importaron {$imported} preguntas exitosamente."
                    );
                }

                // Limpiar el formulario después de una importación exitosa
                $this->resetForm();

            } else {
                // Error general en la importación
                session()->flash('error',
                    'Error en la importación: ' . $result['message']
                );
            }

        } catch (\Exception $e) {
            session()->flash('error',
                'Error inesperado durante la importación: ' . $e->getMessage()
            );
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
    }

    public function render()
    {
        return view('livewire.import-questions-live', [
            'subjects' => $this->DdlSubjects(),
            'statusOptions' => $this->DdlStatusOptions()
        ]);
    }
}
