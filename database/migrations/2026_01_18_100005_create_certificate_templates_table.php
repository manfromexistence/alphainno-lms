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
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 100);
            $table->string('background_image', 500)->nullable();
            $table->string('logo_image', 500)->nullable();
            $table->string('signature_image', 500)->nullable();
            $table->json('layout_config')->nullable();
            $table->json('variables')->nullable();
            $table->integer('width')->default(1200);
            $table->integer('height')->default(900);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            
            // Indexes for performance optimization
            $table->index('type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
