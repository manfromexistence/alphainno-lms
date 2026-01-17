<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('forum_topics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type', 100);
            $table->string('title', 500);
            $table->text('content');
            $table->json('attachments')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->integer('views_count')->default(0);
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            
            // Indexes for performance optimization
            $table->index('course_id');
            $table->index(['user_id', 'user_type'], 'idx_user');
            $table->index('is_pinned');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_topics');
    }
};
