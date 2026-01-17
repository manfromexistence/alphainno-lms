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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['mcq', 'cq', 'mixed'])->default('mcq'); // MCQ, CQ, or mixed
            $table->foreignId('batch_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('total_marks');
            $table->integer('pass_marks');
            $table->integer('duration_minutes')->nullable(); // Duration in minutes for online exams
            $table->datetime('start_time')->nullable();
            $table->datetime('end_time')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'active', 'completed', 'cancelled'])->default('draft');
            $table->text('instructions')->nullable();
            $table->timestamps();

            $table->index(['batch_id', 'status']);
            $table->index(['course_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
