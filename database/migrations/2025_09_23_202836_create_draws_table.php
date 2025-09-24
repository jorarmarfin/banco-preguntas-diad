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
        Schema::create('draws', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('term_id')->nullable()->constrained('terms');
            $table->foreignId('subject_id')->nullable()->constrained('subjects');
            $table->foreignId('chapter_id')->nullable()->nullable()->constrained('chapters');
            $table->foreignId('topic_id')->nullable()->nullable()->constrained('topics');
            $table->string('state'); // ['pending', 'completed', 'canceled']// Consider using enum if supported
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->string('path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draws');
    }
};
