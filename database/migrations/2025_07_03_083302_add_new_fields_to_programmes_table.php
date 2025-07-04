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
        Schema::table('programmes', function (Blueprint $table) {
            $table->string("location")->nullable()->comment("This can also serve as url for meeting link");
            $table->json('metadata')->nullable()->after('description')->after('ministers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            $table->dropColumn([
                'location',
                'metadata'
            ]);
        });
    }
};
