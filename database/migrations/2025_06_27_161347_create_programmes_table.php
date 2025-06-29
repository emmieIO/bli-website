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
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string("slug")->unique();
            $table->string('theme');
            $table->string('description');
            $table->string('program_cover')->nullable();
            $table->string("mode");
            $table->datetime("start_date");
            $table->datetime("end_date")->nullable();
            $table->string("host");
            $table->json('ministers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
