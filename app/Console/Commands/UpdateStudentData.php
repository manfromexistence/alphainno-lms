<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Batch;

class UpdateStudentData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:update-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing students with registration numbers and groups';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating student data...');
        
        $students = Student::all();
        $updated = 0;
        
        foreach ($students as $student) {
            $needsUpdate = false;
            
            // Update registration number if missing
            if (empty($student->registration_no)) {
                $year = date('Y');
                $batchCode = 'STU';
                
                if ($student->batch_id) {
                    $batch = Batch::find($student->batch_id);
                    if ($batch) {
                        $batchCode = $batch->code ?? 'BAT';
                    }
                }
                
                // Use student ID for sequence
                $sequence = $student->id;
                $student->registration_no = $year . '-' . $batchCode . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
                $needsUpdate = true;
            }
            
            // Update group if missing and class is 9 or above
            if (empty($student->group) && !empty($student->class)) {
                $class = (int) $student->class;
                if ($class >= 9) {
                    $student->group = collect(['Science', 'Commerce', 'Arts', 'Humanities'])->random();
                    $needsUpdate = true;
                }
            }
            
            if ($needsUpdate) {
                $student->save();
                $updated++;
            }
        }
        
        $this->info("Updated {$updated} students successfully!");
        
        return 0;
    }
}
