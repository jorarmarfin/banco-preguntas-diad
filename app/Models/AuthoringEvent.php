<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/*
 *   $table->id();
            $table->string('name');
            $table->foreignId('term_id')->nullable()->constrained('terms')->nullOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->foreignId('created_by')->constrained('users')->nullOnDelete();
            $table->timestamps();
 * */
class AuthoringEvent extends Model
{
    protected $fillable = [
        'name',
        'term_id',
        'start_at',
        'end_at',
        'created_by',
    ];

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
