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
        Schema::create('instructor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained('users', 'id')->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('experience_years')->nullable();
            $table->string('area_of_expertise')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('website')->nullable();
            $table->string('intro_video_url')->nullable();
            $table->string('resume_path')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_profiles');
    }
};
