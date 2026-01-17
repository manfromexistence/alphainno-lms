<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS Service Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration for the SMS service, including mock
    | implementation settings for testing without a real SMS gateway.
    |
    */

    /**
     * SMS Driver
     * 
     * Supported: "mock", "twilio", "nexmo", etc.
     * Use "mock" for testing without actual SMS sending
     */
    'driver' => env('SMS_DRIVER', 'mock'),

    /**
     * Mock SMS Settings
     * 
     * Configuration for the mock SMS implementation used for testing
     */
    'mock' => [
        /**
         * Simulate delivery delay in seconds
         * The mock service will wait this long before marking SMS as delivered
         */
        'delivery_delay' => env('SMS_MOCK_DELIVERY_DELAY', 2),

        /**
         * Failure rate (0-100)
         * Percentage of SMS that should randomly fail for testing purposes
         * Set to 0 to disable random failures
         */
        'failure_rate' => env('SMS_MOCK_FAILURE_RATE', 10),

        /**
         * Enable logging
         * Whether to log mock SMS operations to Laravel log
         */
        'enable_logging' => env('SMS_MOCK_LOGGING', true),

        /**
         * Simulate delivery status updates
         * Whether to simulate status changes (pending -> sent -> delivered)
         */
        'simulate_status_updates' => env('SMS_MOCK_SIMULATE_STATUS', true),
    ],

    /**
     * SMS Templates
     * 
     * Available placeholders for SMS templates:
     * - {student_name}: Student's full name
     * - {student_id}: Student's ID number
     * - {amount}: Payment or fee amount
     * - {date}: Date (payment date, exam date, etc.)
     * - {balance}: Current balance
     * - {exam_name}: Name of the exam
     * - {score}: Exam score
     * - {grade}: Exam grade
     * - {batch_name}: Batch name
     * - {course_name}: Course name
     * - {institution_name}: Institution name
     * - {institution_phone}: Institution phone number
     */
    'placeholders' => [
        'student_name',
        'student_id',
        'amount',
        'date',
        'balance',
        'exam_name',
        'score',
        'grade',
        'batch_name',
        'course_name',
        'institution_name',
        'institution_phone',
    ],

    /**
     * Default SMS Templates
     * 
     * Pre-defined templates for common SMS types
     */
    'default_templates' => [
        'payment_confirmation' => 'Dear {student_name}, your payment of {amount} has been received on {date}. Current balance: {balance}. Thank you! - {institution_name}',
        'payment_reminder' => 'Dear {student_name}, you have an outstanding balance of {balance}. Please make payment at your earliest convenience. - {institution_name}',
        'result_notification' => 'Dear {student_name}, your {exam_name} result: Score: {score}, Grade: {grade}. - {institution_name}',
        'general_notification' => 'Dear {student_name}, this is a notification from {institution_name}.',
    ],

    /**
     * SMS Length Limits
     */
    'max_length' => env('SMS_MAX_LENGTH', 160),

    /**
     * Bulk SMS Settings
     */
    'bulk' => [
        /**
         * Maximum recipients per batch
         * Large bulk operations will be split into batches
         */
        'batch_size' => env('SMS_BULK_BATCH_SIZE', 100),

        /**
         * Delay between batches in seconds
         * Prevents overwhelming the SMS gateway
         */
        'batch_delay' => env('SMS_BULK_BATCH_DELAY', 1),
    ],

    /**
     * Institution Contact Information
     * Used in SMS messages for mobile money payments and general communication
     */
    'institution' => [
        'name' => env('INSTITUTION_NAME', 'Learning Management System'),
        'phone' => env('INSTITUTION_PHONE', ''),
        'bkash_number' => env('INSTITUTION_BKASH', ''),
        'nagad_number' => env('INSTITUTION_NAGAD', ''),
    ],

    /**
     * SMS Retry Settings
     */
    'retry' => [
        /**
         * Maximum retry attempts for failed SMS
         */
        'max_attempts' => env('SMS_RETRY_MAX_ATTEMPTS', 3),

        /**
         * Delay between retry attempts in minutes
         */
        'retry_delay' => env('SMS_RETRY_DELAY', 5),
    ],

];
