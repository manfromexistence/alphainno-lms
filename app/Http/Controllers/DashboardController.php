<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->roles->first()?->slug ?? 'guest';
        
        // Redirect to role-specific dashboards
        if ($role === 'student' || $user->isStudent()) {
            return redirect()->route('student.dashboard');
        }
        
        if ($role === 'teacher' || $user->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        }
        
        // Admin and Super Admin see the main dashboard
        $widgets = $this->dashboardService->getWidgetsForRole($role);
        $statistics = $this->dashboardService->getStatisticsForRole($role, $user->id);
        $recentActivities = $this->dashboardService->getRecentActivities();
        
        // Chart data for admin
        $chartData = [];
        if ($role === 'super-admin' || $user->isSuperAdmin()) {
            $chartData = [
                'revenue' => $this->dashboardService->getChartData('revenue'),
                'attendance' => $this->dashboardService->getChartData('attendance'),
                'admissions' => $this->dashboardService->getChartData('admissions'),
            ];
        }

        return view('dashboard.index', compact('widgets', 'statistics', 'recentActivities', 'chartData', 'role'));
    }

    public function config(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->dashboardService->updateDashboardConfig($request->all());
            return back()->with('success', 'Dashboard configuration updated.');
        }

        $config = $this->dashboardService->getDashboardConfig();
        return view('dashboard.config', compact('config'));
    }

    public function chartData(Request $request, string $type)
    {
        return response()->json($this->dashboardService->getChartData($type));
    }
}
