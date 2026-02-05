<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('action_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_action_id')->constrained()->cascadeOnDelete();
            $table->enum('feedback_by', ['staff', 'manager', 'hr', 'other'])->default('staff');
            $table->foreignId('feedback_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('feedback_text');
            $table->date('feedback_date');
            $table->enum('acknowledgment_status', ['pending', 'acknowledged', 'disputed', 'appealed'])->default('pending');
            $table->text('acknowledgment_notes')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_feedbacks');
    }
};
