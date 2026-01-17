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
        Schema::create('video_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_video_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->integer('watched_seconds')->default(0);
            $table->boolean('completed')->default(false);
            $table->timestamp('last_watched_at')->nullable();
            $table->timestamps();
            
            // Ensure one record per student per video
            $table->unique(['course_video_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_views');
    }
};
