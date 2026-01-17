<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'content',
        'placeholders',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'placeholders' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get template by slug.
     */
    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->where('is_active', true)->first();
    }

    /**
     * Render template with data.
     */
    public function render(array $data): string
    {
        $content = $this->content;
        
        foreach ($data as $key => $value) {
            $content = str_replace("{{$key}}", $value, $content);
        }
        
        return $content;
    }

    /**
     * Scope active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
