<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('speakers', function (Blueprint $table) {
            if (Schema::hasColumn('speakers', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('speakers', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('speakers', 'phone')) {
                $table->dropColumn('phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('speakers', function (Blueprint $table) {
            if (!Schema::hasColumn('speakers', 'name')) {
                $table->string('name')->nullable();
            }
            if (!Schema::hasColumn('speakers', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('speakers', 'phone')) {
                $table->string('phone')->nullable();
            }
        });
    }
};
