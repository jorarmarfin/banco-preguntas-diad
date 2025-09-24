<?php

namespace App\Livewire\Forms;

use App\Enums\QuestionStatus;
use App\Models\Question;
use Livewire\Attributes\Validate;
use Livewire\Form;

class QuestionForm extends Form
{
    public ?Question $question;

    #[Validate('required|string|max:255')]
    public $code = '';

    #[Validate('required|integer|exists:topics,id')]
    public $topic_id = '';

    #[Validate('required|integer|exists:subjects,id')]
    public $subject_id = '';

    #[Validate('required|integer|exists:chapters,id')]
    public $chapter_id = '';

    public $term_id = ''; // Se asignará automáticamente

    #[Validate('required|in:easy,medium,hard')]
    public $difficulty = 'medium';

    #[Validate('required')]
    public $status = 'draft';

    #[Validate('nullable|integer|min:30|max:7200')]
    public $estimated_time = 300;

    #[Validate('nullable|string|max:500')]
    public $comments = '';

    #[Validate('nullable|string|max:255')]
    public $path = '';

    public function rules()
    {
        return [
            'code' => 'required|string|max:255|unique:questions,code,' . ($this->question->id ?? 'NULL'),
            'topic_id' => 'required|integer|exists:topics,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'chapter_id' => 'required|integer|exists:chapters,id',
            'term_id' => 'required|integer|exists:terms,id',
            'difficulty' => 'required|in:facil,medio,dificil',
            'status' => 'required|in:' . implode(',', array_column(QuestionStatus::cases(), 'value')),
            'estimated_time' => 'nullable|integer|min:30|max:7200',
            'comments' => 'nullable|string|max:500',
            'path' => 'nullable|string|max:255',
        ];
    }

    public function setQuestion(Question $question)
    {
        $this->question = $question;

        $this->code = $question->code;
        $this->topic_id = $question->topic_id;
        $this->subject_id = $question->subject_id;
        $this->chapter_id = $question->chapter_id;
        $this->term_id = $question->term_id;
        $this->difficulty = $question->difficulty;
        $this->status = $question->status;
        $this->estimated_time = $question->estimated_time;
        $this->comments = $question->comments;
        $this->path = $question->path;
    }

    public function store()
    {
        $this->validate();

        Question::create($this->only([
            'code', 'topic_id', 'subject_id', 'chapter_id', 'term_id',
            'difficulty', 'status', 'estimated_time', 'comments', 'path'
        ]));

        $this->reset();

        return true;
    }

    public function update()
    {
        $this->validate();

        $this->question->update($this->only([
            'code', 'topic_id', 'subject_id', 'chapter_id', 'term_id',
            'difficulty', 'status', 'estimated_time', 'comments', 'path'
        ]));

        return true;
    }

    public function reset(...$properties)
    {
        $this->question = null;
        $this->code = '';
        $this->topic_id = '';
        $this->subject_id = '';
        $this->chapter_id = '';
        $this->term_id = '';
        $this->difficulty = 'medium';
        $this->status = QuestionStatus::DRAFT->value;
        $this->estimated_time = 300;
        $this->comments = '';
        $this->path = '';

        parent::reset(...$properties);
    }
}
