<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct(protected ActivityLogService $activityLogService)
    {}

    public function index(Request $request)
    {
        $logs = $this->activityLogService->getLogs($request->all());
        $users = User::all();
        $actions = $this->activityLogService->getActions();
        $modelTypes = $this->activityLogService->getModelTypes();

        return view('dashboard.activity-logs.index', [
            'logs' => $logs,
            'users' => $users,
            'actions' => $actions,
            'modelTypes' => $modelTypes,
            'filters' => $request->all(),
        ]);
    }
}
