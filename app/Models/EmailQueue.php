<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailQueue extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_queue';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'to',
        'subject',
        'body_html',
        'body_text',
        'template_type',
        'variables',
        'status',
        'scheduled_at',
        'sent_at',
        'retry_count',
        'error_message',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'variables' => 'array',
            'scheduled_at' => 'datetime',
            'sent_at' => 'datetime',
            'retry_count' => 'integer',
        ];
    }

    /**
     * Maximum number of retry attempts.
     *
     * @var int
     */
    const MAX_RETRIES = 3;

    /**
     * Get the template associated with this queue entry.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'template_type', 'type');
    }

    /**
     * Mark the email as sent.
     *
     * @return void
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
            'error_message' => null,
        ]);
    }

    /**
     * Mark the email as failed with error message.
     *
     * @param string $error
     * @return void
     */
    public function markAsFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
        ]);
    }

    /**
     * Increment the retry count.
     *
     * @return void
     */
    public function incrementRetry(): void
    {
        $this->increment('retry_count');
    }

    /**
     * Check if the email should be retried.
     *
     * @return bool
     */
    public function shouldRetry(): bool
    {
        return $this->status === 'failed' && $this->retry_count < self::MAX_RETRIES;
    }

    /**
     * Check if the email is ready to be sent.
     *
     * @return bool
     */
    public function isReadyToSend(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        // If no scheduled time, it's ready
        if (!$this->scheduled_at) {
            return true;
        }

        // Check if scheduled time has passed
        return $this->scheduled_at->isPast();
    }

    /**
     * Get the delay for the next retry attempt (exponential backoff).
     *
     * @return int Delay in seconds
     */
    public function getRetryDelay(): int
    {
        // Exponential backoff: 5 minutes, 15 minutes, 45 minutes
        $delays = [300, 900, 2700]; // in seconds
        $index = min($this->retry_count, count($delays) - 1);
        
        return $delays[$index];
    }

    /**
     * Reset the queue entry for retry.
     *
     * @return void
     */
    public function resetForRetry(): void
    {
        $this->update([
            'status' => 'pending',
            'scheduled_at' => now()->addSeconds($this->getRetryDelay()),
            'error_message' => null,
        ]);
    }

    /**
     * Scope a query to only include pending emails.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include sent emails.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope a query to only include failed emails.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope a query to only include emails ready to be sent.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReadyToSend($query)
    {
        return $query->where('status', 'pending')
            ->where(function ($q) {
                $q->whereNull('scheduled_at')
                  ->orWhere('scheduled_at', '<=', now());
            });
    }

    /**
     * Scope a query to only include emails that can be retried.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRetryable($query)
    {
        return $query->where('status', 'failed')
            ->where('retry_count', '<', self::MAX_RETRIES);
    }

    /**
     * Scope a query to filter by recipient email.
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
     * Scope a query to filter by template type.
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
     * Scope a query to only include scheduled emails.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeScheduled($query)
    {
        return $query->whereNotNull('scheduled_at');
    }

    /**
     * Check if the email is pending.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the email was sent.
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
     * Check if max retries have been reached.
     *
     * @return bool
     */
    public function hasMaxedRetries(): bool
    {
        return $this->retry_count >= self::MAX_RETRIES;
    }
}
