<?php

namespace App\Livewire\Forms;

use App\Models\Chapter;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ChapterForm extends Form
{
    public ?Chapter $chapter;

    #[Validate('required|string|max:50')]
    public $code = '';

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|integer|exists:subjects,id')]
    public $subject_id = '';

    #[Validate('required|integer|min:1')]
    public $order = 1;

    public function setChapter(Chapter $chapter)
    {
        $this->chapter = $chapter;
        $this->code = $chapter->code;
        $this->name = $chapter->name;
        $this->subject_id = $chapter->subject_id;
        $this->order = $chapter->order;
    }

    public function store()
    {
        $this->validate();

        Chapter::create($this->only(['code', 'name', 'subject_id', 'order']));

        $this->reset();

        return true;
    }

    public function update()
    {
        $this->validate();

        $this->chapter->update($this->only(['code', 'name', 'subject_id', 'order']));

        return true;
    }

    public function reset(...$properties)
    {
        $this->chapter = null;
        $this->code = '';
        $this->name = '';
        $this->subject_id = '';
        $this->order = 1;

        parent::reset(...$properties);
    }
}
