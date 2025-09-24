<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->integer('position')->default(0);
            $table->timestamps();
 * */
class ExamQuestion extends Model
{
    protected $fillable = [
        'exam_id',
        'question_id',
        'position',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
