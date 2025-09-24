<?php

namespace App\Livewire\Forms;

use App\Models\Topic;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TopicForm extends Form
{
    public ?Topic $topic;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|integer|exists:chapters,id')]
    public $chapter_id = '';

    #[Validate('required|integer|min:1')]
    public $order = 1;

    public function setTopic(Topic $topic)
    {
        $this->topic = $topic;
        $this->name = $topic->name;
        $this->chapter_id = $topic->chapter_id;
        $this->order = $topic->order;
    }

    public function store()
    {
        $this->validate();

        Topic::create($this->only(['name', 'chapter_id', 'order']));

        $this->reset();

        return true;
    }

    public function update()
    {
        $this->validate();

        $this->topic->update($this->only(['name', 'chapter_id', 'order']));

        return true;
    }

    public function reset(...$properties)
    {
        $this->topic = null;
        $this->name = '';
        $this->chapter_id = '';
        $this->order = 1;

        parent::reset(...$properties);
    }
}
