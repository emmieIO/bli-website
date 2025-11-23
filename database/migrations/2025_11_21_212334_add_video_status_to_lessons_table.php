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
        Schema::table('lessons', function (Blueprint $table) {
            $table->enum('video_status', ['pending', 'uploading', 'processing', 'ready', 'failed'])
                ->default('pending')
                ->after('vimeo_id');
            $table->text('video_error')->nullable()->after('video_status');
            $table->timestamp('video_uploaded_at')->nullable()->after('video_error');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['video_status', 'video_error', 'video_uploaded_at']);
        });
    }
};
