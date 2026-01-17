# Dependencies and Configuration Setup

This document describes the dependencies and configuration files set up for the Admin Panel Functional Features.

## Installed Packages

### 1. Laravel Excel (maatwebsite/excel)
- **Version**: ^3.1
- **Purpose**: Excel export functionality for reports (attendance, payment, performance, student)
- **Configuration File**: `config/excel.php`
- **Documentation**: https://docs.laravel-excel.com/

### 2. Laravel DomPDF (barryvdh/laravel-dompdf)
- **Version**: ^3.1
- **Purpose**: PDF export functionality for reports with institution branding
- **Configuration File**: `config/dompdf.php`
- **Documentation**: https://github.com/barryvdh/laravel-dompdf

## Configuration Files

### PDF Configuration (`config/dompdf.php`)

**Paper Settings:**
- Default paper size: A4
- Default orientation: Portrait

**Branding Settings:**
The following institution branding settings are available for PDF exports:
- `institution_name`: Institution name (from `INSTITUTION_NAME` env variable)
- `institution_logo`: Path to institution logo (from `INSTITUTION_LOGO` env variable)
- `institution_address`: Institution address
- `institution_phone`: Institution phone number
- `institution_email`: Institution email
- `institution_website`: Institution website

**Usage in PDF Templates:**
```php
$branding = config('dompdf.options.branding');
$institutionName = $branding['institution_name'];
$logo = $branding['institution_logo'];
```

### SMS Configuration (`config/sms.php`)

**Mock SMS Settings:**
The system uses a mock SMS implementation for testing without requiring an actual SMS gateway.

**Configuration Options:**
- `driver`: SMS driver (default: 'mock')
- `mock.delivery_delay`: Simulated delivery delay in seconds (default: 2)
- `mock.failure_rate`: Percentage of SMS that randomly fail (0-100, default: 10)
- `mock.enable_logging`: Enable logging to Laravel log (default: true)
- `mock.simulate_status_updates`: Simulate status changes (default: true)

**Available Placeholders:**
SMS templates support the following placeholders:
- `{student_name}`: Student's full name
- `{student_id}`: Student's ID number
- `{amount}`: Payment or fee amount
- `{date}`: Date (payment date, exam date, etc.)
- `{balance}`: Current balance
- `{exam_name}`: Name of the exam
- `{score}`: Exam score
- `{grade}`: Exam grade
- `{batch_name}`: Batch name
- `{course_name}`: Course name
- `{institution_name}`: Institution name
- `{institution_phone}`: Institution phone number

**Default Templates:**
Pre-defined templates are available for:
- Payment confirmation
- Payment reminder
- Result notification
- General notification

**Bulk SMS Settings:**
- `batch_size`: Maximum recipients per batch (default: 100)
- `batch_delay`: Delay between batches in seconds (default: 1)

**Retry Settings:**
- `max_attempts`: Maximum retry attempts for failed SMS (default: 3)
- `retry_delay`: Delay between retry attempts in minutes (default: 5)

**Institution Contact Information:**
Used for mobile money payment instructions:
- `institution.name`: Institution name
- `institution.phone`: General phone number
- `institution.bkash_number`: bKash payment number
- `institution.nagad_number`: Nagad payment number

## Environment Variables

The following environment variables have been added to `.env` and `.env.example`:

### SMS Configuration
```env
SMS_DRIVER=mock
SMS_MOCK_DELIVERY_DELAY=2
SMS_MOCK_FAILURE_RATE=10
SMS_MOCK_LOGGING=true
SMS_MOCK_SIMULATE_STATUS=true
SMS_MAX_LENGTH=160
SMS_BULK_BATCH_SIZE=100
SMS_BULK_BATCH_DELAY=1
SMS_RETRY_MAX_ATTEMPTS=3
SMS_RETRY_DELAY=5
```

### Institution Information
```env
INSTITUTION_NAME="Alphainno LMS"
INSTITUTION_ADDRESS=""
INSTITUTION_PHONE=""
INSTITUTION_EMAIL=""
INSTITUTION_WEBSITE=""
INSTITUTION_LOGO="/images/logo.png"
INSTITUTION_BKASH=""
INSTITUTION_NAGAD=""
```

## Usage Examples

### Accessing Configuration in Code

**PDF Branding:**
```php
$branding = config('dompdf.options.branding');
$institutionName = $branding['institution_name'];
```

**SMS Settings:**
```php
$driver = config('sms.driver');
$placeholders = config('sms.placeholders');
$template = config('sms.default_templates.payment_confirmation');
```

**Institution Contact:**
```php
$bkashNumber = config('sms.institution.bkash_number');
$nagadNumber = config('sms.institution.nagad_number');
```

## Next Steps

1. Implement InvoiceService and enhance Invoice model (Task 2)
2. Implement Payment Module core functionality (Task 3)
3. Implement SMS Service and Communication Module (Task 4)
4. Implement Report Service and export functionality (Tasks 6-9)

## Notes

- The mock SMS implementation allows testing without requiring actual SMS gateway integration
- PDF exports will include institution branding automatically when using the configured templates
- Excel exports use Laravel Excel's efficient chunking for large datasets
- All configuration values can be overridden via environment variables for different environments
