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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('reference_code')->nullable()->after('id');
        });

        // Generate reference codes for existing tickets
        $tickets = \App\Models\Ticket::all();
        foreach ($tickets as $ticket) {
            $prefix = 'TKT';
            $date = $ticket->created_at->format('Ymd');
            $lastTicket = \App\Models\Ticket::where('reference_code', 'like', "{$prefix}-{$date}-%")->latest('id')->first();
            $sequence = $lastTicket ? (intval(substr($lastTicket->reference_code, -3)) + 1) : ($ticket->id);

            $ticket->update([
                'reference_code' => sprintf('%s-%s-%03d', $prefix, $date, $sequence)
            ]);
        }

        // Make the column unique after populating
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('reference_code')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('reference_code');
        });
    }
};
