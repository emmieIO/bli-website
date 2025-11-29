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
        Schema::table('mentorship_requests', function (Blueprint $table) {
            $table->string('meeting_link')->nullable()->after('ended_at');
            $table->json('meeting_schedule')->nullable()->after('meeting_link');
            $table->timestamp('next_session_at')->nullable()->after('meeting_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mentorship_requests', function (Blueprint $table) {
            $table->dropColumn(['meeting_link', 'meeting_schedule', 'next_session_at']);
        });
    }
};
