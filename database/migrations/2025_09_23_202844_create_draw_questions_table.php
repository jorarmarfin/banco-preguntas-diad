<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * draw_id (FK PK part)
     *
     * question_id (FK PK part)
     *
     * position (int) — orden dentro del sorteo
     *
     * locked (bool default false) — si se “congela” para re-correr el sorteo sin reemplazar esta
     *
     * notes (nullable)
     *
     * (PK compuesta: (draw_id,question_id). Índice por position).
     */
    public function up(): void
    {
        Schema::create('draw_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('draw_id')->constrained()->nullOnDelete()
            $table->foreignId('question_id')->constrained()->nullOnDelete();
            $table->integer('position')->index();
            $table->boolean('locked')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draw_questions');
    }
};
