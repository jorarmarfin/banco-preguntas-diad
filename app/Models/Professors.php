<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/*
 *  $table->string('code')->nullable();
            $table->string('name')->index();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->boolean('active')->default(true);

 * */
class Professors extends Model
{
    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'active',
    ];

}
