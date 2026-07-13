<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('event_attendees')) {
            DB::table('event_attendees')
                ->where('status', 'waitlisted')
                ->update(['status' => 'cancelled', 'updated_at' => now()]);
        }

        if (Schema::hasTable('event_guest_attendees')) {
            DB::table('event_guest_attendees')
                ->where('status', 'waitlisted')
                ->update(['status' => 'cancelled', 'updated_at' => now()]);
        }

        if (Schema::hasTable('permissions')) {
            DB::table('permissions')
                ->whereIn('name', ['event-join-waitlist', 'event-manage-waitlist'])
                ->delete();
        }
    }

    public function down(): void
    {
        // Removed queue membership cannot be reconstructed reliably.
    }
};
