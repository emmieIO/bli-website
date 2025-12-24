<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->nullableMorphs('payable');
        });

        // Migrate existing data
        DB::table('transactions')->whereNotNull('course_id')->update([
            'payable_id' => DB::raw('course_id'),
            'payable_type' => 'App\\Models\\Course',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropMorphs('payable');
        });
    }
};