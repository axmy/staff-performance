<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->timestamp('notified_at')->nullable();
            $table->enum('notification_method', ['email', 'sms', 'verbal', 'notice_board', 'other'])->nullable();
            $table->enum('response', ['confirmed', 'declined', 'tentative', 'no_response'])->default('no_response');
            $table->timestamp('response_at')->nullable();
            $table->text('response_notes')->nullable();
            $table->foreignId('notified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['training_session_id', 'staff_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_notifications');
    }
};
