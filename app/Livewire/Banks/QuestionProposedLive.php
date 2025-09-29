<?php

namespace App\Livewire\Banks;

use App\Traits\BankTrait;
use App\Traits\DdlTrait;
use Livewire\Compone
{
    use BankTrait, DdlTrait;

    public $professor_id = '';
    public $quantity = '';
    public $subject_id = '';
    public $hasActiveBank = false;

    public function mount()
    {
        // Verificar si hay un banco activo
        $this->hasActiveBank = $this->getActiveBank() !== null;
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
