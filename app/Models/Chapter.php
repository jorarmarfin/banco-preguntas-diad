<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = ['code', 'name', 'subject_id', 'order'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
