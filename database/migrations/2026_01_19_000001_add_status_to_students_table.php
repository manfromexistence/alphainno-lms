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
        Schema::table('students', function (Blueprint $table) {
            $table->string('status')->default('active')->after('payment_method');
            $table->decimal('balance', 10, 2)->default(0)->after('status')->comment('Alias for due_amount for compatibility');
        });
        
        // Copy due_amount to balance for existing records
        DB::statement('UPDATE students SET balance = due_amount WHERE due_amount IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['status', 'balance']);
        });
    }
};
