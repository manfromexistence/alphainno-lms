<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'inventory_item_id',
        'type',
        'quantity',
        'unit_price',
        'total_amount',
        'supplier',
        'purpose',
        'transaction_date',
        'notes',
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
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'transaction_date' => 'date',
        ];
    }

    /**
     * Get the inventory item that owns the transaction.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    /**
     * Get the user who created the transaction.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the formatted unit price.
     *
     * @return string
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return number_format($this->unit_price ?? 0, 2);
    }

    /**
     * Get the formatted total amount.
     *
     * @return string
     */
    public function getFormattedTotalAmountAttribute(): string
    {
        return number_format($this->total_amount ?? 0, 2);
    }

    /**
     * Scope a query to only include transactions of a given type.
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
     * Scope a query to only include purchase transactions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePurchases($query)
    {
        return $query->where('type', 'purchase');
    }

    /**
     * Scope a query to only include usage transactions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUsages($query)
    {
        return $query->where('type', 'usage');
    }

    /**
     * Scope a query to only include adjustment transactions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdjustments($query)
    {
        return $query->where('type', 'adjustment');
    }
}
