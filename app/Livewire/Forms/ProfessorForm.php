<?php

namespace App\Livewire\Forms;

use App\Models\Professors;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProfessorForm extends Form
{
    public ?Professors $professor;

    #[Validate('nullable|string|max:255')]
    public $code = '';

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|email|max:255')]
    public $email = '';

    #[Validate('nullable|string|max:20')]
    public $phone = '';

    #[Validate('boolean')]
    public $active = true;

    public array $fields = ['code', 'name', 'email', 'phone', 'active'];

    public function setProfessor(Professors $professor)
    {
        $this->professor = $professor;
        $this->code = $professor->code;
        $this->name = $professor->name;
        $this->email = $professor->email;
        $this->phone = $professor->phone;
        $this->active = $professor->active;
    }

    public function store()
    {
        $this->validate();

        // Check if email already exists
        if (Professors::where('email', $this->email)->exists()) {
            $this->addError('email', 'Este email ya está registrado.');
            return false;
        }

        // Check if code already exists (if provided)
        if ($this->code && Professors::where('code', $this->code)->exists()) {
            $this->addError('code', 'Este código ya está registrado.');
            return false;
        }

        Professors::create($this->only(...$this->fields));

        $this->reset();
        return true;
    }

    public function update()
    {
        $this->validate();

        // Check if email already exists (excluding current record)
        $emailExists = Professors::where('email', $this->email)
            ->where('id', '!=', $this->professor->id)
            ->exists();

        if ($emailExists) {
            $this->addError('email', 'Este email ya está registrado.');
            return false;
        }

        // Check if code already exists (excluding current record, if provided)
        if ($this->code) {
            $codeExists = Professors::where('code', $this->code)
                ->where('id', '!=', $this->professor->id)
                ->exists();

            if ($codeExists) {
                $this->addError('code', 'Este código ya está registrado.');
                return false;
            }
        }

        $this->professor->update($this->only(...$this->fields));

        $this->reset();
        return true;
    }

    public function reset(...$properties)
    {
        parent::reset(...$properties);
        $this->professor = null;
    }
}