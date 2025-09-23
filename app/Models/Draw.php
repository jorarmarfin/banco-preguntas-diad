<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 *  $table->id();
            $table->string('code')->unique();
            $table->foreignId('term_id')->constrained('terms');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('chapter_id')->nullable()->constrained('chapters');
            $table->foreignId('topic_id')->nullable()->constrained('topics');
            $table->string('state'); // ['pending', 'completed', 'canceled']// Consider using enum if supported
            $table->foreignId('created_by')->constrained('users');
            $table->string('path')->nullable();

            $table->timestamps();
 * */
class Draw extends Model
{
    protected $fillable = [
        'code',
        'term_id',
        'subject_id',
        'chapter_id',
        'topic_id',
        'state',
        'created_by',
        'path',
    ];

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
