<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ExamQuestion extends Model
{
    protected $fillable = [
        'exam_id',
        'question_id',
        'position',
    ];

    /**
     * Get the exam that owns the exam question
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the question that owns the exam question
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
