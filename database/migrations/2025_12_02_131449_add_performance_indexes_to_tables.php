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
        // Add indexes to transactions table for payment queries
        Schema::table('transactions', function (Blueprint $table) {
            $table->index('user_id', 'idx_transactions_user_id');
            $table->index('course_id', 'idx_transactions_course_id');
            $table->index('status', 'idx_transactions_status');
            $table->index(['user_id', 'course_id', 'status'], 'idx_transactions_user_course_status');
        });

        // Add indexes to tickets table for search and filtering
        Schema::table('tickets', function (Blueprint $table) {
            $table->index('user_id', 'idx_tickets_user_id');
            $table->index('status', 'idx_tickets_status');
            $table->index('reference_code', 'idx_tickets_reference_code');
        });

        // Add indexes to lesson_progress table for dashboard queries
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->index('user_id', 'idx_lesson_progress_user_id');
            $table->index('course_id', 'idx_lesson_progress_course_id');
            $table->index('lesson_id', 'idx_lesson_progress_lesson_id');
            $table->index(['user_id', 'course_id'], 'idx_lesson_progress_user_course');
            $table->index(['user_id', 'lesson_id'], 'idx_lesson_progress_user_lesson');
            $table->index('is_completed', 'idx_lesson_progress_completed');
        });

        // Add indexes to courses table for filtering and search
        Schema::table('courses', function (Blueprint $table) {
            $table->index('instructor_id', 'idx_courses_instructor_id');
            $table->index('category_id', 'idx_courses_category_id');
            $table->index('status', 'idx_courses_status');
            $table->index('is_free', 'idx_courses_is_free');
        });

        // Add indexes to course_user (enrollments) pivot table
        Schema::table('course_user', function (Blueprint $table) {
            $table->index('user_id', 'idx_course_user_user_id');
            $table->index('course_id', 'idx_course_user_course_id');
            $table->index('created_at', 'idx_course_user_created_at');
        });

        // Add indexes to events table
        Schema::table('events', function (Blueprint $table) {
            $table->index('start_date', 'idx_events_start_date');
            $table->index('end_date', 'idx_events_end_date');
            $table->index('creator_id', 'idx_events_creator_id');
        });

        // Add indexes to instructor_ratings table
        Schema::table('instructor_ratings', function (Blueprint $table) {
            $table->index('instructor_id', 'idx_instructor_ratings_instructor_id');
            $table->index('user_id', 'idx_instructor_ratings_user_id');
            $table->index('created_at', 'idx_instructor_ratings_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes from transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_transactions_user_id');
            $table->dropIndex('idx_transactions_course_id');
            $table->dropIndex('idx_transactions_status');
            $table->dropIndex('idx_transactions_user_course_status');
        });

        // Drop indexes from tickets table
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex('idx_tickets_user_id');
            $table->dropIndex('idx_tickets_status');
            $table->dropIndex('idx_tickets_reference_code');
        });

        // Drop indexes from lesson_progress table
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropIndex('idx_lesson_progress_user_id');
            $table->dropIndex('idx_lesson_progress_course_id');
            $table->dropIndex('idx_lesson_progress_lesson_id');
            $table->dropIndex('idx_lesson_progress_user_course');
            $table->dropIndex('idx_lesson_progress_user_lesson');
            $table->dropIndex('idx_lesson_progress_completed');
        });

        // Drop indexes from courses table
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('idx_courses_instructor_id');
            $table->dropIndex('idx_courses_category_id');
            $table->dropIndex('idx_courses_status');
            $table->dropIndex('idx_courses_is_free');
        });

        // Drop indexes from course_user table
        Schema::table('course_user', function (Blueprint $table) {
            $table->dropIndex('idx_course_user_user_id');
            $table->dropIndex('idx_course_user_course_id');
            $table->dropIndex('idx_course_user_created_at');
        });

        // Drop indexes from events table
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('idx_events_start_date');
            $table->dropIndex('idx_events_end_date');
            $table->dropIndex('idx_events_creator_id');
        });

        // Drop indexes from instructor_ratings table
        Schema::table('instructor_ratings', function (Blueprint $table) {
            $table->dropIndex('idx_instructor_ratings_instructor_id');
            $table->dropIndex('idx_instructor_ratings_user_id');
            $table->dropIndex('idx_instructor_ratings_created_at');
        });
    }
};
