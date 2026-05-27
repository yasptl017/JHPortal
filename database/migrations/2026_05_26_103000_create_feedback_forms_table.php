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
        Schema::create('feedback_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('title')->default('Event Feedback');
            $table->text('description')->nullable();
            $table->boolean('include_rating')->default(true);
            $table->boolean('include_comments')->default(true);
            $table->boolean('include_attendance')->default(true);
            $table->boolean('include_would_attend_again')->default(true);
            $table->json('custom_fields')->nullable(); // Store custom questions
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_forms');
    }
};
