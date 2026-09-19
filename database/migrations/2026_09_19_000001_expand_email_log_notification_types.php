<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE email_logs MODIFY type ENUM('registration_confirmation', 'registration_cancellation', 'event_reminder', 'event_updated', 'event_cancelled', 'feedback_request', 'waitlist_notification', 'waitlist_reminder', 'general') NOT NULL DEFAULT 'general'");
        }

        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->foreign('event_id')->references('id')->on('events')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE email_logs MODIFY type ENUM('registration_confirmation', 'event_reminder', 'feedback_request', 'waitlist_notification', 'general') NOT NULL DEFAULT 'general'");
        }

        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete();
        });
    }
};
