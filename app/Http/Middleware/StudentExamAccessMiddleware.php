<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to restrict student access to admin exam functions.
 * 
 * Students should only be able to:
 * - View exam list
 * - Take exams
 * - View their own results
 * 
 * Students should NOT be able to:
 * - Create, edit, or delete exams
 * - View exam questions in admin mode
 * - Manage exam results
 * - Access exam review functions
 * 
 * Usage in routes:
 * Route::middleware('student.exam.access')
 */
class StudentExamAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // If user is not a student, allow access (admins, teachers, etc.)
        if (!$request->user()->isStudent()) {
            return $next($request);
        }

        // Student is trying to access the route
        // Check if this is an admin exam route that students should not access
        $route = $request->route();
        $routeName = $route ? $route->getName() : '';
        
        // List of routes that students CAN access (viewing exam lists)
        $allowedRoutes = [
            'dashboard.exams.index',     // All exams list
            'dashboard.exams.mcq',       // MCQ exams list
            'dashboard.exams.cq',        // CQ exams list
            'dashboard.exams.live',      // Live exams list
            'dashboard.exams.results',   // Exam results list
            'dashboard.exams.leaderboard', // Exam leaderboard
        ];
        
        // If the route is in the allowed list, let students access it
        if (in_array($routeName, $allowedRoutes)) {
            return $next($request);
        }
        
        // List of admin exam routes that students should NOT access
        $restrictedRoutes = [
            'dashboard.exams.create',
            'dashboard.exams.store',
            'dashboard.exams.edit',
            'dashboard.exams.update',
            'dashboard.exams.destroy',
            'dashboard.exams.show', // Admin view of exam details
            'dashboard.exams.questions.store',
            'dashboard.exams.questions.show',
            'dashboard.exams.questions.update',
            'dashboard.exams.questions.destroy',
            'dashboard.exams.import-questions',
            'dashboard.exams.process-import',
            'dashboard.exams.export-questions',
            'dashboard.exams.download-template',
            'dashboard.exams.view-result',
            'dashboard.exams.edit-result',
            'dashboard.exams.update-result',
            'dashboard.exams.delete-result',
            'dashboard.exams.export-results',
            'dashboard.exams.review-submissions',
            'dashboard.exams.review-submission',
            'dashboard.exams.save-review',
        ];

        // Check if the current route is in the restricted list
        if (in_array($routeName, $restrictedRoutes)) {
            return $this->redirectToStudentExams($request);
        }

        // Check if the URL path contains admin exam patterns that should be blocked
        $path = $request->path();
        
        // Block access to specific dashboard exam management routes
        // But allow viewing exam lists
        if (preg_match('#^dashboard/exams/[0-9]+/(edit|questions|import|export|review)#', $path)) {
            return $this->redirectToStudentExams($request);
        }
        
        // Block access to exam creation
        if (preg_match('#^dashboard/exams/create#', $path)) {
            return $this->redirectToStudentExams($request);
        }

        // Allow access to student exam routes and exam list views
        return $next($request);
    }

    /**
     * Redirect student to their exam list with an access denied message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function redirectToStudentExams(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Students cannot access exam management functions.',
                'code' => 'STUDENT_EXAM_ACCESS_DENIED'
            ], 403);
        }

        return redirect()
            ->route('student.exams')
            ->with('error', 'Access denied. You do not have permission to access exam management functions.');
    }
}
