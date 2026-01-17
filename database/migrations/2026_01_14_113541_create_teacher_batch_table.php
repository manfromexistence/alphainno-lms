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
        Schema::create('teacher_batch', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');
            $table->json('subjects')->nullable(); // Subjects taught in this batch
            $table->timestamps();

            $table->unique(['teacher_id', 'batch_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_batch');
    }
};
