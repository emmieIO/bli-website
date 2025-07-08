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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string("slug")->unique();
            $table->string('title');
            $table->text('description');
            $table->string('program_cover')->nullable();
            $table->string("mode");
            $table->string("location")->nullable()->comment("This can also serve as url for meeting link");
            $table->datetime("start_date");
            $table->datetime("end_date")->nullable();
            $table->foreignId("creator_id")->contrained('users', 'id')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
