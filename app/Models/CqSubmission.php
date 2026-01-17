<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CqSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'exam_id',
        'files',
        'annotated_files',
        'submitted_at',
        'evaluated_at',
        'marks',
        'feedback',
        'teacher_notes',
        'evaluated_by',
    ];

    protected function casts(): array
    {
        return [
            'files' => 'array',
            'annotated_files' => 'array',
            'submitted_at' => 'datetime',
            'evaluated_at' => 'datetime',
            'marks' => 'decimal:2',
        ];
    }

    /**
     * Get the student who made the submission.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the exam for the submission.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the evaluator.
     */
    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    /**
     * Check if the submission has been evaluated.
     */
    public function isEvaluated(): bool
    {
        return $this->evaluated_at !== null;
    }

    /**
     * Check if the submission is pending evaluation.
     */
    public function isPending(): bool
    {
        return $this->submitted_at !== null && $this->evaluated_at === null;
    }

    /**
     * Get the file count.
     */
    public function getFileCountAttribute(): int
    {
        return count($this->files ?? []);
    }

    /**
     * Scope to get pending submissions.
     */
    public function scopePending($query)
    {
        return $query->whereNotNull('submitted_at')->whereNull('evaluated_at');
    }

    /**
     * Scope to get evaluated submissions.
     */
    public function scopeEvaluated($query)
    {
        return $query->whereNotNull('evaluated_at');
    }

    /**
     * Scope to filter by exam.
     */
    public function scopeForExam($query, int $examId)
    {
        return $query->where('exam_id', $examId);
    }
}
