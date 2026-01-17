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
        Schema::create('email_queue', function (Blueprint $table) {
            $table->id();
            $table->string('to');
            $table->string('subject', 500);
            $table->text('body_html');
            $table->text('body_text')->nullable();
            $table->string('template_type', 100)->nullable();
            $table->json('variables')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('retry_count')->default(0);
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            // Indexes for performance optimization
            $table->index('status');
            $table->index('scheduled_at');
            $table->index('to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_queue');
    }
};
