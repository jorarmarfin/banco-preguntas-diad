<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * $table->id();
 * $table->foreignId('question_id')->constrained()->onDelete('cascade');
 * $table->foreignId('professor_id')->constrained()->nullOnDelete();
 * $table->text('comments')->nullable();
 * $table->date('reviewed_at')->nullable();
 *
 * $table->timestamps();
 */
class QuestionReview extends Model
{
    protected $fillable = [
        'question_id',
        'professor_id',
        'comments',
        'reviewed_at',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function professor(): BelongsTo
    {
        return $this->belongsTo(Professor::class);
    }
}
