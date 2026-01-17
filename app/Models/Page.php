<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'meta_title',
        'meta_description',
        'content',
        'sections',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'sections' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->where('is_active', true)->first();
    }

    public function getSection(string $key, $default = null)
    {
        return $this->sections[$key] ?? $default;
    }

    public function getContent(string $key, $default = null)
    {
        return $this->content[$key] ?? $default;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
