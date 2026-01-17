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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('template_id')->constrained('certificate_templates');
            $table->string('certificate_number', 100)->unique();
            $table->string('verification_code', 50)->unique();
            $table->timestamp('issued_at')->useCurrent();
            $table->foreignId('issued_by')->constrained('users');
            $table->string('grade', 10)->nullable();
            $table->enum('status', ['active', 'revoked', 'expired'])->default('active');
            $table->timestamp('revoked_at')->nullable();
            $table->text('revocation_reason')->nullable();
            $table->string('pdf_path', 500)->nullable();
            $table->timestamps();
            
            // Indexes for performance optimization
            $table->index('student_id');
            $table->index('course_id');
            $table->index('verification_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
