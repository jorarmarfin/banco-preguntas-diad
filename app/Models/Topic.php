<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    protected $fillable = ['code', 'name', 'order', 'chapter_id'];

    /**
     * Get the chapter that owns the topic
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Get the questions for the topic
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the draws for the topic
     */
    public function draws(): HasMany
    {
        return $this->hasMany(Draw::class);
    }
}
