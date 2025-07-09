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
        Schema::table('events', function (Blueprint $table) {
            $table->string("physical_address")->nullable();
            $table->string("venue")->nullable();
            $table->string('contact_email')->nullable();
            $table->boolean('is_published')->default(false);
            $table->decimal('entry_fee', 8, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'physical_address',
                'venue',
                'contact_email',
                'is_published',
                'entry_fee',
            ]);
        });
    }
};
