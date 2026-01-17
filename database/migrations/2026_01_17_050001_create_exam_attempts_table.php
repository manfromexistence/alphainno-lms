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
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->json('answers')->nullable();
            $table->json('time_per_question')->nullable();
            $table->enum('status', ['in_progress', 'submitted', 'expired', 'graded'])->default('in_progress');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};
