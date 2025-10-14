<?php

use App\Models\Event;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('theme')->nullable()->after('title');
        });

        Event::query()
            ->whereNull('theme')
            ->orWhere('theme', '')
            ->get()
            ->each(function ($event) {
                $event->theme = "default event theme";
                $event->save();
            });
        Schema::table('events', function (Blueprint $table) {
            $table->string('theme')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['theme']);
        });
    }
};
