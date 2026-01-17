<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'changes',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'changes' => 'array',
        ];
    }

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject of the activity.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo('model');
    }

    /**
     * Scope to filter by action.
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by model type.
     */
    public function scopeForModel($query, string $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get human readable action.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'login' => 'Logged in',
            'logout' => 'Logged out',
            default => ucfirst($this->action),
        };
    }

    /**
     * Get model name without namespace.
     */
    public function getModelNameAttribute(): string
    {
        if (!$this->model_type) {
            return '-';
        }
        return class_basename($this->model_type);
    }
}
