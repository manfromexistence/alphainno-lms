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
        Schema::create('teacher_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('month'); // e.g., '2026-01'
            $table->date('payment_date')->nullable();
            $table->string('status')->default('pending'); // pending, paid, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['teacher_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_salaries');
    }
};
