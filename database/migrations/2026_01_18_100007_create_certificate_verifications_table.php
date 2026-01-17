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
        Schema::create('certificate_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained('certificates')->onDelete('cascade');
            $table->timestamp('verified_at')->useCurrent();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            
            // Indexes for performance optimization
            $table->index('certificate_id');
            $table->index('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_verifications');
    }
};
