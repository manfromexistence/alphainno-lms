<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'to',
        'subject',
        'template_type',
        'status',
        'sent_at',
        'error_message',
        'user_id',
        'user_type',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    /**
     * Get the user associated with this email log (polymorphic).
     */
    public function user(): MorphTo
    {
        return $this->morphTo('user');
    }

    /**
     * Get the template associated with this log entry.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'template_type', 'type');
    }

    /**
     * Scope to filter by status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for sent emails.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for failed emails.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope to filter by recipient email.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $email
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForRecipient($query, string $email)
    {
        return $query->where('to', $email);
    }

    /**
     * Scope to filter by template type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfTemplateType($query, string $type)
    {
        return $query->where('template_type', $type);
    }

    /**
     * Scope to filter by user (polymorphic).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @param string $userType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, int $userId, string $userType)
    {
        return $query->where('user_id', $userId)
            ->where('user_type', $userType);
    }

    /**
     * Scope to filter by date range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon $startDate
     * @param \Carbon\Carbon $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('sent_at', [$startDate, $endDate]);
    }

    /**
     * Check if the email was sent successfully.
     *
     * @return bool
     */
    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    /**
     * Check if the email failed.
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Create a log entry for a sent email.
     *
     * @param string $to
     * @param string $subject
     * @param string|null $templateType
     * @param int|null $userId
     * @param string|null $userType
     * @return self
     */
    public static function logSent(
        string $to,
        string $subject,
        ?string $templateType = null,
        ?int $userId = null,
        ?string $userType = null
    ): self {
        return static::create([
            'to' => $to,
            'subject' => $subject,
            'template_type' => $templateType,
            'status' => 'sent',
            'sent_at' => now(),
            'user_id' => $userId,
            'user_type' => $userType,
        ]);
    }

    /**
     * Create a log entry for a failed email.
     *
     * @param string $to
     * @param string $subject
     * @param string $error
     * @param string|null $templateType
     * @param int|null $userId
     * @param string|null $userType
     * @return self
     */
    public static function logFailed(
        string $to,
        string $subject,
        string $error,
        ?string $templateType = null,
        ?int $userId = null,
        ?string $userType = null
    ): self {
        return static::create([
            'to' => $to,
            'subject' => $subject,
            'template_type' => $templateType,
            'status' => 'failed',
            'sent_at' => now(),
            'error_message' => $error,
            'user_id' => $userId,
            'user_type' => $userType,
        ]);
    }
}
