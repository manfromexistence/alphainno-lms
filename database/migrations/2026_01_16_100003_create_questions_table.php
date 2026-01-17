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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->enum('type', ['mcq', 'cq', 'true_false', 'short_answer'])->default('mcq');
            $table->json('options')->nullable(); // For MCQ: array of options
            $table->text('correct_answer')->nullable(); // For MCQ: correct option key; for CQ: model answer
            $table->integer('marks')->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index('exam_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
