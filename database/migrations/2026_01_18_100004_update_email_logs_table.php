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
        // Drop the old email_logs table
        Schema::dropIfExists('email_logs');
        
        // Recreate with the new structure
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('to');
            $table->string('subject', 500);
            $table->string('template_type', 100)->nullable();
            $table->enum('status', ['sent', 'failed']);
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_type', 100)->nullable();
            $table->timestamps();
            
            // Indexes for performance optimization
            $table->index('to');
            $table->index('status');
            $table->index('sent_at');
            $table->index(['user_id', 'user_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the new table
        Schema::dropIfExists('email_logs');
        
        // Recreate the old structure
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient');
            $table->string('subject');
            $table->text('body')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }
};
