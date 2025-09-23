<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['code', 'name', 'subject_category_id'];

    public function category()
    {
        return $this->belongsTo(SubjectCategory::class, 'subject_category_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}
