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
        Schema::create('forum_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topic_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type', 100);
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('topic_id')->references('id')->on('forum_topics')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate subscriptions
            $table->unique(['topic_id', 'user_id', 'user_type'], 'unique_subscription');
            
            // Indexes for performance optimization
            $table->index('topic_id');
            $table->index(['user_id', 'user_type'], 'idx_subscription_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_subscriptions');
    }
};
