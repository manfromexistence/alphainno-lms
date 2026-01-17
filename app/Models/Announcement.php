<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;

    /**
     * The target types for announcements.
     */
    public const TARGET_TYPES = [
        'all',
        'batch',
        'course',
    ];

    /**
     * The priority levels for announcements.
     */
    public const PRIORITIES = [
        'normal',
        'high',
        'urgent',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'target_type',
        'target_id',
        'priority',
        'starts_at',
        'expires_at',
        'is_active',
        'created_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user who created the announcement.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the batch associated with the announcement (if target_type is 'batch').
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'target_id');
    }

    /**
     * Get the course associated with the announcement (if target_type is 'course').
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'target_id');
    }

    /**
     * Scope a query to only include active announcements.
     * An announcement is active if:
     * - is_active is true
     * - starts_at is null or in the past
     * - expires_at is null or in the future
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        $now = Carbon::now();
        
        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', $now);
            });
    }

    /**
     * Scope a query to only include announcements relevant to a specific student.
     * Returns announcements where:
     * - target_type is 'all', OR
     * - target_type is 'batch' and target_id matches student's batch_id, OR
     * - target_type is 'course' and target_id matches student's batch's course_id
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\Student $student
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForStudent($query, Student $student)
    {
        return $query->where(function ($q) use ($student) {
            // Include announcements targeted to all
            $q->where('target_type', 'all')
              // Include announcements targeted to student's batch
              ->orWhere(function ($subQ) use ($student) {
                  $subQ->where('target_type', 'batch')
                       ->where('target_id', $student->batch_id);
              })
              // Include announcements targeted to student's course (via batch)
              ->orWhere(function ($subQ) use ($student) {
                  if ($student->batch && $student->batch->course_id) {
                      $subQ->where('target_type', 'course')
                           ->where('target_id', $student->batch->course_id);
                  }
              });
        });
    }

    /**
     * Scope a query to only include announcements with a specific priority.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $priority
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to only include urgent announcements.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    /**
     * Scope a query to only include high priority announcements.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    /**
     * Check if the announcement is currently active.
     *
     * @return bool
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Check if the announcement has expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get the target name (batch name, course name, or 'All').
     *
     * @return string
     */
    public function getTargetNameAttribute(): string
    {
        if ($this->target_type === 'all') {
            return 'All';
        }

        if ($this->target_type === 'batch' && $this->batch) {
            return $this->batch->name;
        }

        if ($this->target_type === 'course' && $this->course) {
            return $this->course->name;
        }

        return 'Unknown';
    }

    /**
     * Get the priority badge color class.
     *
     * @return string
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'red',
            'high' => 'orange',
            'normal' => 'blue',
            default => 'gray',
        };
    }
}
