<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'code',
        'subject_id',
        'chapter_id',
        'topic_id',
        'question_category_id',
        'term_id',
        'difficulty',
        'points',
        'latex_body',
        'latex_solution',
        'status',
        'reviewed_by',
        'reviewed_at',
        'estimated_time',
        'comments',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function questionCategory()
    {
        return $this->belongsTo(QuestionCategory::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

}
