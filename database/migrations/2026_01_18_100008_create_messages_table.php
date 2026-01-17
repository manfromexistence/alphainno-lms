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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_user_id');
            $table->string('from_user_type', 100);
            $table->unsignedBigInteger('to_user_id');
            $table->string('to_user_type', 100);
            $table->string('subject', 500)->nullable();
            $table->text('content');
            $table->json('attachments')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            
            // Indexes for performance optimization
            $table->index(['from_user_id', 'from_user_type'], 'idx_from');
            $table->index(['to_user_id', 'to_user_type'], 'idx_to');
            $table->index('read_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
