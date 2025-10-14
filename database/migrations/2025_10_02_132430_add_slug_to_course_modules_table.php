<?php

use App\Models\CourseModule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('course_modules', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
        });
        CourseModule::whereNull('slug')->orWhere('slug', '')->get()->each(function ($module) {
            $module->slug = (string) Str::uuid();
            $module->saveQuietly();
        });

        Schema::table('course_modules', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->after('id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_modules', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
