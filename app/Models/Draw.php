<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Draw extends Model
{
    protected $fillable = [
        'code',
        'term_id',
        'subject_id',
        'chapter_id',
        'topic_id',
        'state',
        'created_by',
        'path',
    ];

    /**
     * Get the term that owns the draw
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    /**
     * Get the subject that owns the draw
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the chapter that owns the draw
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Get the topic that owns the draw
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the user who created the draw
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the draw questions for the draw
     */
    public function drawQuestions(): HasMany
    {
        return $this->hasMany(DrawQuestion::class);
    }

    /**
     * Get the exams for the draw
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
}
