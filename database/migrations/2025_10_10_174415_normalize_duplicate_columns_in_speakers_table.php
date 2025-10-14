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
            if (Schema::hasColumn('speakers', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('speakers', 'linkedin')) {
                $table->dropColumn('linkedin');
            }
            if (Schema::hasColumn('speakers', 'website')) {
                $table->dropColumn('website');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('speakers', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('website')->nullable();
        });
    }
};
