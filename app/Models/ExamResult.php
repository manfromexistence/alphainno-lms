<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'exam_id',
        'subject_name',
        'marks',
        'grade',
        'answers',
        'total_marks',
        'obtained_marks',
        'rank',
        'feedback',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'marks' => 'integer',
            'total_marks' => 'integer',
            'obtained_marks' => 'integer',
            'rank' => 'integer',
        ];
    }

    /**
     * Get the student that owns the result.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the exam that owns the result.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the percentage score.
     *
     * @return float
     */
    public function getPercentageAttribute(): float
    {
        if (!$this->total_marks || $this->total_marks === 0) {
            return 0;
        }

        return round(($this->obtained_marks / $this->total_marks) * 100, 2);
    }

    /**
     * Check if the student passed the exam.
     *
     * @return bool
     */
    public function hasPassed(): bool
    {
        if (!$this->exam) {
            return false;
        }

        return $this->obtained_marks >= $this->exam->pass_marks;
    }

    /**
     * Calculate grade based on percentage.
     *
     * @return string
     */
    public function calculateGrade(): string
    {
        $percentage = $this->percentage;

        if ($percentage >= 90) {
            return 'A+';
        } elseif ($percentage >= 80) {
            return 'A';
        } elseif ($percentage >= 70) {
            return 'B+';
        } elseif ($percentage >= 60) {
            return 'B';
        } elseif ($percentage >= 50) {
            return 'C';
        } elseif ($percentage >= 40) {
            return 'D';
        } else {
            return 'F';
        }
    }

    /**
     * Scope a query to only include results for a specific exam.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $examId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForExam($query, int $examId)
    {
        return $query->where('exam_id', $examId);
    }

    /**
     * Scope a query to only include results for a specific student.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $studentId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope a query to order by rank.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByRank($query)
    {
        return $query->orderBy('rank');
    }

    /**
     * Scope a query to order by obtained marks descending.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByScore($query)
    {
        return $query->orderByDesc('obtained_marks');
    }
}
