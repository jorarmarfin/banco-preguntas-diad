<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'code',
        'term_id',
        'subject_id',
        'name',
        'draw_id',
        'version',
        'generated_by',
        'generated_at',
        'status',
        'path',
    ];

    /**
     * Get the term that owns the exam
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    /**
     * Get the subject that owns the exam
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the draw that owns the exam
     */
    public function draw(): BelongsTo
    {
        return $this->belongsTo(Draw::class);
    }

    /**
     * Get the user who generated the exam
     */
    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    /**
     * Get the exam questions for the exam
     */
    public function examQuestions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class);
    }
}
