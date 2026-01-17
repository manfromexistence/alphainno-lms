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
        Schema::create('course_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_path')->nullable(); // Storage path for uploaded videos
            $table->string('video_type')->default('upload'); // 'upload', 'youtube', 'vimeo'
            $table->string('external_id')->nullable(); // For YouTube/Vimeo video IDs
            $table->string('thumbnail')->nullable(); // Video thumbnail
            $table->integer('duration')->nullable(); // Duration in seconds
            $table->integer('order')->default(0); // Display order
            $table->boolean('is_preview')->default(false); // Free preview for non-enrolled students
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_videos');
    }
};
