<?php

namespace App\Livewire\Professor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ProfessorTrait;
use App\Livewire\Forms\ProfessorForm;
use App\Models\Professors;

class ProfessorLive extends Component
{

    public function render()
    {

        return view('livewire.professor.professor-live');
    }
}
