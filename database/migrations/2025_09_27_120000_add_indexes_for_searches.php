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
        // Indexes to speed up common searches / filters
        Schema::table('questions', function (Blueprint $table) {
            // non-unique searchable fields
            $table->index('code', 'idx_questions_code');
            $table->index('difficulty', 'idx_questions_difficulty');
            $table->index('status', 'idx_questions_status');

            // add indexes on foreign keys often used in filters
            $table->index('subject_id', 'idx_questions_subject_id');
            $table->index('chapter_id', 'idx_questions_chapter_id');
            $table->index('topic_id', 'idx_questions_topic_id');
            $table->index('term_id', 'idx_questions_term_id');
            $table->index('bank_id', 'idx_questions_bank_id');
            $table->index('reviewed_by', 'idx_questions_reviewed_by');
        });

        Schema::table('banks', function (Blueprint $table) {
            $table->index('active', 'idx_banks_active');
            $table->index('folder_slug', 'idx_banks_folder_slug');
        });

        Schema::table('terms', function (Blueprint $table) {
            $table->index('is_active', 'idx_terms_is_active');
        });

        Schema::table('question_proposals', function (Blueprint $table) {
            $table->index('code', 'idx_qp_code');
            $table->index('difficulty', 'idx_qp_difficulty');
            $table->index('status', 'idx_qp_status');

            // foreign keys
            $table->index('subject_id', 'idx_qp_subject_id');
            $table->index('chapter_id', 'idx_qp_chapter_id');
            $table->index('topic_id', 'idx_qp_topic_id');
            $table->index('term_id', 'idx_qp_term_id');
            $table->index('bank_id', 'idx_qp_bank_id');
        });

        Schema::table('authoring_events', function (Blueprint $table) {
            // helpful for filtering events by term / creator / date ranges
            $table->index('term_id', 'idx_ae_term_id');
            $table->index('created_by', 'idx_ae_created_by');
            $table->index('start_at', 'idx_ae_start_at');
            $table->index('end_at', 'idx_ae_end_at');
        });

        // other tables where foreign keys/filters are common
        Schema::table('exam_questions', function (Blueprint $table) {
            $table->index('exam_id', 'idx_eq_exam_id');
            $table->index('question_id', 'idx_eq_question_id');
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->index('subject_id', 'idx_chapters_subject_id');
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->index('chapter_id', 'idx_topics_chapter_id');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->index('subject_category_id', 'idx_subjects_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex('idx_questions_code');
            $table->dropIndex('idx_questions_difficulty');
            $table->dropIndex('idx_questions_status');
            $table->dropIndex('idx_questions_subject_id');
            $table->dropIndex('idx_questions_chapter_id');
            $table->dropIndex('idx_questions_topic_id');
            $table->dropIndex('idx_questions_term_id');
            $table->dropIndex('idx_questions_bank_id');
            $table->dropIndex('idx_questions_reviewed_by');
        });

        Schema::table('banks', function (Blueprint $table) {
            $table->dropIndex('idx_banks_active');
            $table->dropIndex('idx_banks_folder_slug');
        });

        Schema::table('terms', function (Blueprint $table) {
            $table->dropIndex('idx_terms_is_active');
        });

        Schema::table('question_proposals', function (Blueprint $table) {
            $table->dropIndex('idx_qp_code');
            $table->dropIndex('idx_qp_difficulty');
            $table->dropIndex('idx_qp_status');
            $table->dropIndex('idx_qp_subject_id');
            $table->dropIndex('idx_qp_chapter_id');
            $table->dropIndex('idx_qp_topic_id');
            $table->dropIndex('idx_qp_term_id');
            $table->dropIndex('idx_qp_bank_id');
        });

        Schema::table('authoring_events', function (Blueprint $table) {
            $table->dropIndex('idx_ae_term_id');
            $table->dropIndex('idx_ae_created_by');
            $table->dropIndex('idx_ae_start_at');
            $table->dropIndex('idx_ae_end_at');
        });

        Schema::table('exam_questions', function (Blueprint $table) {
            $table->dropIndex('idx_eq_exam_id');
            $table->dropIndex('idx_eq_question_id');
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->dropIndex('idx_chapters_subject_id');
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->dropIndex('idx_topics_chapter_id');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropIndex('idx_subjects_category_id');
        });
    }
};
