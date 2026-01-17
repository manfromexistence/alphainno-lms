<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation for bulk SMS sending.
 * 
 * Supports two modes:
 * 1. Direct recipients array: Pass an array of phone numbers directly
 * 2. Recipient type selection: Select recipients by batch, course, or other criteria
 * 
 * Validates: Requirements 11.1, 11.2, 11.4, 11.5
 */
class BulkSmsRequest extends FormRequest
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
        return [
            // Message content - required for all bulk SMS
            'message' => ['required', 'string', 'min:1', 'max:500'],

            // Direct recipients array mode (alternative to recipient_type)
            // When provided, this takes precedence over recipient_type selection
            'recipients' => [
                'nullable',
                'array',
                'min:1',
                'required_without:recipient_type',
            ],
            'recipients.*' => [
                'required',
                'string',
                'min:10',
                'max:20',
                'regex:/^[\+]?[0-9\s\-]+$/',
            ],

            // Recipient type selection mode
            'recipient_type' => [
                'nullable',
                'string',
                'in:all,batch,course,custom,students_with_dues',
                'required_without:recipients',
            ],

            // Batch selection - required when recipient_type is 'batch'
            'batch_id' => [
                'required_if:recipient_type,batch',
                'nullable',
                'integer',
                'exists:batches,id',
            ],

            // Course selection - required when recipient_type is 'course'
            'course_id' => [
                'required_if:recipient_type,course',
                'nullable',
                'integer',
                'exists:courses,id',
            ],

            // Custom phone numbers - required when recipient_type is 'custom'
            // Accepts comma-separated phone numbers as a string
            'custom_numbers' => [
                'required_if:recipient_type,custom',
                'nullable',
                'string',
            ],

            // Option to include parent phone numbers
            'include_parents' => ['nullable', 'boolean'],

            // Optional template reference
            'template_id' => ['nullable', 'integer', 'exists:message_templates,id'],
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
            // Message validation messages
            'message.required' => 'Message content is required.',
            'message.min' => 'Message cannot be empty.',
            'message.max' => 'Message cannot exceed 500 characters.',

            // Recipients array validation messages
            'recipients.required_without' => 'Please provide recipients or select a recipient type.',
            'recipients.array' => 'Recipients must be provided as an array.',
            'recipients.min' => 'At least one recipient is required.',
            'recipients.*.required' => 'Each recipient phone number is required.',
            'recipients.*.string' => 'Each recipient must be a valid phone number string.',
            'recipients.*.min' => 'Phone number must be at least 10 characters.',
            'recipients.*.max' => 'Phone number cannot exceed 20 characters.',
            'recipients.*.regex' => 'Invalid phone number format in recipients list.',

            // Recipient type validation messages
            'recipient_type.required_without' => 'Please select a recipient type or provide recipients directly.',
            'recipient_type.in' => 'Invalid recipient type selected.',

            // Batch validation messages
            'batch_id.required_if' => 'Please select a batch when sending to batch recipients.',
            'batch_id.exists' => 'Selected batch does not exist.',

            // Course validation messages
            'course_id.required_if' => 'Please select a course when sending to course recipients.',
            'course_id.exists' => 'Selected course does not exist.',

            // Custom numbers validation messages
            'custom_numbers.required_if' => 'Please enter phone numbers when using custom recipients.',

            // Template validation messages
            'template_id.exists' => 'Selected template does not exist.',
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
            'message' => 'SMS message',
            'recipients' => 'recipients list',
            'recipients.*' => 'recipient phone number',
            'recipient_type' => 'recipient type',
            'batch_id' => 'batch',
            'course_id' => 'course',
            'custom_numbers' => 'phone numbers',
            'include_parents' => 'include parents option',
            'template_id' => 'template',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert include_parents to boolean
        if ($this->has('include_parents')) {
            $this->merge([
                'include_parents' => filter_var($this->include_parents, FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        // Clean up recipients array if provided
        if ($this->has('recipients') && is_array($this->recipients)) {
            $cleanedRecipients = array_values(array_filter(array_map(function ($phone) {
                // Remove spaces and dashes from phone numbers
                return preg_replace('/[\s\-]/', '', trim($phone));
            }, $this->recipients)));

            $this->merge([
                'recipients' => $cleanedRecipients,
            ]);
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Ensure at least one recipient selection method is provided
            if (!$this->filled('recipients') && !$this->filled('recipient_type')) {
                $validator->errors()->add('recipients', 'Please provide recipients or select a recipient type.');
            }
        });
    }
}
