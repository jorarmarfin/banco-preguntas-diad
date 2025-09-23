<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['code', 'name', 'order', 'chapter_id'];
    public $timestamps = false;

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
