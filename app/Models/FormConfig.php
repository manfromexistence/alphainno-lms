<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormConfig extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_type',
        'field_name',
        'visible',
        'order',
        'role_visibility',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'visible' => 'boolean',
            'role_visibility' => 'array',
        ];
    }

    /**
     * Get visible fields for a specific form type.
     *
     * @param string $formType
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getVisibleFields(string $formType): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('form_type', $formType)
            ->where('visible', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Get visible fields for a specific form type and role.
     *
     * @param string $formType
     * @param string $role
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getVisibleFieldsForRole(string $formType, string $role): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('form_type', $formType)
            ->where('visible', true)
            ->where(function ($query) use ($role) {
                $query->whereNull('role_visibility')
                    ->orWhereJsonContains('role_visibility', $role);
            })
            ->orderBy('order')
            ->get();
    }

    /**
     * Get all fields for a specific form type.
     *
     * @param string $formType
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFieldsForForm(string $formType): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('form_type', $formType)
            ->orderBy('order')
            ->get();
    }

    /**
     * Check if a role can see this field.
     *
     * @param string $role
     * @return bool
     */
    public function isVisibleForRole(string $role): bool
    {
        if (!$this->visible) {
            return false;
        }

        if (empty($this->role_visibility)) {
            return true;
        }

        return in_array($role, $this->role_visibility);
    }

    /**
     * Scope a query to only include configs for a given form type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $formType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForForm($query, string $formType)
    {
        return $query->where('form_type', $formType);
    }

    /**
     * Scope a query to only include visible fields.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }
}
