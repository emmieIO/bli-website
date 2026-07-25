<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_days', function (Blueprint $table) {
            $table->dateTime('end_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('event_days', function (Blueprint $table) {
            $table->dateTime('end_at')->nullable(false)->change();
        });
    }
};
