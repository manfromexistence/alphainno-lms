<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VerifyDatabaseSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:verify-schema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify database schema has all required columns';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verifying database schema...');
        $this->newLine();

        $checks = [
            'students' => ['status', 'balance', 'due_amount', 'paid_amount', 'total_amount'],
            'teachers' => ['status'],
            'batches' => ['status'],
            'courses' => ['status'],
            'payments' => ['status'],
            'attendances' => ['status'],
        ];

        $allPassed = true;

        foreach ($checks as $table => $columns) {
            $this->info("Checking table: {$table}");
            
            if (!Schema::hasTable($table)) {
                $this->error("  ✗ Table '{$table}' does not exist!");
                $allPassed = false;
                continue;
            }

            foreach ($columns as $column) {
                if (Schema::hasColumn($table, $column)) {
                    $this->line("  ✓ Column '{$column}' exists");
                } else {
                    $this->error("  ✗ Column '{$column}' is missing!");
                    $allPassed = false;
                }
            }
            
            $this->newLine();
        }

        if ($allPassed) {
            $this->info('✓ All schema checks passed!');
            return 0;
        } else {
            $this->error('✗ Some schema checks failed. Please run migrations.');
            $this->newLine();
            $this->info('Run: php artisan migrate --force');
            return 1;
        }
    }
}
