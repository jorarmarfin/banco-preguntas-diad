<?php

namespace App\Livewire\Forms;

use App\Models\Term;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TermForm extends Form
{
    public ?Term $term;

    #[Validate('required|string|max:10')]
    public $code = '';

    #[Validate('required|string|max:255')]
    public $name = '';

    public $is_active = true;

    public function setTerm(Term $term)
    {
        $this->term = $term;
        $this->code = $term->code;
        $this->name = $term->name;
        $this->is_active = (bool) $term->is_active;
    }

    public function store()
    {
        $this->validate();

        // Check if code already exists
        if (Term::where('code', $this->code)->exists()) {
            $this->addError('code', 'Este código ya existe.');
            return false;
        }

        // Check if name already exists
        if (Term::where('name', $this->name)->exists()) {
            $this->addError('name', 'Este nombre ya existe.');
            return false;
        }

        // If setting this term as active, deactivate all others first
        if ($this->is_active) {
            Term::where('is_active', true)->update(['is_active' => false]);
        }

        Term::create([
            'code' => $this->code,
            'name' => $this->name,
            'is_active' => (bool) $this->is_active
        ]);

        $this->reset();
        return true;
    }

    public function update()
    {
        $this->validate();

        // Check if code already exists (excluding current record)
        $codeExists = Term::where('code', $this->code)
            ->where('id', '!=', $this->term->id)
            ->exists();

        if ($codeExists) {
            $this->addError('code', 'Este código ya existe.');
            return false;
        }

        // Check if name already exists (excluding current record)
        $nameExists = Term::where('name', $this->name)
            ->where('id', '!=', $this->term->id)
            ->exists();

        if ($nameExists) {
            $this->addError('name', 'Este nombre ya existe.');
            return false;
        }

        // If setting this term as active, deactivate all others first
        if ($this->is_active) {
            Term::where('id', '!=', $this->term->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $this->term->update([
            'code' => $this->code,
            'name' => $this->name,
            'is_active' => (bool) $this->is_active
        ]);

        $this->reset();
        return true;
    }

    public function reset(...$properties)
    {
        parent::reset(...$properties);
        $this->term = null;
        $this->is_active = true;
    }
}
