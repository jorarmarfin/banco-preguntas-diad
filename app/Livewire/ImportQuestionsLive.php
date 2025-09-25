<?php

namespace App\Livewire;

use App\Traits\DdlTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImportQuestionsLive extends Component
{
    use WithFileUploads, DdlTrait;

    // Propiedades del formulario
    public $folderName = '';
    public $selectedSubject = '';
    public $csvFile;

    // Control de estado
    public $isImporting = false;

    public function mount()
    {
        // Inicializar valores por defecto si es necesario
    }

    public function import()
    {
        // Aquí irá la lógica de importación después
        $this->validate([
            'folderName' => 'required|string|min:3',
            'selectedSubject' => 'required',
            'csvFile' => 'required|file|mimes:csv,txt|max:10240', // 10MB máximo
        ]);

        $this->isImporting = true;

        // TODO: Implementar lógica de importación

        $this->isImporting = false;

        session()->flash('success', 'Importación completada exitosamente.');
    }

    public function resetForm()
    {
        $this->folderName = '';
        $this->selectedSubject = '';
        $this->csvFile = null;
        $this->isImporting = false;
    }

    public function render()
    {
        return view('livewire.import-questions-live', [
            'subjects' => $this->DdlSubjects()
        ]);
    }
}
