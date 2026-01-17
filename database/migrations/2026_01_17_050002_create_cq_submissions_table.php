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
        Schema::create('cq_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->json('files')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->decimal('marks', 8, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cq_submissions');
    }
};
