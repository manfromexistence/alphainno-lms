<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailTemplate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'subject',
        'body_html',
        'body_text',
        'variables',
        'is_active',
        'is_default',
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
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    /**
     * Get the email queue entries that use this template.
     */
    public function queueEntries(): HasMany
    {
        return $this->hasMany(EmailQueue::class, 'template_type', 'type');
    }

    /**
     * Get the email logs that use this template.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(EmailLog::class, 'template_type', 'type');
    }

    /**
     * Render the template with provided variables.
     *
     * @param array $variables
     * @return array ['html' => string, 'text' => string|null, 'subject' => string]
     */
    public function renderWithVariables(array $variables): array
    {
        $html = $this->body_html;
        $text = $this->body_text;
        $subject = $this->subject;

        // Replace variables in HTML body
        foreach ($variables as $key => $value) {
            $placeholder = '{' . $key . '}';
            $html = str_replace($placeholder, $value, $html);
            
            if ($text) {
                $text = str_replace($placeholder, $value, $text);
            }
            
            $subject = str_replace($placeholder, $value, $subject);
        }

        return [
            'html' => $html,
            'text' => $text,
            'subject' => $subject,
        ];
    }

    /**
     * Get the list of available variables for this template.
     *
     * @return array
     */
    public function getAvailableVariables(): array
    {
        return $this->variables ?? [];
    }

    /**
     * Extract variables from template content.
     *
     * @return array
     */
    public function extractVariablesFromContent(): array
    {
        $content = $this->body_html . ' ' . $this->body_text . ' ' . $this->subject;
        preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $content, $matches);
        
        return array_unique($matches[1] ?? []);
    }

    /**
     * Scope a query to only include active templates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include default templates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope a query to filter by template type.
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
     * Get the default template for a specific type.
     *
     * @param string $type
     * @return self|null
     */
    public static function getDefaultForType(string $type): ?self
    {
        return static::where('type', $type)
            ->where('is_active', true)
            ->where('is_default', true)
            ->first();
    }

    /**
     * Get an active template by type (default or first available).
     *
     * @param string $type
     * @return self|null
     */
    public static function getActiveForType(string $type): ?self
    {
        // Try to get default template first
        $template = static::getDefaultForType($type);
        
        // If no default, get any active template of this type
        if (!$template) {
            $template = static::where('type', $type)
                ->where('is_active', true)
                ->first();
        }
        
        return $template;
    }

    /**
     * Check if this template can be deleted.
     * A template cannot be deleted if it's the only template for its type.
     *
     * @return bool
     */
    public function canBeDeleted(): bool
    {
        $count = static::where('type', $this->type)->count();
        return $count > 1;
    }

    /**
     * Validate template variables against provided data.
     *
     * @param array $data
     * @return array Missing variables
     */
    public function validateVariables(array $data): array
    {
        $required = $this->extractVariablesFromContent();
        $provided = array_keys($data);
        
        return array_diff($required, $provided);
    }
}
