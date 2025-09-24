<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'code',
        'subject_id',
        'chapter_id',
        'topic_id',
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

    /**
     * Get the subject that owns the question
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the chapter that owns the question
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Get the topic that owns the question
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the term that owns the question
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    /**
     * Get the user who reviewed this question
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the exam questions for this question
     */
    public function examQuestions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class);
    }

    /**
     * Get the draw questions for this question
     */
    public function drawQuestions(): HasMany
    {
        return $this->hasMany(DrawQuestion::class);
    }
}
