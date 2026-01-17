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
        Schema::table('batches', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('schedule')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('max_students')->nullable();
            $table->enum('status', ['active', 'inactive', 'completed'])->default('active');
            $table->string('room')->nullable();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['course_id', 'schedule', 'start_date', 'end_date', 'max_students', 'status', 'room', 'teacher_id']);
        });
    }
};
