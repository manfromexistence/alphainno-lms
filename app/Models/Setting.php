<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'value' => 'json',
        ];
    }

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @param string $type
     * @return static
     */
    public static function setValue(string $key, mixed $value, string $group = 'general', string $type = 'string'): static
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type,
            ]
        );
    }

    /**
     * Get all settings by group.
     *
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByGroup(string $group): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('group', $group)->get();
    }

    /**
     * Cast the value based on the type.
     *
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    protected static function castValue(mixed $value, string $type): mixed
    {
        return match ($type) {
            'integer' => (int) $value,
            'boolean' => (bool) $value,
            'json', 'array' => is_array($value) ? $value : json_decode($value, true),
            'text', 'string' => (string) $value,
            default => $value,
        };
    }

    /**
     * Scope a query to only include settings of a given group.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }
}
