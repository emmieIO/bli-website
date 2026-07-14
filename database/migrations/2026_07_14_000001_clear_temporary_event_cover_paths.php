<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // PHP upload paths are request-scoped and can never serve as permanent media.
        DB::table('events')
            ->where('program_cover', 'like', '/tmp/php%')
            ->update(['program_cover' => null]);
    }

    public function down(): void
    {
        // Temporary upload files are already gone, so their paths cannot be restored.
    }
};
