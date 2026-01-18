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
        Schema::table('payments', function (Blueprint $table) {
            // Add course_id for course enrollment payments
            $table->foreignId('course_id')->nullable()->after('student_id')->constrained()->cascadeOnDelete();
            
            // Add screenshot_path for payment proof
            $table->string('screenshot_path')->nullable()->after('transaction_id');
            
            // Add submitted_at timestamp for when payment was submitted
            $table->timestamp('submitted_at')->nullable()->after('payment_date');
            
            // Add reviewed_at timestamp for when admin reviewed the payment
            $table->timestamp('reviewed_at')->nullable()->after('submitted_at');
            
            // Add reviewed_by to track which admin reviewed the payment
            $table->foreignId('reviewed_by')->nullable()->after('reviewed_at')->constrained('users')->nullOnDelete();
            
            // Add admin_notes for rejection reasons or other notes
            $table->text('admin_notes')->nullable()->after('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn([
                'course_id',
                'screenshot_path',
                'submitted_at',
                'reviewed_at',
                'reviewed_by',
                'admin_notes'
            ]);
        });
    }
};
