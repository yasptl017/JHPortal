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
            $table->string('title');
            $table->string('category', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('audience')->nullable();
            $table->string('visibility')->default('public');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('location')->nullable();
            $table->string('meeting_link')->nullable();
            $table->text('venue_notes')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->unsignedInteger('registration_limit')->default(1);
            $table->dateTime('registration_close_date')->nullable();
            $table->string('approval_mode')->default('automatic');
            $table->string('external_id')->nullable();
            $table->boolean('waitlist_enabled')->default(false);
            $table->text('required_fields')->nullable();
            $table->text('confirmation_message')->nullable();
            $table->text('reminder_message')->nullable();
            $table->text('waitlist_message')->nullable();
            $table->text('feedback_message')->nullable();
            $table->string('reminder_offset')->default('24h');
            $table->string('feedback_offset')->default('24h');
            $table->string('email_status')->default('enabled');
            $table->string('status')->default('draft');
            $table->timestamps();

            $table->index(['start_date', 'status']);
            $table->index('category');
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
