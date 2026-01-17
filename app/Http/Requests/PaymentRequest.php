<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
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
     * Requirements: 1.1, 1.2, 1.4
     * 
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Student selection - required
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            // Payment amount - must be positive
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999999.99',
            ],

            // Payment method - must be one of the supported methods
            // Requirement 1.1: Support Cash, bKash, Nagad, and Bank Transfer
            'payment_method' => [
                'required',
                'string',
                Rule::in(['cash', 'bkash', 'nagad', 'bank_transfer']),
            ],

            // Payment date - cannot be in the future
            'payment_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],

            // Transaction ID - required for mobile money and bank transfers
            'transaction_id' => [
                'nullable',
                'string',
                'max:255',
                'required_if:payment_method,bkash,nagad,bank_transfer',
            ],

            // Invoice reference - optional, must exist if provided
            'invoice_id' => [
                'nullable',
                'integer',
                'exists:invoices,id',
            ],

            // Notes - optional additional information
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],

            // Mobile number - required for mobile money payments (bKash, Nagad)
            // Requirement 1.2: Mobile money requires phone number
            'mobile_number' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^01[3-9]\d{8}$/',
                'required_if:payment_method,bkash,nagad',
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
            // Student validation messages
            'student_id.required' => 'Please select a student.',
            'student_id.integer' => 'Invalid student selection.',
            'student_id.exists' => 'The selected student does not exist in the system.',

            // Amount validation messages
            'amount.required' => 'Payment amount is required.',
            'amount.numeric' => 'Payment amount must be a valid number.',
            'amount.min' => 'Payment amount must be greater than zero.',
            'amount.max' => 'Payment amount exceeds the maximum allowed value.',

            // Payment method validation messages
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in' => 'Invalid payment method. Please select Cash, bKash, Nagad, or Bank Transfer.',

            // Payment date validation messages
            'payment_date.date' => 'Please enter a valid payment date.',
            'payment_date.before_or_equal' => 'Payment date cannot be in the future.',

            // Transaction ID validation messages
            'transaction_id.required_if' => 'Transaction ID is required for :values payments.',
            'transaction_id.max' => 'Transaction ID cannot exceed 255 characters.',

            // Invoice validation messages
            'invoice_id.exists' => 'The selected invoice does not exist.',

            // Notes validation messages
            'notes.max' => 'Notes cannot exceed 1000 characters.',

            // Mobile number validation messages
            'mobile_number.required_if' => 'Mobile number is required for mobile money payments (bKash/Nagad).',
            'mobile_number.regex' => 'Please enter a valid Bangladeshi mobile number (e.g., 01712345678).',
            'mobile_number.max' => 'Mobile number cannot exceed 20 characters.',
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
            'student_id' => 'student',
            'payment_method' => 'payment method',
            'payment_date' => 'payment date',
            'transaction_id' => 'transaction ID',
            'invoice_id' => 'invoice',
            'mobile_number' => 'mobile number',
        ];
    }

    /**
     * Prepare the data for validation.
     * 
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Set default payment date to today if not provided
        if (!$this->filled('payment_date')) {
            $this->merge([
                'payment_date' => now()->toDateString(),
            ]);
        }

        // Normalize payment method to lowercase
        if ($this->filled('payment_method')) {
            $this->merge([
                'payment_method' => strtolower($this->payment_method),
            ]);
        }

        // Clean mobile number (remove spaces and dashes)
        if ($this->filled('mobile_number')) {
            $this->merge([
                'mobile_number' => preg_replace('/[\s\-]/', '', $this->mobile_number),
            ]);
        }
    }
}
