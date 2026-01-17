<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request validation for SMS template management (create/update).
 * 
 * Validates: Requirements 12.1, 12.2, 12.3, 12.4
 */
class TemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $templateId = $this->route('template')?->id;

        return [
            // Template name - required, max 100 characters
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],

            // Template slug - optional, unique identifier
            'slug' => [
                'nullable',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('message_templates', 'slug')->ignore($templateId),
            ],

            // Template content - required, max 1000 characters
            // Supports placeholders like {student_name}, {amount}, {date}
            'content' => [
                'required',
                'string',
                'min:1',
                'max:1000',
            ],

            // Placeholders - optional array of placeholder names
            // These are automatically extracted from content if not provided
            'placeholders' => [
                'nullable',
                'array',
            ],
            'placeholders.*' => [
                'string',
                'max:50',
                'regex:/^\{?\w+\}?$/',
            ],

            // Template type - defaults to 'sms'
            'type' => [
                'nullable',
                'string',
                'in:sms,email,notification',
            ],

            // Description - optional description of the template
            'description' => [
                'nullable',
                'string',
                'max:500',
            ],

            // Active status - whether the template is available for use
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Name validation messages
            'name.required' => 'Template name is required.',
            'name.string' => 'Template name must be a valid string.',
            'name.min' => 'Template name must be at least 2 characters.',
            'name.max' => 'Template name cannot exceed 100 characters.',

            // Slug validation messages
            'slug.max' => 'Template slug cannot exceed 100 characters.',
            'slug.alpha_dash' => 'Template slug may only contain letters, numbers, dashes, and underscores.',
            'slug.unique' => 'This template slug is already in use.',

            // Content validation messages
            'content.required' => 'Template content is required.',
            'content.string' => 'Template content must be a valid string.',
            'content.min' => 'Template content cannot be empty.',
            'content.max' => 'Template content cannot exceed 1000 characters.',

            // Placeholders validation messages
            'placeholders.array' => 'Placeholders must be provided as an array.',
            'placeholders.*.string' => 'Each placeholder must be a valid string.',
            'placeholders.*.max' => 'Placeholder name cannot exceed 50 characters.',
            'placeholders.*.regex' => 'Invalid placeholder format. Use format like {placeholder_name}.',

            // Type validation messages
            'type.in' => 'Invalid template type. Must be sms, email, or notification.',

            // Description validation messages
            'description.max' => 'Description cannot exceed 500 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'template name',
            'slug' => 'template slug',
            'content' => 'template content',
            'placeholders' => 'placeholders',
            'placeholders.*' => 'placeholder',
            'type' => 'template type',
            'description' => 'description',
            'is_active' => 'active status',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert is_active to boolean
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        // Generate slug from name if not provided
        if (!$this->filled('slug') && $this->filled('name')) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->name),
            ]);
        }

        // Normalize placeholders format (ensure they have curly braces)
        if ($this->has('placeholders') && is_array($this->placeholders)) {
            $normalizedPlaceholders = array_map(function ($placeholder) {
                $placeholder = trim($placeholder);
                // Add curly braces if not present
                if (!str_starts_with($placeholder, '{')) {
                    $placeholder = '{' . $placeholder;
                }
                if (!str_ends_with($placeholder, '}')) {
                    $placeholder = $placeholder . '}';
                }
                return $placeholder;
            }, $this->placeholders);

            $this->merge([
                'placeholders' => array_values(array_filter($normalizedPlaceholders)),
            ]);
        }
    }

    /**
     * Get the validated data with auto-extracted placeholders.
     *
     * @return array
     */
    public function validatedWithPlaceholders(): array
    {
        $validated = $this->validated();

        // Auto-extract placeholders from content if not provided
        if (empty($validated['placeholders']) && !empty($validated['content'])) {
            preg_match_all('/\{(\w+)\}/', $validated['content'], $matches);
            if (!empty($matches[0])) {
                $validated['placeholders'] = $matches[0];
            }
        }

        return $validated;
    }
}
