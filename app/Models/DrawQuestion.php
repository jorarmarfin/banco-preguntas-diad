<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrawQuestion extends Model
{
    protected $fillable = [
        'draw_id',
        'question_id',
        'position',
    ];

    /**
     * Get the draw that owns the draw question
     */
    public function draw(): BelongsTo
    {
        return $this->belongsTo(Draw::class);
    }

    /**
     * Get the question that owns the draw question
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
