<?php

namespace App\Livewire\Forms;

use App\Models\SubjectCategories;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SubjectCategoriesForm extends Form
{
    public ?SubjectCategories $subjectCategory;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string|max:500')]
    public $description = '';

    public function setSubjectCategory(SubjectCategories $subjectCategory)
    {
        $this->subjectCategory = $subjectCategory;
        $this->name = $subjectCategory->name;
        $this->description = $subjectCategory->description ?? '';
    }

    public function store()
    {
        $this->validate();

        // Check if name already exists
        if (SubjectCategories::where('name', $this->name)->exists()) {
            $this->addError('name', 'Este nombre ya existe.');
            return false;
        }

        SubjectCategories::create($this->only(['name', 'description']));

        $this->reset();
        return true;
    }

    public function update()
    {
        $this->validate();

        // Check if name already exists (excluding current record)
        $exists = SubjectCategories::where('name', $this->name)
            ->where('id', '!=', $this->subjectCategory->id)
            ->exists();

        if ($exists) {
            $this->addError('name', 'Este nombre ya existe.');
            return false;
        }

        $this->subjectCategory->update($this->only(['name', 'description']));

        $this->reset();
        return true;
    }

    public function reset(...$properties)
    {
        parent::reset(...$properties);
        $this->subjectCategory = null;
    }
}
