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
            $table->foreignId('student_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2)->default(0)->after('student_id');
            $table->string('payment_method')->nullable()->after('amount'); // cash, bank_transfer, mobile_payment, online
            $table->string('receipt_number')->unique()->nullable()->after('payment_method');
            $table->string('transaction_id')->nullable()->after('receipt_number');
            $table->date('payment_date')->nullable()->after('transaction_id');
            $table->text('notes')->nullable()->after('payment_date');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('completed')->after('notes');

            $table->index('student_id');
            $table->index('status');
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropIndex(['student_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['payment_date']);
            
            $table->dropColumn([
                'student_id',
                'amount',
                'payment_method',
                'receipt_number',
                'transaction_id',
                'payment_date',
                'notes',
                'status',
            ]);
        });
    }
};
