<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('action_trigger_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('action_type_id')->constrained()->cascadeOnDelete();
            $table->integer('year');
            $table->integer('month');
            $table->json('trigger_data')->nullable();
            $table->timestamp('triggered_at')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled', 'escalated'])->default('pending');
            $table->date('due_date')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_actions');
    }
};
