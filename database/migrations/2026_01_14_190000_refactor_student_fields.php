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
        Schema::table('students', function (Blueprint $table) {
            // Drop unnecessary fields
            $table->dropColumn([
                'student_signature',
                'guardian_signature',
                'authority_signature',
                'class_days',
                'class_time'
            ]);

            // Add Payment Fields
            $table->decimal('total_amount', 10, 2)->default(0)->after('status')->nullable();
            $table->decimal('paid_amount', 10, 2)->default(0)->after('total_amount')->nullable();
            $table->decimal('due_amount', 10, 2)->default(0)->after('paid_amount')->nullable();
            $table->string('payment_method')->nullable()->after('due_amount');

            // Ensure proper indexing if needed
            if (!Schema::hasColumn('students', 'registration_no')) {
                $table->string('registration_no')->unique()->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('student_signature')->nullable();
            $table->string('guardian_signature')->nullable();
            $table->string('authority_signature')->nullable();
            $table->string('class_days')->nullable();
            $table->string('class_time')->nullable();

            $table->dropColumn(['total_amount', 'paid_amount', 'due_amount', 'payment_method']);
        });
    }
};
