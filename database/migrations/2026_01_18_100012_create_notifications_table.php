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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_type', 100);
            $table->string('type', 100);
            $table->string('title', 500);
            $table->text('message');
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->string('action_url', 500)->nullable();
            $table->timestamps();
            
            // Indexes for performance optimization
            $table->index(['user_id', 'user_type'], 'idx_notification_user');
            $table->index('type');
            $table->index('read_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
