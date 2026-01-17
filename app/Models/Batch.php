<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'course_id',
        'schedule',
        'start_date',
        'end_date',
        'max_students',
        'status',
        'room',
        'teacher_id',
        'telegram_link',
        'facebook_link'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'max_students' => 'integer',
    ];

    /**
     * Get the course that owns the batch.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the students for the batch.
     */
    public function students(): HasMany
    {
        // Assuming students table has batch_id foreign key
        return $this->hasMany(Student::class, 'batch_id');
    }

    /**
     * Get the teachers for the batch.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_batch');
    }

    /**
     * Get the attendances for the batch.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the schedules for the batch.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class)->orderBy('day_of_week')->orderBy('start_time');
    }

    /**
     * Get the exams for the batch.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Get the count of students in the batch.
     *
     * @return int
     */
    public function getStudentsCountAttribute(): int
    {
        return $this->students()->count();
    }

    /**
     * Check if the batch has reached its capacity.
     *
     * @return bool
     */
    public function isAtCapacity(): bool
    {
        if (!$this->max_students) {
            return false;
        }

        return $this->students_count >= $this->max_students;
    }

    /**
     * Get the remaining capacity for the batch.
     *
     * @return int|null
     */
    public function getRemainingCapacityAttribute(): ?int
    {
        if (!$this->max_students) {
            return null;
        }

        return max(0, $this->max_students - $this->students_count);
    }

    /**
     * Scope a query to only include active batches.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive batches.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope a query to only include completed batches.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include batches with available capacity.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAvailableCapacity($query)
    {
        return $query->whereRaw('(SELECT COUNT(*) FROM students WHERE students.batch_id = batches.id) < batches.max_students')
            ->orWhereNull('max_students');
    }
}
