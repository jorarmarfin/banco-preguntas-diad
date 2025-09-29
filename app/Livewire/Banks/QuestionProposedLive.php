<?php

namespace App\Livewire\Banks;

use App\Traits\BankTrait;
use App\Traits\DdlTrait;
use App\Traits\QuestionsProposedTrait;
use Livewire\Component;

class QuestionProposedLive extends Component
{
    use BankTrait, DdlTrait, QuestionsProposedTrait;

    public $professor_id = '';
    public $quantity = '';
    public $subject_id = '';
    public $hasActiveBank = false;

    public function mount()
    {
        // Verificar si hay un banco activo
        $this->hasActiveBank = $this->getActiveBank() !== null;
    }

    public function createProposal()
    {
        // Validar campos requeridos usando Livewire validation
        $this->validate([
            'professor_id' => 'required|exists:professors,id',
            'quantity' => 'required|integer|min:1|max:100',
            'subject_id' => 'required|exists:subjects,id'
        ]);

        // Usar el trait para crear la propuesta
        $result = $this->createQuestionProposal($this->professor_id, $this->quantity, $this->subject_id);

        if ($result['success']) {
            // Mostrar mensaje de éxito
            $details = "Carpetas creadas: " . implode(', ', $result['details']['folders_created']) . 
                      "<br>Archivo CSV: " . $result['details']['csv_file'];
            
            $this->dispatch('show-alert', [
                'type' => 'success',
                'title' => '¡Propuesta creada exitosamente!',
                'message' => $result['message'],
                'details' => $details
            ]);

            // Limpiar el formulario
            $this->reset(['professor_id', 'quantity', 'subject_id']);
        } else {
            // Mostrar mensaje de error
            $this->dispatch('show-alert', [
                'type' => 'error',
                'title' => 'Error al crear propuesta',
                'message' => $result['message']
            ]);
        }
    }

    public function render()
    {
        return view('livewire.banks.question-proposed-live', [
            'professors' => $this->DdlProfessors(),
            'subjects' => $this->DdlSubjects(),
            'activeBank' => $this->getActiveBank()
        ]);
    }
}
