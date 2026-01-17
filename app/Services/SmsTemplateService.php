<?php

namespace App\Services;

use App\Models\MessageTemplate;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Exception;

/**
 * Service for managing SMS templates with placeholder support.
 * 
 * Handles CRUD operations for SMS templates and provides
 * placeholder replacement functionality for dynamic content.
 * 
 * Validates: Requirements 12.1, 12.2, 12.3, 12.4, 12.5
 */
class SmsTemplateService
{
    /**
     * Available placeholders for SMS templates.
     * 
     * @var array<string, string>
     */
    protected array $availablePlaceholders = [
        '{student_name}' => 'Student\'s full name',
        '{student_id}' => 'Student ID number',
        '{guardian_name}' => 'Guardian/Parent name',
        '{batch_name}' => 'Batch/Class name',
        '{course_name}' => 'Course name',
        '{amount}' => 'Payment amount',
        '{due_amount}' => 'Outstanding due amount',
        '{balance}' => 'Current balance',
        '{payment_date}' => 'Payment date',
        '{due_date}' => 'Due date',
        '{receipt_number}' => 'Receipt/Invoice number',
        '{exam_name}' => 'Exam name',
        '{marks}' => 'Marks obtained',
        '{total_marks}' => 'Total marks',
        '{grade}' => 'Grade achieved',
        '{percentage}' => 'Percentage score',
        '{attendance_percentage}' => 'Attendance percentage',
        '{date}' => 'Current date',
        '{institution_name}' => 'Institution name',
        '{phone}' => 'Contact phone number',
    ];

    /**
     * Create a new SMS template.
     * 
     * @param array $data Template data (name, content, description, is_active)
     * @return MessageTemplate The created template
     * @throws Exception If template creation fails
     * 
     * Validates: Requirements 12.1
     */
    public function create(array $data): MessageTemplate
    {
        try {
            // Generate slug from name if not provided
            $slug = $data['slug'] ?? Str::slug($data['name']);
            
            // Ensure unique slug
            $slug = $this->ensureUniqueSlug($slug);
            
            // Extract placeholders from content
            $placeholders = $this->extractPlaceholders($data['content'] ?? '');
            
            return MessageTemplate::create([
                'name' => $data['name'],
                'slug' => $slug,
                'type' => 'sms',
                'content' => $data['content'] ?? '',
                'placeholders' => $placeholders,
                'is_active' => $data['is_active'] ?? true,
            ]);
        } catch (QueryException $e) {
            Log::error('Database error creating SMS template', [
                'name' => $data['name'] ?? 'unknown',
                'error' => $e->getMessage(),
            ]);
            throw new Exception('Unable to create SMS template. Please try again.');
        } catch (Exception $e) {
            Log::error('Failed to create SMS template', [
                'name' => $data['name'] ?? 'unknown',
                'error' => $e->getMessage(),
            ]);
            throw new Exception('Failed to create SMS template: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing SMS template.
     * 
     * @param MessageTemplate $template The template to update
     * @param array $data Updated template data
     * @return MessageTemplate The updated template
     * @throws Exception If template update fails
     * 
     * Validates: Requirements 12.3
     */
    public function update(MessageTemplate $template, array $data): MessageTemplate
    {
        try {
            $updateData = [];
            
            if (isset($data['name'])) {
                $updateData['name'] = $data['name'];
                
                // Update slug if name changed and no custom slug provided
                if (!isset($data['slug'])) {
                    $newSlug = Str::slug($data['name']);
                    if ($newSlug !== $template->slug) {
                        $updateData['slug'] = $this->ensureUniqueSlug($newSlug, $template->id);
                    }
                }
            }
            
            if (isset($data['slug'])) {
                $updateData['slug'] = $this->ensureUniqueSlug($data['slug'], $template->id);
            }
            
            if (isset($data['content'])) {
                $updateData['content'] = $data['content'];
                $updateData['placeholders'] = $this->extractPlaceholders($data['content']);
            }
            
            if (isset($data['is_active'])) {
                $updateData['is_active'] = $data['is_active'];
            }
            
            $template->update($updateData);
            
            return $template->fresh();
        } catch (QueryException $e) {
            Log::error('Database error updating SMS template', [
                'template_id' => $template->id,
                'error' => $e->getMessage(),
            ]);
            throw new Exception('Unable to update SMS template. Please try again.');
        } catch (Exception $e) {
            Log::error('Failed to update SMS template', [
                'template_id' => $template->id,
                'error' => $e->getMessage(),
            ]);
            throw new Exception('Failed to update SMS template: ' . $e->getMessage());
        }
    }

    /**
     * Delete an SMS template.
     * 
     * @param MessageTemplate $template The template to delete
     * @return bool Whether the deletion was successful
     * @throws Exception If template deletion fails
     * 
     * Validates: Requirements 12.4
     */
    public function delete(MessageTemplate $template): bool
    {
        try {
            return $template->delete();
        } catch (QueryException $e) {
            Log::error('Database error deleting SMS template', [
                'template_id' => $template->id,
                'error' => $e->getMessage(),
            ]);
            throw new Exception('Unable to delete SMS template. Please try again.');
        } catch (Exception $e) {
            Log::error('Failed to delete SMS template', [
                'template_id' => $template->id,
                'error' => $e->getMessage(),
            ]);
            throw new Exception('Failed to delete SMS template: ' . $e->getMessage());
        }
    }

    /**
     * Get all SMS templates.
     * 
     * @param bool $activeOnly Whether to return only active templates
     * @return Collection Collection of MessageTemplate models
     */
    public function getAll(bool $activeOnly = false): Collection
    {
        try {
            $query = MessageTemplate::where('type', 'sms');
            
            if ($activeOnly) {
                $query->where('is_active', true);
            }
            
            return $query->orderBy('name')->get();
        } catch (QueryException $e) {
            Log::error('Database error retrieving SMS templates', [
                'active_only' => $activeOnly,
                'error' => $e->getMessage(),
            ]);
            return collect();
        } catch (Exception $e) {
            Log::error('Failed to retrieve SMS templates', [
                'error' => $e->getMessage(),
            ]);
            return collect();
        }
    }

    /**
     * Get a template by ID.
     * 
     * @param int $id Template ID
     * @return MessageTemplate|null The template or null if not found
     */
    public function find(int $id): ?MessageTemplate
    {
        try {
            return MessageTemplate::where('type', 'sms')->find($id);
        } catch (QueryException $e) {
            Log::error('Database error finding SMS template', [
                'template_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return null;
        } catch (Exception $e) {
            Log::error('Failed to find SMS template', [
                'template_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get a template by slug.
     * 
     * @param string $slug Template slug
     * @return MessageTemplate|null The template or null if not found
     */
    public function findBySlug(string $slug): ?MessageTemplate
    {
        try {
            return MessageTemplate::where('type', 'sms')
                ->where('slug', $slug)
                ->first();
        } catch (QueryException $e) {
            Log::error('Database error finding SMS template by slug', [
                'slug' => $slug,
                'error' => $e->getMessage(),
            ]);
            return null;
        } catch (Exception $e) {
            Log::error('Failed to find SMS template by slug', [
                'slug' => $slug,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Replace placeholders in a template with actual values.
     * 
     * Supports both template string and MessageTemplate model.
     * Placeholders use the format {placeholder_name}.
     * 
     * @param string|MessageTemplate $template Template content or model
     * @param array $data Key-value pairs for placeholder replacement
     * @return string The template with placeholders replaced
     * 
     * Validates: Requirements 12.2, 12.5
     */
    public function replacePlaceholders(string|MessageTemplate $template, array $data): string
    {
        // Get content from template model if provided
        $content = $template instanceof MessageTemplate 
            ? $template->content 
            : $template;
        
        // Replace each placeholder with its corresponding value
        foreach ($data as $key => $value) {
            // Support both {key} and key formats in data array
            $placeholder = Str::startsWith($key, '{') ? $key : '{' . $key . '}';
            $content = str_replace($placeholder, (string) $value, $content);
        }
        
        return $content;
    }

    /**
     * Get list of available placeholders with descriptions.
     * 
     * @return array<string, string> Placeholder => Description mapping
     * 
     * Validates: Requirements 12.2
     */
    public function getAvailablePlaceholders(): array
    {
        return $this->availablePlaceholders;
    }

    /**
     * Get placeholder keys only (without descriptions).
     * 
     * @return array<string> List of placeholder keys
     */
    public function getPlaceholderKeys(): array
    {
        return array_keys($this->availablePlaceholders);
    }

    /**
     * Extract placeholders from template content.
     * 
     * @param string $content Template content
     * @return array List of placeholders found in the content
     */
    public function extractPlaceholders(string $content): array
    {
        preg_match_all('/\{([a-z_]+)\}/', $content, $matches);
        
        return array_unique($matches[0] ?? []);
    }

    /**
     * Validate that all placeholders in content are valid.
     * 
     * @param string $content Template content to validate
     * @return array Array with 'valid' boolean and 'invalid_placeholders' array
     */
    public function validatePlaceholders(string $content): array
    {
        $usedPlaceholders = $this->extractPlaceholders($content);
        $validPlaceholders = array_keys($this->availablePlaceholders);
        
        $invalidPlaceholders = array_diff($usedPlaceholders, $validPlaceholders);
        
        return [
            'valid' => empty($invalidPlaceholders),
            'invalid_placeholders' => array_values($invalidPlaceholders),
            'used_placeholders' => $usedPlaceholders,
        ];
    }

    /**
     * Render a template with data (alias for replacePlaceholders).
     * 
     * @param MessageTemplate $template The template to render
     * @param array $data Data for placeholder replacement
     * @return string The rendered message
     */
    public function render(MessageTemplate $template, array $data): string
    {
        return $this->replacePlaceholders($template, $data);
    }

    /**
     * Preview a template with sample data.
     * 
     * @param string|MessageTemplate $template Template content or model
     * @return string The template with sample values
     */
    public function preview(string|MessageTemplate $template): string
    {
        $sampleData = [
            'student_name' => 'John Doe',
            'student_id' => 'STU-2024-001',
            'guardian_name' => 'Jane Doe',
            'batch_name' => 'Batch A - 2024',
            'course_name' => 'Mathematics',
            'amount' => '5,000',
            'due_amount' => '2,500',
            'balance' => '2,500',
            'payment_date' => date('d M Y'),
            'due_date' => date('d M Y', strtotime('+7 days')),
            'receipt_number' => 'RCP-2024-0001',
            'exam_name' => 'Mid-Term Exam',
            'marks' => '85',
            'total_marks' => '100',
            'grade' => 'A',
            'percentage' => '85%',
            'attendance_percentage' => '92%',
            'date' => date('d M Y'),
            'institution_name' => 'Sample Institution',
            'phone' => '+880 1234-567890',
        ];
        
        return $this->replacePlaceholders($template, $sampleData);
    }

    /**
     * Ensure a slug is unique by appending a number if necessary.
     * 
     * @param string $slug The base slug
     * @param int|null $excludeId ID to exclude from uniqueness check (for updates)
     * @return string A unique slug
     */
    protected function ensureUniqueSlug(string $slug, ?int $excludeId = null): string
    {
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Check if a slug already exists.
     * 
     * @param string $slug The slug to check
     * @param int|null $excludeId ID to exclude from check
     * @return bool Whether the slug exists
     */
    protected function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = MessageTemplate::where('slug', $slug)->where('type', 'sms');
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Get predefined template types for common use cases.
     * 
     * @return array<string, array> Template type definitions
     */
    public function getPredefinedTemplates(): array
    {
        return [
            'payment_confirmation' => [
                'name' => 'Payment Confirmation',
                'content' => 'Dear {guardian_name}, payment of Tk.{amount} received for {student_name}. Receipt: {receipt_number}. Balance: Tk.{balance}. Thank you!',
            ],
            'payment_reminder' => [
                'name' => 'Payment Reminder',
                'content' => 'Dear {guardian_name}, this is a reminder that Tk.{due_amount} is due for {student_name} by {due_date}. Please pay at your earliest convenience.',
            ],
            'exam_result' => [
                'name' => 'Exam Result Notification',
                'content' => 'Dear {guardian_name}, {student_name} scored {marks}/{total_marks} ({grade}) in {exam_name}. Keep up the good work!',
            ],
            'attendance_alert' => [
                'name' => 'Attendance Alert',
                'content' => 'Dear {guardian_name}, {student_name}\'s attendance is {attendance_percentage}. Please ensure regular attendance.',
            ],
            'general_announcement' => [
                'name' => 'General Announcement',
                'content' => 'Dear {guardian_name}, this is an announcement from {institution_name} regarding {student_name}.',
            ],
        ];
    }

    /**
     * Create predefined templates if they don't exist.
     * 
     * @return int Number of templates created
     */
    public function createPredefinedTemplates(): int
    {
        $created = 0;
        
        try {
            foreach ($this->getPredefinedTemplates() as $slug => $data) {
                if (!$this->findBySlug($slug)) {
                    $this->create([
                        'name' => $data['name'],
                        'slug' => $slug,
                        'content' => $data['content'],
                        'is_active' => true,
                    ]);
                    $created++;
                }
            }
        } catch (Exception $e) {
            Log::error('Failed to create predefined templates', [
                'created_so_far' => $created,
                'error' => $e->getMessage(),
            ]);
        }
        
        return $created;
    }
}
