<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * $table->id();
            $table->string('key');
            $table->string('value');
 * */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];
}
