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
        Schema::create('question_proposals', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignId('subject_id')->nullable()->constrained('subjects');
            $table->foreignId('chapter_id')->nullable()->nullable()->constrained('chapters');
            $table->foreignId('topic_id')->nullable()->nullable()->constrained('topics');
            $table->foreignId('term_id')->nullable()->nullable()->constrained('terms');
            $table->foreignId('bank_id')->nullable()->nullable()->constrained('banks');
            $table->string('difficulty');// ['easy', 'medium', 'hard']
            $table->text('latex_body')->nullable();
            $table->text('latex_solution')->nullable();
            $table->string('status');// ['draft', 'approved', 'archived']
            $table->integer('estimated_time')->nullable(); // in seconds
            $table->text('comments')->nullable();
            $table->string('path')->nullable();
            //revisado por
            $table->string('reviewed_by')->nullable();
            $table->string('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_proposals');
    }
};
