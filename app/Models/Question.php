<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'exam_id',
        'question_text',
        'type',
        'options',
        'correct_answer',
        'marks',
        'order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'options' => 'array',
            'marks' => 'integer',
            'order' => 'integer',
        ];
    }

    /**
     * Get the exam that owns the question.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Check if the given answer is correct.
     *
     * @param string $answer
     * @return bool
     */
    public function isCorrectAnswer(string $answer): bool
    {
        return strtolower(trim($answer)) === strtolower(trim($this->correct_answer));
    }

    /**
     * Check if this is an MCQ question.
     *
     * @return bool
     */
    public function isMcq(): bool
    {
        return $this->type === 'mcq';
    }

    /**
     * Check if this is a CQ (creative question).
     *
     * @return bool
     */
    public function isCq(): bool
    {
        return $this->type === 'cq';
    }

    /**
     * Check if this is a true/false question.
     *
     * @return bool
     */
    public function isTrueFalse(): bool
    {
        return $this->type === 'true_false';
    }

    /**
     * Check if this is a short answer question.
     *
     * @return bool
     */
    public function isShortAnswer(): bool
    {
        return $this->type === 'short_answer';
    }

    /**
     * Get the options as a formatted array for display.
     *
     * @return array
     */
    public function getFormattedOptionsAttribute(): array
    {
        if (!$this->options || !is_array($this->options)) {
            return [];
        }

        return $this->options;
    }

    /**
     * Scope a query to only include questions of a given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to order by the question order.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
