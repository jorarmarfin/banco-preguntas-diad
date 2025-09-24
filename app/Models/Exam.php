<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    // Relationships
    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function draw()
    {
        return $this->belongsTo(Draw::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
