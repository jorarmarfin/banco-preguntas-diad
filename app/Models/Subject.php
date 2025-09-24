<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = ['code', 'name', 'subject_category_id'];

    /**
     * Get the category that owns the subject
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(SubjectCategories::class, 'subject_category_id');
    }

    /**
     * Get the chapters for the subject
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * Get the questions for the subject
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the draws for the subject
     */
    public function draws(): HasMany
    {
        return $this->hasMany(Draw::class);
    }

    /**
     * Get the exams for the subject
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
}
