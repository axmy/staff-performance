<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->integer('year');
            $table->integer('month');
            $table->integer('days_present')->default(0);
            $table->integer('days_absent')->default(0);
            $table->integer('days_leave')->default(0);
            $table->integer('days_late')->default(0);
            $table->integer('total_working_days')->default(0);
            $table->foreignId('import_batch_id')->nullable()->constrained('attendance_imports')->nullOnDelete();
            $table->boolean('is_processed')->default(false);
            $table->timestamps();

            $table->unique(['staff_id', 'year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_attendances');
    }
};
