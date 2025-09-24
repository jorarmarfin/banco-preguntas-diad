<?php

namespace App\Livewire\Forms;

use App\Models\Subject;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SubjectForm extends Form
{
    public ?Subject $subject;

    #[Validate('required|string|max:10')]
    public $code = '';

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|exists:subject_categories,id')]
    public $subject_category_id = '';

    public function setSubject(Subject $subject)
    {
        $this->subject = $subject;
        $this->code = $subject->code;
        $this->name = $subject->name;
        $this->subject_category_id = $subject->subject_category_id;
    }

    public function store()
    {
        $this->validate();

        // Check if code already exists
        if (Subject::where('code', $this->code)->exists()) {
            $this->addError('code', 'Este código ya existe.');
            return false;
        }

        // Check if name already exists
        if (Subject::where('name', $this->name)->exists()) {
            $this->addError('name', 'Este nombre ya existe.');
            return false;
        }

        Subject::create($this->only(['code', 'name', 'subject_category_id']));

        $this->reset();
        return true;
    }

    public function update()
    {
        $this->validate();

        // Check if code already exists (excluding current record)
        $codeExists = Subject::where('code', $this->code)
            ->where('id', '!=', $this->subject->id)
            ->exists();

        if ($codeExists) {
            $this->addError('code', 'Este código ya existe.');
            return false;
        }

        // Check if name already exists (excluding current record)
        $nameExists = Subject::where('name', $this->name)
            ->where('id', '!=', $this->subject->id)
            ->exists();

        if ($nameExists) {
            $this->addError('name', 'Este nombre ya existe.');
            return false;
        }

        $this->subject->update($this->only(['code', 'name', 'subject_category_id']));

        $this->reset();
        return true;
    }

    public function reset(...$properties)
    {
        parent::reset(...$properties);
        $this->subject = null;
    }
}
