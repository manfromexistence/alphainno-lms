<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SmsLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'message',
        'status',
        'type',
        'related_type',
        'related_id',
        'sent_at',
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
     * Get the related model (polymorphic relationship).
     */
    public function related(): MorphTo
    {
        return $this->morphTo('related');
    }

    /**
     * Check if the SMS was sent successfully.
     *
     * @return bool
     */
    public function isSent(): bool
    {
        return in_array($this->status, ['sent', 'delivered']);
    }

    /**
     * Check if the SMS is pending.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the SMS delivery failed.
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if the SMS was delivered.
     *
     * @return bool
     */
    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    /**
     * Mark the SMS as sent.
     *
     * @return bool
     */
    public function markAsSent(): bool
    {
        return $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark the SMS as delivered.
     *
     * @return bool
     */
    public function markAsDelivered(): bool
    {
        return $this->update(['status' => 'delivered']);
    }

    /**
     * Mark the SMS as failed.
     *
     * @return bool
     */
    public function markAsFailed(): bool
    {
        return $this->update(['status' => 'failed']);
    }

    /**
     * Scope a query to only include logs with a given status.
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
     * Scope a query to only include logs of a given type.
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
     * Scope a query to only include pending logs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include failed logs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope a query to only include logs for a specific phone number.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $phone
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForPhone($query, string $phone)
    {
        return $query->where('phone', $phone);
    }
}
