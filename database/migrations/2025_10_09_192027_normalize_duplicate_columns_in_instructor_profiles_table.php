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
        Schema::table('instructor_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('instructor_profiles', 'headline')) {
                $table->dropColumn(['headline']);
            }
            if (Schema::hasColumn('instructor_profiles', 'linkedin_url')) {
                $table->dropColumn(['linkedin_url']);
            }
            if (Schema::hasColumn('instructor_profiles', 'website_url')) {
                $table->dropColumn(['website_url']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instructor_profiles', function (Blueprint $table) {
            $table->string('headline')->nullable()->after('bio');
            $table->string('linkedin_url')->nullable()->after('headline');
            $table->string('website_url')->nullable()->after('linkedin_url');
        });
    }
};
