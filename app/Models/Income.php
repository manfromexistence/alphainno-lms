<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    use HasFactory;

    /**
     * The income categories.
     */
    public const CATEGORIES = [
        'admission',
        'tuition',
        'materials',
        'other',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category',
        'amount',
        'description',
        'income_date',
        'student_id',
        'payment_id',
        'reference',
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
            'amount' => 'decimal:2',
            'income_date' => 'date',
        ];
    }

    /**
     * Get the student associated with the income.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the payment associated with the income.
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the user who created the income record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the formatted amount.
     *
     * @return string
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2);
    }

    /**
     * Scope a query to only include incomes with a given category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to only include incomes within a date range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon $startDate
     * @param \Carbon\Carbon $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('income_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to filter by income date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('income_date', $date);
    }

    /**
     * Scope a query to only include incomes for a specific student.
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
     * Scope a query to only include incomes linked to payments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFromPayments($query)
    {
        return $query->whereNotNull('payment_id');
    }

    /**
     * Scope a query to only include manual income entries.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeManual($query)
    {
        return $query->whereNull('payment_id');
    }
}
