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
        Schema::dropIfExists('forum_replies');
        Schema::create('forum_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topic_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type', 100);
            $table->text('content');
            $table->json('attachments')->nullable();
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('topic_id')->references('id')->on('forum_topics')->onDelete('cascade');
            
            // Indexes for performance optimization
            $table->index('topic_id');
            $table->index(['user_id', 'user_type'], 'idx_reply_user');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_replies');
    }
};
