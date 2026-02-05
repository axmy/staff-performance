<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('action_triggers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('trigger_type', ['attendance', 'training'])->default('attendance');
            $table->enum('condition_field', [
                'absent_days',
                'late_days',
                'leave_days',
                'missed_trainings',
                'late_to_trainings'
            ]);
            $table->enum('condition_operator', ['>=', '>', '=', '<=', '<'])->default('>=');
            $table->integer('threshold_value');
            $table->foreignId('action_type_id')->constrained()->cascadeOnDelete();
            $table->enum('period_type', ['monthly', 'quarterly', 'yearly', 'cumulative'])->default('monthly');
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_trigger')->default(true);
            $table->integer('priority')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_triggers');
    }
};
