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
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->text('message');
            $table->enum('status', ['pending', 'sent', 'delivered', 'failed'])->default('pending');
            $table->string('type')->nullable(); // e.g., 'result_notification', 'payment_confirmation', 'bulk'
            $table->string('related_type')->nullable(); // Polymorphic: e.g., 'App\Models\ExamResult'
            $table->unsignedBigInteger('related_id')->nullable(); // Polymorphic: related model ID
            $table->datetime('sent_at')->nullable();
            $table->timestamps();

            $table->index('phone');
            $table->index('status');
            $table->index('type');
            $table->index(['related_type', 'related_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
