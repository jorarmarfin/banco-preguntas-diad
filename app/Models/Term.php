<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Term extends Model
{
    protected $fillable = ['code', 'name', 'is_active'];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Boot the model and add event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // When saving a term, ensure only one can be active
        static::saving(function ($term) {
            if ($term->is_active) {
                // Deactivate all other terms
                static::where('id', '!=', $term->id ?? 0)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }
        });
    }

    /**
     * Get the questions for the term
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the draws for the term
     */
    public function draws(): HasMany
    {
        return $this->hasMany(Draw::class);
    }

    /**
     * Get the exams for the term
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
}
