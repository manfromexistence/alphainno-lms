<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSchedule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'batch_id',
        'teacher_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
        'subject',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
        ];
    }

    /**
     * Get the batch that owns the schedule.
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Get the teacher that owns the schedule.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the formatted time range.
     *
     * @return string
     */
    public function getTimeRangeAttribute(): string
    {
        $start = $this->start_time ? $this->start_time->format('h:i A') : '';
        $end = $this->end_time ? $this->end_time->format('h:i A') : '';

        return "{$start} - {$end}";
    }

    /**
     * Get the day name in title case.
     *
     * @return string
     */
    public function getDayNameAttribute(): string
    {
        return ucfirst($this->day_of_week);
    }

    /**
     * Get the duration in minutes.
     *
     * @return int
     */
    public function getDurationMinutesAttribute(): int
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }

        return $this->start_time->diffInMinutes($this->end_time);
    }

    /**
     * Check if the schedule is for today.
     *
     * @return bool
     */
    public function isToday(): bool
    {
        return strtolower(now()->format('l')) === strtolower($this->day_of_week);
    }

    /**
     * Scope a query to only include schedules for a given day.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $day
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForDay($query, string $day)
    {
        return $query->where('day_of_week', strtolower($day));
    }

    /**
     * Scope a query to only include today's schedules.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToday($query)
    {
        return $query->where('day_of_week', strtolower(now()->format('l')));
    }

    /**
     * Scope a query to order by start time.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByTime($query)
    {
        return $query->orderBy('start_time');
    }
}
