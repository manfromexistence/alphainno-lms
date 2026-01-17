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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();
            $table->date('income_date');
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('reference')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['category', 'income_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
