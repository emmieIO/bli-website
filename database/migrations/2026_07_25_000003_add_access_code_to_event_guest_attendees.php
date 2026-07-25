<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_guest_attendees', function (Blueprint $table) {
            $table->string('access_code_hash')->nullable()->after('status');
            $table->dateTime('access_code_expires_at')->nullable()->after('access_code_hash');
        });
    }

    public function down(): void
    {
        Schema::table('event_guest_attendees', function (Blueprint $table) {
            $table->dropColumn(['access_code_hash', 'access_code_expires_at']);
        });
    }
};
