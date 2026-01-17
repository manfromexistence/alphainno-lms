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
        Schema::table('exam_results', function (Blueprint $table) {
            $table->foreignId('exam_id')->nullable()->after('student_id')->constrained()->onDelete('cascade');
            $table->json('answers')->nullable()->after('grade'); // Store student's answers for MCQ exams
            $table->integer('total_marks')->nullable()->after('answers');
            $table->integer('obtained_marks')->nullable()->after('total_marks');
            $table->integer('rank')->nullable()->after('obtained_marks');
            $table->text('feedback')->nullable()->after('rank'); // Teacher feedback for CQ exams

            $table->index('exam_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropForeign(['exam_id']);
            $table->dropIndex(['exam_id']);
            
            $table->dropColumn([
                'exam_id',
                'answers',
                'total_marks',
                'obtained_marks',
                'rank',
                'feedback',
            ]);
        });
    }
};
