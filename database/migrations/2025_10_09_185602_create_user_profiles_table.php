<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('slug')->nullable()->after('id')->unique();
            $table->string('linkedin')->nullable();
            $table->string('website')->nullable();
            $table->string('headline')->nullable();
            $table->string('photo')->nullable();
        });
        User::whereNull('slug')->orWhere('slug', '')->get()->each(function ($user) {
            $user->slug = (string) Str::uuid();
            $user->saveQuietly();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->uuid('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['slug', 'linkedin', 'website', 'headline', 'photo']);
        });
    }
};
