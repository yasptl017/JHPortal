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
        Schema::table('waitlist', function (Blueprint $table) {
            // Add position column if it doesn't exist
            if (!Schema::hasColumn('waitlist', 'position')) {
                $table->integer('position')->nullable()->after('status');
            }

            // Add expired_at column if it doesn't exist
            if (!Schema::hasColumn('waitlist', 'expired_at')) {
                $table->timestamp('expired_at')->nullable()->after('confirmed_at');
            }

            // Add index for faster queries
            if (!Schema::hasColumn('waitlist', 'event_id')) {
                $table->index('event_id');
            }
            if (!Schema::hasColumn('waitlist', 'user_id')) {
                $table->index('user_id');
            }
            if (!Schema::hasColumn('waitlist', 'status')) {
                $table->index('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waitlist', function (Blueprint $table) {
            $table->dropIndexIfExists(['event_id']);
            $table->dropIndexIfExists(['user_id']);
            $table->dropIndexIfExists(['status']);
            
            if (Schema::hasColumn('waitlist', 'position')) {
                $table->dropColumn('position');
            }
            
            if (Schema::hasColumn('waitlist', 'expired_at')) {
                $table->dropColumn('expired_at');
            }
        });
    }
};
