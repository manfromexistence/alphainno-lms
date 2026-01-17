<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ActivityLogService
{
    public function log(string $action, ?Model $model = null, ?array $changes = null): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->getKey(),
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function getLogs(array $filters = []): LengthAwarePaginator
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (!empty($filters['model_type'])) {
            $query->where('model_type', 'like', '%' . $filters['model_type'] . '%');
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        return $query->paginate($filters['per_page'] ?? 25);
    }

    public function getActions(): array
    {
        return ActivityLog::distinct('action')->pluck('action')->toArray();
    }

    public function getModelTypes(): array
    {
        return ActivityLog::distinct('model_type')
            ->whereNotNull('model_type')
            ->pluck('model_type')
            ->map(fn($type) => class_basename($type))
            ->toArray();
    }
}
