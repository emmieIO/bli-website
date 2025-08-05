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
        Schema::create('application_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_profile_id')->constrained('instructor_profiles', 'id')->onDelete('cascade');
            $table->string('application_id');
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // e.g. 'rejected', 'approved', 'updated-profile'
            $table->text('comment')->nullable(); // e.g. reason for rejection
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_logs');
    }
};
