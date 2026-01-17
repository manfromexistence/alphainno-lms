<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of classes with their courses and batches.
     */
    public function index(Request $request)
    {
        $classes = $this->getClassesWithData();
        
        // Filter by class if specified
        if ($request->filled('class')) {
            $classes = array_filter($classes, function($class) use ($request) {
                return $class['number'] == $request->class;
            });
        }
        
        return view('dashboard.classes.index', compact('classes'));
    }

    /**
     * Show details for a specific class.
     */
    public function show($classNumber)
    {
        if ($classNumber < 1 || $classNumber > 12) {
            abort(404);
        }
        
        $courses = \App\Models\Course::where('class', $classNumber)
            ->with(['batches.students'])
            ->get();
        
        $batches = \App\Models\Batch::whereHas('course', function($query) use ($classNumber) {
            $query->where('class', $classNumber);
        })->with(['course', 'students', 'teachers'])->get();
        
        $students = \App\Models\Student::where('class', $classNumber)
            ->with(['batch.course'])
            ->get();
        
        return view('dashboard.classes.show', compact('classNumber', 'courses', 'batches', 'students'));
    }

    /**
     * Get all classes with their associated data.
     */
    protected function getClassesWithData()
    {
        $classes = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $coursesCount = \App\Models\Course::where('class', $i)->count();
            $batchesCount = \App\Models\Batch::whereHas('course', function($query) use ($i) {
                $query->where('class', $i);
            })->count();
            $studentsCount = \App\Models\Student::where('class', $i)->count();
            
            $classes[] = [
                'number' => $i,
                'name' => "Class $i",
                'courses_count' => $coursesCount,
                'batches_count' => $batchesCount,
                'students_count' => $studentsCount,
            ];
        }
        
        return $classes;
    }
}
