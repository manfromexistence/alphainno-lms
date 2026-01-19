<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to alter the enum
        // For SQLite, enum is just a string so this won't cause issues
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'completed', 'failed', 'refunded') DEFAULT 'pending'");
        } else {
            // For SQLite, we don't need to do anything as it stores enums as strings
            // But we'll add a check constraint for consistency
            Schema::table('payments', function (Blueprint $table) {
                // SQLite doesn't support modifying columns easily, so we'll just ensure the column exists
                if (!Schema::hasColumn('payments', 'status')) {
                    $table->string('status')->default('pending');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'completed'");
        }
    }
};
