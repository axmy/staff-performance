<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_imports', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('original_file_name');
            $table->integer('year');
            $table->integer('month');
            $table->foreignId('imported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('records_count')->default(0);
            $table->integer('success_count')->default(0);
            $table->integer('error_count')->default(0);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->json('error_log')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_imports');
    }
};
