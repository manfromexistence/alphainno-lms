<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request validation for single SMS sending.
 * 
 * Validates: Requirements 4.1, 4.3, 10.1, 10.2
 */
class SendSmsRequest extends FormRequest
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
            'phone' => ['required', 'string', 'min:10', 'max:20', 'regex:/^[\+]?[0-9\s\-]+$/'],
            'message' => ['required', 'string', 'min:1', 'max:500'],
            'template_id' => ['nullable', 'integer', 'exists:message_templates,id'],
            'type' => ['nullable', 'string', 'in:general,payment,result,attendance,reminder,bulk'],
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
            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Phone number must be at least 10 characters.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'phone.regex' => 'Please enter a valid phone number.',
            'message.required' => 'Message content is required.',
            'message.min' => 'Message cannot be empty.',
            'message.max' => 'Message cannot exceed 500 characters.',
            'template_id.exists' => 'Selected template does not exist.',
            'type.in' => 'Invalid SMS type selected.',
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
            'phone' => 'phone number',
            'message' => 'SMS message',
            'template_id' => 'template',
            'type' => 'SMS type',
        ];
    }
}
