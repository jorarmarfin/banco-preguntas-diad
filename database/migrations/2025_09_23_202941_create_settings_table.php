<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * id (PK)
     *
     * scope (ej. system, draw, exam)
     *
     * key
     *
     * value_type (enum: string|number|boolean|json|date)
     *
     * value_string, value_number, value_boolean, value_json, value_date (una de estas según el tipo)
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
