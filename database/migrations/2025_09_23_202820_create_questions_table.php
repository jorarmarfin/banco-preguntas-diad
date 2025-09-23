<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('chapter_id')->nullable()->constrained('chapters');
            $table->foreignId('topic_id')->nullable()->constrained('topics');
            $table->foreignId('question_category_id')->constrained('question_categories');
            $table->foreignId('term_id')->nullable()->constrained('terms');
            $table->string('difficulty');// ['easy', 'medium', 'hard']
            $table->text('latex_body');
            $table->text('latex_solution')->nullable();
            $table->string('status');// ['draft', 'approved', 'archived']
            //quien reviso, cuabndo reviso, tiempo estimado,
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->string('reviewed_at')->nullable();
            $table->integer('estimated_time')->nullable(); // in seconds
            $table->text('comments')->nullable();
            $table->string('path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
