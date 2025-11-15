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
        // Add indexes to courses table for frequently queried columns
        Schema::table('courses', function (Blueprint $table) {
            $table->index('instructor_id', 'courses_instructor_id_index');
            $table->index('category_id', 'courses_category_id_index');
            $table->index('status', 'courses_status_index');
            $table->index(['status', 'instructor_id'], 'courses_status_instructor_index');
        });

        // Add indexes to lessons table
        Schema::table('lessons', function (Blueprint $table) {
            $table->index('module_id', 'lessons_module_id_index');
            $table->index('type', 'lessons_type_index');
            $table->index(['module_id', 'order'], 'lessons_module_order_index');
        });

        // Add indexes to speaker_applications table
        Schema::table('speaker_applications', function (Blueprint $table) {
            $table->index('event_id', 'speaker_applications_event_id_index');
            $table->index('status', 'speaker_applications_status_index');
            $table->index('user_id', 'speaker_applications_user_id_index');
            $table->index(['event_id', 'status'], 'speaker_applications_event_status_index');
        });

        // Add indexes to lesson_progress table (if exists)
        if (Schema::hasTable('lesson_progress')) {
            Schema::table('lesson_progress', function (Blueprint $table) {
                $table->index('course_id', 'lesson_progress_course_id_index');
                $table->index('user_id', 'lesson_progress_user_id_index');
                $table->index('lesson_id', 'lesson_progress_lesson_id_index');
                $table->index(['user_id', 'course_id'], 'lesson_progress_user_course_index');
                $table->index(['user_id', 'lesson_id'], 'lesson_progress_user_lesson_index');
            });
        }

        // Add indexes to events table for common queries
        Schema::table('events', function (Blueprint $table) {
            $table->index('creator_id', 'events_creator_id_index');
            $table->index('is_published', 'events_is_published_index');
            $table->index('start_date', 'events_start_date_index');
            $table->index(['is_published', 'start_date'], 'events_published_start_index');
        });

        // Add indexes to course_modules table
        Schema::table('course_modules', function (Blueprint $table) {
            $table->index(['course_id', 'order'], 'course_modules_course_order_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes from courses table
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('courses_instructor_id_index');
            $table->dropIndex('courses_category_id_index');
            $table->dropIndex('courses_status_index');
            $table->dropIndex('courses_status_instructor_index');
        });

        // Drop indexes from lessons table
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropIndex('lessons_module_id_index');
            $table->dropIndex('lessons_type_index');
            $table->dropIndex('lessons_module_order_index');
        });

        // Drop indexes from speaker_applications table
        Schema::table('speaker_applications', function (Blueprint $table) {
            $table->dropIndex('speaker_applications_event_id_index');
            $table->dropIndex('speaker_applications_status_index');
            $table->dropIndex('speaker_applications_user_id_index');
            $table->dropIndex('speaker_applications_event_status_index');
        });

        // Drop indexes from lesson_progress table
        if (Schema::hasTable('lesson_progress')) {
            Schema::table('lesson_progress', function (Blueprint $table) {
                $table->dropIndex('lesson_progress_course_id_index');
                $table->dropIndex('lesson_progress_user_id_index');
                $table->dropIndex('lesson_progress_lesson_id_index');
                $table->dropIndex('lesson_progress_user_course_index');
                $table->dropIndex('lesson_progress_user_lesson_index');
            });
        }

        // Drop indexes from events table
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('events_creator_id_index');
            $table->dropIndex('events_is_published_index');
            $table->dropIndex('events_start_date_index');
            $table->dropIndex('events_published_start_index');
        });

        // Drop indexes from course_modules table
        Schema::table('course_modules', function (Blueprint $table) {
            $table->dropIndex('course_modules_course_order_index');
        });
    }
};
