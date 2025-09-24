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
     * code (uniq)
     *
     * term_id (FK → terms)
     *
     * subject_id (FK → subjects)
     *
     * name (ej. “Parcial 1 – Grupo A”)
     *
     * draw_id (FK → draws, nullable si combinas varios sorteos)
     *
     * version (int, ej. A=1, B=2)
     *
     * generated_by (FK → users)
     *
     * generated_at
     *
     * status (enum: draft|finalized|published|archived)
     *
     * output_path (para carpeta/artefactos generados)
     */
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('term_id')->nullable()->constrained('terms');
            $table->foreignId('subject_id')->nullable()->constrained('subjects');
            $table->string('name');
            $table->foreignId('draw_id')->nullable()->nullable()->constrained('draws');
            $table->integer('version')->default(1);
            $table->foreignId('generated_by')->nullable()->constrained('users');
            $table->timestamp('generated_at')->useCurrent();
            $table->string('status')->default('draft');//['draft', 'finalized', 'published', 'archived'];
            $table->string('path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
