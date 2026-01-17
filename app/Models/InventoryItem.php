<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category',
        'description',
        'quantity',
        'unit',
        'unit_price',
        'low_stock_threshold',
        'location',
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
            'low_stock_threshold' => 'integer',
        ];
    }

    /**
     * Get the transactions for the inventory item.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    /**
     * Check if the item is low on stock.
     *
     * @return bool
     */
    public function isLowStock(): bool
    {
        return $this->quantity < $this->low_stock_threshold;
    }

    /**
     * Get the total value of the inventory item.
     *
     * @return float
     */
    public function getTotalValueAttribute(): float
    {
        return (float) ($this->quantity * $this->unit_price);
    }

    /**
     * Get the formatted unit price.
     *
     * @return string
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return number_format($this->unit_price, 2);
    }

    /**
     * Get the formatted total value.
     *
     * @return string
     */
    public function getFormattedTotalValueAttribute(): string
    {
        return number_format($this->total_value, 2);
    }

    /**
     * Scope a query to only include low stock items.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<', 'low_stock_threshold');
    }

    /**
     * Scope a query to only include items with a given category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
