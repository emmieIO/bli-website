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
        Schema::table('speaker_invites', function (Blueprint $table) {
            $table->text('user_feedback')->nullable();
            $table->text('admin_feedback')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('speaker_invites', function (Blueprint $table) {
            $table->dropColumn(['user_feedback', 'admin_feedback']);
        });
    }
};
