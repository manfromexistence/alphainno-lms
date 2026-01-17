# Design Document: LMS Missing Features

## Overview

This design document outlines the technical implementation for adding missing features to an existing Laravel 11 LMS system. The implementation follows the existing architecture patterns including service classes, controllers, Blade templates, and Tailwind CSS styling.

The design is organized into three priority tiers:
- **High Priority**: Parent Portal, Email Notification Service, Certificate Generation System
- **Medium Priority**: Advanced Analytics, PWA Optimization, Multi-Language Support, Advanced Communication
- **Low Priority**: SMS Modem Support, Biometric Attendance, Advanced Exam Features, Document Management, Third-Party API

All new features will integrate seamlessly with existing functionality including SMS notifications (SmsService), payment processing (PaymentService), student portal, admin panel with RBAC, report generation, inventory management, and activity logging.

## Architecture

### System Architecture Overview

The LMS follows a layered architecture pattern:

```
┌─────────────────────────────────────────────────────────┐
│                    Presentation Layer                    │
│              (Blade Templates + Tailwind CSS)            │
└─────────────────────────────────────────────────────────┘
                            │
┌─────────────────────────────────────────────────────────┐
│                    Controller Layer                      │
│     (ParentController, EmailController, etc.)           │
└─────────────────────────────────────────────────────────┘
                            │
┌─────────────────────────────────────────────────────────┐
│                     Service Layer                        │
│  (EmailService, CertificateService, AnalyticsService)   │
└─────────────────────────────────────────────────────────┘
                            │
┌─────────────────────────────────────────────────────────┐
│                      Model Layer                         │
│     (Eloquent Models with Relationships)                │
└─────────────────────────────────────────────────────────┘
                            │
┌─────────────────────────────────────────────────────────┐
│                     Database Layer                       │
│              (MySQL with Migrations)                     │
└─────────────────────────────────────────────────────────┘
```

### Service Pattern

All business logic will be encapsulated in service classes following the existing pattern:
- Services handle complex operations and business rules
- Controllers remain thin, delegating to services
- Services are injected via Laravel's dependency injection
- Services return consistent response structures

### Integration Points

New features integrate with existing services:
- **EmailService** works alongside existing SmsService
- **ParentService** integrates with StudentService and PaymentService
- **CertificateService** integrates with CourseService and StudentService
- **AnalyticsService** aggregates data from all existing services


## Components and Interfaces

### 1. Parent Portal Module

#### ParentController
```php
class ParentController extends Controller
{
    public function __construct(
        private ParentService $parentService,
        private StudentService $studentService,
        private PaymentService $paymentService
    ) {}
    
    public function dashboard(): View
    public function linkStudent(Request $request): RedirectResponse
    public function unlinkStudent(Student $student): RedirectResponse
    public function viewChild(Student $student): View
    public function payments(): View
    public function makePayment(Request $request): RedirectResponse
    public function messages(): View
    public function sendMessage(Request $request): RedirectResponse
}
```

#### ParentService
```php
class ParentService
{
    public function registerParent(array $data): Parent
    public function linkStudentToParent(Parent $parent, string $studentIdentifier): bool
    public function unlinkStudent(Parent $parent, Student $student): bool
    public function getChildrenOverview(Parent $parent): Collection
    public function getChildAttendance(Student $student, ?Carbon $startDate, ?Carbon $endDate): array
    public function getChildPayments(Student $student): Collection
    public function getChildResults(Student $student): Collection
    public function getChildSchedule(Student $student): Collection
    public function updateNotificationPreferences(Parent $parent, array $preferences): bool
}
```

#### Parent Model
```php
class Parent extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'phone', 'password', 
        'notification_preferences', 'email_verified_at', 'phone_verified_at'
    ];
    
    protected $casts = [
        'notification_preferences' => 'array',
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];
    
    public function students(): BelongsToMany
    public function messages(): HasMany
    public function notifications(): MorphMany
}
```

#### ParentStudent Pivot Model
```php
class ParentStudent extends Pivot
{
    protected $table = 'parent_student';
    
    protected $fillable = [
        'parent_id', 'student_id', 'relationship_type', 
        'approved_by', 'approved_at', 'status'
    ];
    
    protected $casts = [
        'approved_at' => 'datetime',
    ];
}
```

### 2. Email Notification Service Module

#### EmailService
```php
class EmailService
{
    public function __construct(
        private EmailTemplateRepository $templateRepository,
        private EmailLogRepository $logRepository
    ) {}
    
    public function send(string $to, string $templateType, array $variables): bool
    public function sendBulk(array $recipients, string $templateType, array $variables): array
    public function queueEmail(string $to, string $templateType, array $variables, ?Carbon $sendAt = null): EmailQueue
    public function retryFailed(EmailLog $log): bool
    public function getTemplate(string $type): EmailTemplate
    public function renderTemplate(EmailTemplate $template, array $variables): string
    public function validateEmail(string $email): bool
    public function sendPaymentConfirmation(Payment $payment): bool
    public function sendResultNotification(ExamResult $result): bool
    public function sendAttendanceAlert(Student $student, array $attendanceData): bool
    public function sendAnnouncement(Announcement $announcement, array $recipients): array
    public function sendAdmissionConfirmation(Student $student): bool
}
```

#### EmailTemplate Model
```php
class EmailTemplate extends Model
{
    protected $fillable = [
        'name', 'type', 'subject', 'body_html', 'body_text',
        'variables', 'is_active', 'is_default'
    ];
    
    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];
    
    public function renderWithVariables(array $variables): string
    public function getAvailableVariables(): array
}
```

#### EmailQueue Model
```php
class EmailQueue extends Model
{
    protected $fillable = [
        'to', 'subject', 'body_html', 'body_text',
        'template_type', 'variables', 'status', 
        'scheduled_at', 'sent_at', 'retry_count', 'error_message'
    ];
    
    protected $casts = [
        'variables' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];
    
    public function markAsSent(): void
    public function markAsFailed(string $error): void
    public function incrementRetry(): void
    public function shouldRetry(): bool
}
```

#### EmailLog Model
```php
class EmailLog extends Model
{
    protected $fillable = [
        'to', 'subject', 'template_type', 'status',
        'sent_at', 'error_message', 'user_id', 'user_type'
    ];
    
    protected $casts = [
        'sent_at' => 'datetime',
    ];
    
    public function user(): MorphTo
}
```

### 3. Certificate Generation System Module

#### CertificateService
```php
class CertificateService
{
    public function __construct(
        private CertificateTemplateRepository $templateRepository,
        private PdfGenerator $pdfGenerator
    ) {}
    
    public function generateCertificate(Student $student, Course $course, ?CertificateTemplate $template = null): Certificate
    public function generateBulkCertificates(Collection $students, Course $course): Collection
    public function generatePdf(Certificate $certificate): string
    public function generateVerificationCode(): string
    public function verifyCertificate(string $code): ?Certificate
    public function revokeCertificate(Certificate $certificate, string $reason): bool
    public function getCertificateTemplate(string $type): CertificateTemplate
    public function renderCertificate(Certificate $certificate): string
}
```

#### Certificate Model
```php
class Certificate extends Model
{
    protected $fillable = [
        'student_id', 'course_id', 'template_id', 'certificate_number',
        'verification_code', 'issued_at', 'issued_by', 'grade',
        'status', 'revoked_at', 'revocation_reason', 'pdf_path'
    ];
    
    protected $casts = [
        'issued_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];
    
    public function student(): BelongsTo
    public function course(): BelongsTo
    public function template(): BelongsTo
    public function issuer(): BelongsTo
    public function isValid(): bool
    public function revoke(string $reason): void
}
```

#### CertificateTemplate Model
```php
class CertificateTemplate extends Model
{
    protected $fillable = [
        'name', 'type', 'background_image', 'logo_image',
        'signature_image', 'layout_config', 'variables',
        'width', 'height', 'is_active', 'is_default'
    ];
    
    protected $casts = [
        'layout_config' => 'array',
        'variables' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];
    
    public function certificates(): HasMany
    public function renderPreview(array $sampleData): string
}
```

#### CertificateVerification Model
```php
class CertificateVerification extends Model
{
    protected $fillable = [
        'certificate_id', 'verified_at', 'ip_address', 'user_agent'
    ];
    
    protected $casts = [
        'verified_at' => 'datetime',
    ];
    
    public function certificate(): BelongsTo
}
```


### 4. Advanced Analytics Module

#### AnalyticsService
```php
class AnalyticsService
{
    public function getStudentPerformanceTrends(array $filters = []): array
    public function predictAtRiskStudents(): Collection
    public function analyzeAttendancePatterns(array $filters = []): array
    public function forecastRevenue(int $months = 6): array
    public function calculateRetentionMetrics(array $filters = []): array
    public function getTeacherPerformanceMetrics(Teacher $teacher): array
    public function getCourseEffectivenessMetrics(Course $course): array
    public function getComparativeBatchAnalysis(Batch $batch1, Batch $batch2): array
    public function getYearOverYearComparison(int $year1, int $year2): array
    public function generateCustomReport(array $metrics, array $filters): array
    public function exportAnalytics(string $format, array $data): string
}
```

#### AnalyticsController
```php
class AnalyticsController extends Controller
{
    public function dashboard(): View
    public function studentPerformance(Request $request): JsonResponse
    public function attendanceAnalysis(Request $request): JsonResponse
    public function revenueForecasting(Request $request): JsonResponse
    public function retentionMetrics(Request $request): JsonResponse
    public function teacherPerformance(Request $request): JsonResponse
    public function courseEffectiveness(Request $request): JsonResponse
    public function customReport(Request $request): JsonResponse
    public function exportReport(Request $request): Response
}
```

### 5. Progressive Web App Module

#### PWA Configuration
```javascript
// public/sw.js - Service Worker
const CACHE_NAME = 'lms-cache-v1';
const urlsToCache = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/dashboard',
    '/offline.html'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.addAll(urlsToCache))
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request)
            .then((response) => response || fetch(event.request))
    );
});

self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-forms') {
        event.waitUntil(syncFormData());
    }
});
```

#### Manifest Configuration
```json
{
    "name": "LMS - Learning Management System",
    "short_name": "LMS",
    "description": "Complete Learning Management System",
    "start_url": "/",
    "display": "standalone",
    "background_color": "#ffffff",
    "theme_color": "#4F46E5",
    "icons": [
        {
            "src": "/images/icon-192.png",
            "sizes": "192x192",
            "type": "image/png"
        },
        {
            "src": "/images/icon-512.png",
            "sizes": "512x512",
            "type": "image/png"
        }
    ]
}
```

### 6. Multi-Language Support Module

#### LanguageService
```php
class LanguageService
{
    public function setLanguage(string $locale): void
    public function getAvailableLanguages(): array
    public function translateKey(string $key, string $locale): string
    public function updateTranslation(string $key, string $locale, string $value): bool
    public function exportTranslations(string $locale): array
    public function importTranslations(string $locale, array $translations): bool
}
```

#### Language Files Structure
```
resources/
  lang/
    en/
      auth.php
      validation.php
      messages.php
      dashboard.php
    bn/
      auth.php
      validation.php
      messages.php
      dashboard.php
```

### 7. Messaging System Module

#### MessageService
```php
class MessageService
{
    public function sendMessage(User $from, User $to, string $content, ?array $attachments = null): Message
    public function getConversation(User $user1, User $user2): Collection
    public function markAsRead(Message $message): void
    public function getUnreadCount(User $user): int
    public function searchMessages(User $user, string $query): Collection
    public function deleteMessage(Message $message): bool
    public function canSendMessage(User $from, User $to): bool
}
```

#### Message Model
```php
class Message extends Model
{
    protected $fillable = [
        'from_user_id', 'from_user_type', 'to_user_id', 'to_user_type',
        'subject', 'content', 'attachments', 'read_at', 'deleted_at'
    ];
    
    protected $casts = [
        'attachments' => 'array',
        'read_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    
    public function from(): MorphTo
    public function to(): MorphTo
    public function markAsRead(): void
    public function isRead(): bool
}
```

#### ForumService
```php
class ForumService
{
    public function createTopic(Course $course, User $user, string $title, string $content): ForumTopic
    public function replyToTopic(ForumTopic $topic, User $user, string $content): ForumReply
    public function pinTopic(ForumTopic $topic): void
    public function lockTopic(ForumTopic $topic): void
    public function subscribeTopic(ForumTopic $topic, User $user): void
    public function unsubscribeTopic(ForumTopic $topic, User $user): void
    public function getTopicsByCourse(Course $course): Collection
}
```

#### ForumTopic Model
```php
class ForumTopic extends Model
{
    protected $fillable = [
        'course_id', 'user_id', 'user_type', 'title', 'content',
        'attachments', 'is_pinned', 'is_locked', 'views_count'
    ];
    
    protected $casts = [
        'attachments' => 'array',
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
    ];
    
    public function course(): BelongsTo
    public function user(): MorphTo
    public function replies(): HasMany
    public function subscribers(): BelongsToMany
}
```

### 8. Notification Center Module

#### NotificationService
```php
class NotificationService
{
    public function createNotification(User $user, string $type, string $message, ?array $data = null): Notification
    public function getNotifications(User $user, ?string $type = null): Collection
    public function markAsRead(Notification $notification): void
    public function markAllAsRead(User $user): void
    public function deleteNotification(Notification $notification): void
    public function getUnreadCount(User $user): int
}
```

#### Notification Model
```php
class Notification extends Model
{
    protected $fillable = [
        'user_id', 'user_type', 'type', 'title', 'message',
        'data', 'read_at', 'action_url'
    ];
    
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
    
    public function user(): MorphTo
    public function markAsRead(): void
    public function isRead(): bool
}
```

### 9. SMS Modem Integration Module

#### ModemService
```php
class ModemService
{
    public function __construct(
        private SerialPort $serialPort
    ) {}
    
    public function connect(string $port, int $baudRate): bool
    public function disconnect(): void
    public function sendSms(string $phone, string $message): bool
    public function checkStatus(): array
    public function getSimBalance(): ?float
    public function sendAtCommand(string $command): string
    public function isConnected(): bool
}
```

#### ModemConfiguration Model
```php
class ModemConfiguration extends Model
{
    protected $fillable = [
        'port', 'baud_rate', 'sim_pin', 'is_active',
        'last_connected_at', 'status'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'last_connected_at' => 'datetime',
    ];
}
```

### 10. Biometric Attendance Module

#### BiometricService
```php
class BiometricService
{
    public function registerDevice(array $data): BiometricDevice
    public function syncAttendance(BiometricDevice $device): int
    public function recordAttendance(Student $student, string $method, ?array $metadata = null): Attendance
    public function verifyGpsLocation(float $latitude, float $longitude): bool
    public function generateQrCode(Student $student, Course $course): string
    public function verifyQrCode(string $code): ?array
}
```

#### BiometricDevice Model
```php
class BiometricDevice extends Model
{
    protected $fillable = [
        'name', 'type', 'api_endpoint', 'api_key',
        'location', 'is_active', 'last_sync_at'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'last_sync_at' => 'datetime',
    ];
    
    public function attendances(): HasMany
}
```


### 11. Question Bank and Advanced Exam Module

#### QuestionBankService
```php
class QuestionBankService
{
    public function createQuestion(array $data): Question
    public function updateQuestion(Question $question, array $data): Question
    public function deleteQuestion(Question $question): bool
    public function getQuestionsByCategory(string $category): Collection
    public function getRandomQuestions(array $criteria, int $count): Collection
    public function shareQuestion(Question $question, Teacher $teacher): bool
    public function getQuestionStatistics(Question $question): array
}
```

#### Question Model
```php
class Question extends Model
{
    protected $fillable = [
        'teacher_id', 'type', 'category', 'difficulty', 'content',
        'options', 'correct_answer', 'points', 'explanation',
        'tags', 'usage_count', 'average_score'
    ];
    
    protected $casts = [
        'options' => 'array',
        'tags' => 'array',
    ];
    
    public function teacher(): BelongsTo
    public function exams(): BelongsToMany
}
```

#### ExamService
```php
class ExamService
{
    public function createExam(array $data): Exam
    public function scheduleExam(Exam $exam, Carbon $startTime, Carbon $endTime): void
    public function addQuestionsToExam(Exam $exam, Collection $questions): void
    public function startExam(Exam $exam, Student $student): ExamAttempt
    public function submitExam(ExamAttempt $attempt): void
    public function detectTabSwitch(ExamAttempt $attempt): void
    public function autoSubmitExam(ExamAttempt $attempt): void
    public function gradeExam(ExamAttempt $attempt): float
    public function getExamAnalytics(Exam $exam): array
}
```

#### ExamAttempt Model
```php
class ExamAttempt extends Model
{
    protected $fillable = [
        'exam_id', 'student_id', 'started_at', 'submitted_at',
        'answers', 'score', 'tab_switches', 'status'
    ];
    
    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'answers' => 'array',
    ];
    
    public function exam(): BelongsTo
    public function student(): BelongsTo
    public function recordTabSwitch(): void
    public function shouldAutoSubmit(): bool
}
```

### 12. Document Generation Module

#### DocumentService
```php
class DocumentService
{
    public function generateIdCard(User $user, string $type): Document
    public function generateAdmitCard(Student $student, Exam $exam): Document
    public function generateTransferCertificate(Student $student): Document
    public function generateCharacterCertificate(Student $student, string $content): Document
    public function generateBonafideCertificate(Student $student): Document
    public function generateBulkDocuments(Collection $users, string $type): Collection
    public function getDocumentTemplate(string $type): DocumentTemplate
}
```

#### Document Model
```php
class Document extends Model
{
    protected $fillable = [
        'user_id', 'user_type', 'type', 'template_id',
        'document_number', 'data', 'pdf_path', 'generated_at', 'generated_by'
    ];
    
    protected $casts = [
        'data' => 'array',
        'generated_at' => 'datetime',
    ];
    
    public function user(): MorphTo
    public function template(): BelongsTo
    public function generator(): BelongsTo
}
```

#### DocumentTemplate Model
```php
class DocumentTemplate extends Model
{
    protected $fillable = [
        'name', 'type', 'layout', 'variables', 'is_active'
    ];
    
    protected $casts = [
        'layout' => 'array',
        'variables' => 'array',
        'is_active' => 'boolean',
    ];
    
    public function documents(): HasMany
}
```

### 13. API Module

#### API Authentication
```php
class ApiAuthController extends Controller
{
    public function login(Request $request): JsonResponse
    public function logout(Request $request): JsonResponse
    public function refresh(Request $request): JsonResponse
    public function generateApiKey(Request $request): JsonResponse
    public function revokeApiKey(Request $request): JsonResponse
}
```

#### API Resource Controllers
```php
class Api\StudentController extends Controller
{
    public function index(Request $request): JsonResponse
    public function show(Student $student): JsonResponse
    public function store(Request $request): JsonResponse
    public function update(Request $request, Student $student): JsonResponse
    public function destroy(Student $student): JsonResponse
}

class Api\CourseController extends Controller
{
    public function index(Request $request): JsonResponse
    public function show(Course $course): JsonResponse
    public function enroll(Request $request, Course $course): JsonResponse
}

class Api\PaymentController extends Controller
{
    public function index(Request $request): JsonResponse
    public function show(Payment $payment): JsonResponse
    public function create(Request $request): JsonResponse
}

class Api\AttendanceController extends Controller
{
    public function index(Request $request): JsonResponse
    public function record(Request $request): JsonResponse
}

class Api\ResultController extends Controller
{
    public function index(Request $request): JsonResponse
    public function show(ExamResult $result): JsonResponse
}
```

#### WebhookService
```php
class WebhookService
{
    public function registerWebhook(string $event, string $url, ?string $secret = null): Webhook
    public function triggerWebhook(string $event, array $data): void
    public function verifyWebhookSignature(string $payload, string $signature, string $secret): bool
    public function retryFailedWebhook(Webhook $webhook, array $data): bool
}
```

#### Webhook Model
```php
class Webhook extends Model
{
    protected $fillable = [
        'event', 'url', 'secret', 'is_active', 'last_triggered_at'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'last_triggered_at' => 'datetime',
    ];
}
```

#### ApiKey Model
```php
class ApiKey extends Model
{
    protected $fillable = [
        'user_id', 'name', 'key', 'last_used_at', 'expires_at', 'is_active'
    ];
    
    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];
    
    public function user(): BelongsTo
    public function isValid(): bool
}
```

### 14. Newsletter and WhatsApp Module

#### NewsletterService
```php
class NewsletterService
{
    public function createNewsletter(array $data): Newsletter
    public function scheduleNewsletter(Newsletter $newsletter, Carbon $sendAt): void
    public function sendNewsletter(Newsletter $newsletter): array
    public function trackOpen(Newsletter $newsletter, string $recipientEmail): void
    public function trackClick(Newsletter $newsletter, string $recipientEmail, string $url): void
    public function getNewsletterStatistics(Newsletter $newsletter): array
    public function unsubscribe(string $email): void
}
```

#### Newsletter Model
```php
class Newsletter extends Model
{
    protected $fillable = [
        'title', 'content', 'template_id', 'recipient_groups',
        'scheduled_at', 'sent_at', 'created_by', 'status'
    ];
    
    protected $casts = [
        'recipient_groups' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];
    
    public function creator(): BelongsTo
    public function template(): BelongsTo
    public function statistics(): HasOne
}
```

#### WhatsAppService
```php
class WhatsAppService
{
    public function sendMessage(string $phone, string $templateName, array $variables): bool
    public function sendMedia(string $phone, string $mediaUrl, string $caption): bool
    public function sendDocument(string $phone, string $documentUrl, string $filename): bool
    public function getTemplates(): array
    public function checkOptInStatus(string $phone): bool
    public function optIn(string $phone): bool
    public function optOut(string $phone): bool
}
```

#### MessageTemplate Model
```php
class MessageTemplate extends Model
{
    protected $fillable = [
        'name', 'type', 'channel', 'content', 'variables',
        'category', 'is_active', 'usage_count'
    ];
    
    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];
}
```


## Data Models

### Database Schema

#### Parent Portal Tables

```sql
-- parents table
CREATE TABLE parents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    notification_preferences JSON,
    email_verified_at TIMESTAMP NULL,
    phone_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_phone (phone)
);

-- parent_student pivot table
CREATE TABLE parent_student (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id BIGINT UNSIGNED NOT NULL,
    student_id BIGINT UNSIGNED NOT NULL,
    relationship_type VARCHAR(50) DEFAULT 'parent',
    approved_by BIGINT UNSIGNED NULL,
    approved_at TIMESTAMP NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES parents(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_parent_student (parent_id, student_id),
    INDEX idx_parent (parent_id),
    INDEX idx_student (student_id),
    INDEX idx_status (status)
);
```

#### Email Service Tables

```sql
-- email_templates table
CREATE TABLE email_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(100) NOT NULL,
    subject VARCHAR(500) NOT NULL,
    body_html TEXT NOT NULL,
    body_text TEXT,
    variables JSON,
    is_active BOOLEAN DEFAULT TRUE,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_active (is_active)
);

-- email_queue table
CREATE TABLE email_queue (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    to VARCHAR(255) NOT NULL,
    subject VARCHAR(500) NOT NULL,
    body_html TEXT NOT NULL,
    body_text TEXT,
    template_type VARCHAR(100),
    variables JSON,
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    scheduled_at TIMESTAMP NULL,
    sent_at TIMESTAMP NULL,
    retry_count INT DEFAULT 0,
    error_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_scheduled (scheduled_at),
    INDEX idx_to (to)
);

-- email_logs table
CREATE TABLE email_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    to VARCHAR(255) NOT NULL,
    subject VARCHAR(500) NOT NULL,
    template_type VARCHAR(100),
    status ENUM('sent', 'failed') NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    error_message TEXT,
    user_id BIGINT UNSIGNED NULL,
    user_type VARCHAR(100),
    INDEX idx_to (to),
    INDEX idx_status (status),
    INDEX idx_sent_at (sent_at),
    INDEX idx_user (user_id, user_type)
);
```

#### Certificate System Tables

```sql
-- certificate_templates table
CREATE TABLE certificate_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(100) NOT NULL,
    background_image VARCHAR(500),
    logo_image VARCHAR(500),
    signature_image VARCHAR(500),
    layout_config JSON,
    variables JSON,
    width INT DEFAULT 1200,
    height INT DEFAULT 900,
    is_active BOOLEAN DEFAULT TRUE,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_active (is_active)
);

-- certificates table
CREATE TABLE certificates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    course_id BIGINT UNSIGNED NOT NULL,
    template_id BIGINT UNSIGNED NOT NULL,
    certificate_number VARCHAR(100) UNIQUE NOT NULL,
    verification_code VARCHAR(50) UNIQUE NOT NULL,
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    issued_by BIGINT UNSIGNED NOT NULL,
    grade VARCHAR(10),
    status ENUM('active', 'revoked', 'expired') DEFAULT 'active',
    revoked_at TIMESTAMP NULL,
    revocation_reason TEXT,
    pdf_path VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (template_id) REFERENCES certificate_templates(id),
    FOREIGN KEY (issued_by) REFERENCES users(id),
    INDEX idx_student (student_id),
    INDEX idx_course (course_id),
    INDEX idx_verification (verification_code),
    INDEX idx_status (status)
);

-- certificate_verifications table
CREATE TABLE certificate_verifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    certificate_id BIGINT UNSIGNED NOT NULL,
    verified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (certificate_id) REFERENCES certificates(id) ON DELETE CASCADE,
    INDEX idx_certificate (certificate_id),
    INDEX idx_verified_at (verified_at)
);
```

#### Messaging and Communication Tables

```sql
-- messages table
CREATE TABLE messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    from_user_id BIGINT UNSIGNED NOT NULL,
    from_user_type VARCHAR(100) NOT NULL,
    to_user_id BIGINT UNSIGNED NOT NULL,
    to_user_type VARCHAR(100) NOT NULL,
    subject VARCHAR(500),
    content TEXT NOT NULL,
    attachments JSON,
    read_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_from (from_user_id, from_user_type),
    INDEX idx_to (to_user_id, to_user_type),
    INDEX idx_read (read_at),
    INDEX idx_created (created_at)
);

-- forum_topics table
CREATE TABLE forum_topics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    user_type VARCHAR(100) NOT NULL,
    title VARCHAR(500) NOT NULL,
    content TEXT NOT NULL,
    attachments JSON,
    is_pinned BOOLEAN DEFAULT FALSE,
    is_locked BOOLEAN DEFAULT FALSE,
    views_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    INDEX idx_course (course_id),
    INDEX idx_user (user_id, user_type),
    INDEX idx_pinned (is_pinned),
    INDEX idx_created (created_at)
);

-- forum_replies table
CREATE TABLE forum_replies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    topic_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    user_type VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    attachments JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (topic_id) REFERENCES forum_topics(id) ON DELETE CASCADE,
    INDEX idx_topic (topic_id),
    INDEX idx_user (user_id, user_type),
    INDEX idx_created (created_at)
);

-- forum_subscriptions table
CREATE TABLE forum_subscriptions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    topic_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    user_type VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (topic_id) REFERENCES forum_topics(id) ON DELETE CASCADE,
    UNIQUE KEY unique_subscription (topic_id, user_id, user_type),
    INDEX idx_topic (topic_id),
    INDEX idx_user (user_id, user_type)
);

-- notifications table
CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    user_type VARCHAR(100) NOT NULL,
    type VARCHAR(100) NOT NULL,
    title VARCHAR(500) NOT NULL,
    message TEXT NOT NULL,
    data JSON,
    read_at TIMESTAMP NULL,
    action_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user (user_id, user_type),
    INDEX idx_type (type),
    INDEX idx_read (read_at),
    INDEX idx_created (created_at)
);
```

#### Advanced Features Tables

```sql
-- questions table
CREATE TABLE questions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    teacher_id BIGINT UNSIGNED NOT NULL,
    type ENUM('mcq', 'true_false', 'short_answer', 'essay') NOT NULL,
    category VARCHAR(100),
    difficulty ENUM('easy', 'medium', 'hard') DEFAULT 'medium',
    content TEXT NOT NULL,
    options JSON,
    correct_answer TEXT,
    points DECIMAL(5,2) DEFAULT 1.00,
    explanation TEXT,
    tags JSON,
    usage_count INT DEFAULT 0,
    average_score DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_teacher (teacher_id),
    INDEX idx_category (category),
    INDEX idx_difficulty (difficulty),
    INDEX idx_type (type)
);

-- exam_attempts table
CREATE TABLE exam_attempts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    exam_id BIGINT UNSIGNED NOT NULL,
    student_id BIGINT UNSIGNED NOT NULL,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    submitted_at TIMESTAMP NULL,
    answers JSON,
    score DECIMAL(5,2),
    tab_switches INT DEFAULT 0,
    status ENUM('in_progress', 'submitted', 'auto_submitted') DEFAULT 'in_progress',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    INDEX idx_exam (exam_id),
    INDEX idx_student (student_id),
    INDEX idx_status (status)
);

-- biometric_devices table
CREATE TABLE biometric_devices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('fingerprint', 'rfid', 'qr_code', 'gps') NOT NULL,
    api_endpoint VARCHAR(500),
    api_key VARCHAR(255),
    location VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    last_sync_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_active (is_active)
);

-- modem_configurations table
CREATE TABLE modem_configurations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    port VARCHAR(50) NOT NULL,
    baud_rate INT DEFAULT 9600,
    sim_pin VARCHAR(10),
    is_active BOOLEAN DEFAULT FALSE,
    last_connected_at TIMESTAMP NULL,
    status ENUM('connected', 'disconnected', 'error') DEFAULT 'disconnected',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- documents table
CREATE TABLE documents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    user_type VARCHAR(100) NOT NULL,
    type VARCHAR(100) NOT NULL,
    template_id BIGINT UNSIGNED,
    document_number VARCHAR(100) UNIQUE NOT NULL,
    data JSON,
    pdf_path VARCHAR(500),
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    generated_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (generated_by) REFERENCES users(id),
    INDEX idx_user (user_id, user_type),
    INDEX idx_type (type),
    INDEX idx_document_number (document_number)
);

-- document_templates table
CREATE TABLE document_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(100) NOT NULL,
    layout JSON,
    variables JSON,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_active (is_active)
);

-- api_keys table
CREATE TABLE api_keys (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    key VARCHAR(255) UNIQUE NOT NULL,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_key (key),
    INDEX idx_user (user_id),
    INDEX idx_active (is_active)
);

-- webhooks table
CREATE TABLE webhooks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event VARCHAR(100) NOT NULL,
    url VARCHAR(500) NOT NULL,
    secret VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    last_triggered_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_event (event),
    INDEX idx_active (is_active)
);

-- newsletters table
CREATE TABLE newsletters (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL,
    content TEXT NOT NULL,
    template_id BIGINT UNSIGNED,
    recipient_groups JSON,
    scheduled_at TIMESTAMP NULL,
    sent_at TIMESTAMP NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    status ENUM('draft', 'scheduled', 'sent') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_status (status),
    INDEX idx_scheduled (scheduled_at)
);

-- message_templates table
CREATE TABLE message_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(100) NOT NULL,
    channel ENUM('email', 'sms', 'whatsapp', 'in_app') NOT NULL,
    content TEXT NOT NULL,
    variables JSON,
    category VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    usage_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_channel (channel),
    INDEX idx_active (is_active)
);
```

### Relationships Summary

- **Parent** has many **Students** (many-to-many through parent_student)
- **Parent** has many **Messages**
- **Parent** has many **Notifications** (polymorphic)
- **Student** belongs to many **Parents**
- **EmailTemplate** has many **EmailQueue** entries
- **Certificate** belongs to **Student**, **Course**, **CertificateTemplate**
- **Certificate** has many **CertificateVerifications**
- **Message** belongs to **User** (polymorphic for from and to)
- **ForumTopic** belongs to **Course** and **User** (polymorphic)
- **ForumTopic** has many **ForumReplies**
- **ForumTopic** has many **Subscribers** (many-to-many)
- **Question** belongs to **Teacher**
- **Question** belongs to many **Exams**
- **ExamAttempt** belongs to **Exam** and **Student**
- **Document** belongs to **User** (polymorphic) and **DocumentTemplate**
- **ApiKey** belongs to **User**
- **Newsletter** belongs to **User** (creator)


## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Parent Portal Properties

**Property 1: Parent Registration Completeness**
*For any* valid parent registration data (email, phone, password), registering a parent should create a parent account AND trigger both email and SMS verification messages.
**Validates: Requirements 1.1, 1.2**

**Property 2: Multi-Student Linking**
*For any* parent and any collection of valid student identifiers, linking students should create pending relationships that require approval for each student.
**Validates: Requirements 1.3, 1.4**

**Property 3: Profile Update Persistence**
*For any* parent and any valid profile update data, updating the profile should persist all changes and return the updated values on subsequent retrieval.
**Validates: Requirements 1.5**

**Property 4: Password Reset Dual Channel**
*For any* parent account, initiating password reset should generate a reset token and send it via both email and SMS channels.
**Validates: Requirements 1.6**

**Property 5: Dashboard Child Data Completeness**
*For any* parent with approved linked students, the dashboard should display attendance, payments, results, and schedules for all linked children.
**Validates: Requirements 2.1, 2.2, 2.3, 2.4, 2.5**

**Property 6: Child Data Filtering**
*For any* parent with multiple children and any date range filter, filtering should return only data for the selected child within the specified date range.
**Validates: Requirements 2.6, 2.7**

**Property 7: Payment Processing Flow**
*For any* parent and any collection of pending invoices, successful payment should generate a receipt, send confirmation emails to both parent and Director, and update invoice status to paid.
**Validates: Requirements 3.3, 3.4, 3.6**

**Property 8: Overdue Payment Calculation**
*For any* invoice with a due date in the past, the system should mark it as overdue and display the overdue indicator.
**Validates: Requirements 3.7**

**Property 9: Message Authorization**
*For any* parent and any teacher, the parent should only be able to send messages to teachers of their approved linked students.
**Validates: Requirements 4.7**

**Property 10: Message Notification Dual Channel**
*For any* message sent between parent and teacher, the recipient should receive notifications via both email and SMS.
**Validates: Requirements 4.2, 4.5**

**Property 11: File Attachment Size Limit**
*For any* message with file attachments, the system should accept attachments up to 10MB and reject larger files with an appropriate error.
**Validates: Requirements 4.6**

### Email Service Properties

**Property 12: Email Template Variable Substitution**
*For any* email template with variables and any set of variable values, rendering the template should replace all variable placeholders with their corresponding values.
**Validates: Requirements 5.6, 6.2**

**Property 13: Email Validation**
*For any* string, the email validation function should return true only if the string is a valid email address format.
**Validates: Requirements 5.7**

**Property 14: Email Retry Mechanism**
*For any* failed email, the system should retry sending up to 3 times with exponential backoff before marking it as permanently failed.
**Validates: Requirements 5.4**

**Property 15: Email Logging Completeness**
*For any* email send attempt, the system should log the recipient, status, timestamp, and error message (if failed).
**Validates: Requirements 5.5**

**Property 16: Template Deletion Protection**
*For any* email template that is the only template of its type, attempting to delete it should fail with an appropriate error message.
**Validates: Requirements 6.7**

**Property 17: Automated Payment Email**
*For any* completed payment, the system should automatically send payment confirmation emails to both the parent and the Director.
**Validates: Requirements 7.1**

**Property 18: Automated Result Email**
*For any* published exam result, the system should automatically send result notification emails to both the student and their linked parents.
**Validates: Requirements 7.2**

**Property 19: Attendance Alert Threshold**
*For any* student whose attendance percentage falls below 75%, the system should automatically send an attendance alert email to all linked parents.
**Validates: Requirements 7.3**

**Property 20: Notification Preference Respect**
*For any* user with email notifications disabled for a specific type, the system should not send emails of that type to the user.
**Validates: Requirements 7.7**

### Certificate System Properties

**Property 21: Certificate Verification Code Uniqueness**
*For any* two certificates generated by the system, their verification codes should be different.
**Validates: Requirements 9.2**

**Property 22: Automatic Certificate Generation**
*For any* student who completes a course, the system should automatically generate a certificate with a unique certificate number and verification code.
**Validates: Requirements 9.1, 9.2**

**Property 23: Certificate Generation Notification**
*For any* generated certificate, the system should send an email notification to the student with a download link.
**Validates: Requirements 9.6**

**Property 24: Certificate Verification Round Trip**
*For any* valid certificate, entering its verification code in the verification system should return the certificate details including student name, course name, completion date, and grade.
**Validates: Requirements 10.2, 10.3**

**Property 25: Invalid Certificate Code Handling**
*For any* string that is not a valid verification code, the verification system should return an appropriate error message without exposing system information.
**Validates: Requirements 10.4**

**Property 26: Certificate Verification Logging**
*For any* certificate verification attempt, the system should log the certificate ID (if valid), timestamp, IP address, and user agent.
**Validates: Requirements 10.5**

**Property 27: Certificate Revocation**
*For any* certificate that is revoked with a reason, subsequent verification attempts should display the certificate as revoked with the revocation reason.
**Validates: Requirements 10.7, 10.6**

### Messaging and Communication Properties

**Property 28: Message History Persistence**
*For any* message sent between two users, the message should be stored and retrievable in the conversation history for both users.
**Validates: Requirements 4.3**

**Property 29: Unread Message Count**
*For any* user, the unread message count should equal the number of messages sent to that user where read_at is null.
**Validates: Requirements 16.2, 16.7**

**Property 30: Forum Topic Subscription Notification**
*For any* forum topic with subscribers, when a reply is posted, all subscribers should receive email notifications.
**Validates: Requirements 15.8**

**Property 31: Forum Topic Locking**
*For any* locked forum topic, attempting to post a reply should fail with an appropriate error message.
**Validates: Requirements 15.6**

### Question Bank and Exam Properties

**Property 32: Question Uniqueness in Exam**
*For any* exam generated from a question bank, no question should appear more than once in that exam.
**Validates: Requirements 19.6**

**Property 33: Random Question Selection Criteria**
*For any* question selection criteria (category, difficulty, count), the randomly selected questions should all match the specified criteria and the count should equal the requested number (or less if insufficient questions exist).
**Validates: Requirements 19.5**

**Property 34: Tab Switch Detection and Auto-Submit**
*For any* exam attempt, if the student switches tabs more than 3 times, the system should automatically submit the exam and mark it as auto-submitted.
**Validates: Requirements 20.3, 20.4**

**Property 35: Question Order Randomization**
*For any* exam taken by two different students, the question order should be different (unless the exam has only one question).
**Validates: Requirements 20.7**

**Property 36: Partial Credit Calculation**
*For any* MCQ question with multiple correct answers, if a student selects some but not all correct answers, the score should be proportional to the number of correct answers selected.
**Validates: Requirements 20.5**

### API and Integration Properties

**Property 37: JWT Token Expiration**
*For any* JWT token generated by the system, attempting to use it after its expiration time should result in an authentication error with 401 status code.
**Validates: Requirements 22.3**

**Property 38: API Rate Limiting**
*For any* API key, making more than 100 requests within a 60-second window should result in rate limit errors (429 status code) for requests exceeding the limit.
**Validates: Requirements 22.5**

**Property 39: API Request Logging**
*For any* API request, the system should log the endpoint, HTTP method, status code, and timestamp.
**Validates: Requirements 22.9**

**Property 40: API Error Response Consistency**
*For any* API error, the response should include a consistent JSON structure with error code, message, and appropriate HTTP status code.
**Validates: Requirements 22.10**

**Property 41: Webhook Event Triggering**
*For any* registered webhook for a specific event type, when that event occurs, the system should send an HTTP POST request to the webhook URL with the event data.
**Validates: Requirements 22.7**

**Property 42: API Key Revocation**
*For any* revoked API key, attempting to use it for authentication should result in an authentication error.
**Validates: Requirements 22.8**

### Biometric and Modem Properties

**Property 43: Biometric Attendance Sync**
*For any* biometric device with pending attendance records, syncing should import all records and create corresponding attendance entries in the LMS.
**Validates: Requirements 18.4, 18.7**

**Property 44: SMS Modem Fallback**
*For any* SMS send attempt, if the SMS API fails, the system should automatically attempt to send via the configured GSM modem (if available and active).
**Validates: Requirements 17.4**

### Document Generation Properties

**Property 45: Bulk Document Generation**
*For any* collection of users and a document type, bulk generation should create a document for each user with unique document numbers.
**Validates: Requirements 21.7**

**Property 46: Document Number Uniqueness**
*For any* two documents generated by the system, their document numbers should be different.
**Validates: Requirements 21.1-21.6**


## Error Handling

### Error Handling Strategy

The LMS will implement a consistent error handling approach across all modules:

#### Exception Hierarchy

```php
// Base exception for all LMS errors
class LmsException extends Exception
{
    protected $errorCode;
    protected $context;
    
    public function __construct(string $message, string $errorCode, array $context = [])
    {
        parent::__construct($message);
        $this->errorCode = $errorCode;
        $this->context = $context;
    }
    
    public function getErrorCode(): string
    public function getContext(): array
}

// Specific exception types
class ValidationException extends LmsException {}
class AuthenticationException extends LmsException {}
class AuthorizationException extends LmsException {}
class ResourceNotFoundException extends LmsException {}
class ServiceException extends LmsException {}
class ExternalServiceException extends LmsException {}
class RateLimitException extends LmsException {}
```

#### Error Response Format

All API and AJAX responses will follow this format:

```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The provided data is invalid",
        "details": {
            "email": ["The email field is required"],
            "phone": ["The phone format is invalid"]
        }
    },
    "timestamp": "2024-01-15T10:30:00Z"
}
```

#### Error Handling by Module

**Parent Portal:**
- Invalid student linking attempts → `STUDENT_NOT_FOUND` or `ALREADY_LINKED`
- Unauthorized access to child data → `UNAUTHORIZED_ACCESS`
- Payment processing failures → `PAYMENT_FAILED` with reason
- Message sending to unlinked student's teacher → `UNAUTHORIZED_MESSAGE`

**Email Service:**
- Invalid email address → `INVALID_EMAIL_ADDRESS`
- Template not found → `TEMPLATE_NOT_FOUND`
- Email sending failure → `EMAIL_SEND_FAILED` (queued for retry)
- Template deletion protection → `CANNOT_DELETE_LAST_TEMPLATE`

**Certificate System:**
- Course not completed → `COURSE_NOT_COMPLETED`
- Invalid verification code → `INVALID_VERIFICATION_CODE`
- Certificate already exists → `CERTIFICATE_ALREADY_EXISTS`
- PDF generation failure → `PDF_GENERATION_FAILED`

**Messaging System:**
- File size exceeds limit → `FILE_TOO_LARGE`
- Unauthorized message recipient → `UNAUTHORIZED_RECIPIENT`
- Forum topic locked → `TOPIC_LOCKED`
- Invalid attachment type → `INVALID_FILE_TYPE`

**API:**
- Invalid authentication → `401 UNAUTHORIZED`
- Rate limit exceeded → `429 TOO_MANY_REQUESTS`
- Resource not found → `404 NOT_FOUND`
- Validation error → `422 UNPROCESSABLE_ENTITY`
- Server error → `500 INTERNAL_SERVER_ERROR`

**Biometric/Modem:**
- Device not connected → `DEVICE_NOT_CONNECTED`
- Sync failure → `SYNC_FAILED` (queued for retry)
- Modem communication error → `MODEM_ERROR`
- Invalid GPS coordinates → `INVALID_GPS_LOCATION`

#### Logging Strategy

All errors will be logged with appropriate context:

```php
Log::error('Email sending failed', [
    'recipient' => $email,
    'template_type' => $templateType,
    'error' => $exception->getMessage(),
    'trace' => $exception->getTraceAsString()
]);
```

Critical errors (payment failures, certificate generation failures, data loss risks) will trigger admin notifications via email and SMS.

#### User-Facing Error Messages

- Technical errors will be translated to user-friendly messages
- Validation errors will be specific and actionable
- System errors will show generic messages without exposing internals
- All error messages will support multi-language translation

## Testing Strategy

### Testing Approach

The LMS will employ a comprehensive dual testing strategy combining unit tests and property-based tests to ensure correctness and reliability.

#### Unit Testing

**Purpose:** Verify specific examples, edge cases, error conditions, and integration points.

**Framework:** PHPUnit for Laravel

**Coverage Areas:**
- Controller request/response handling
- Service method behavior with specific inputs
- Model relationships and scopes
- Validation rules
- Edge cases (empty data, boundary values, null handling)
- Error conditions and exception handling
- Integration between services

**Example Unit Tests:**
```php
class ParentServiceTest extends TestCase
{
    public function test_parent_can_link_student_with_valid_id()
    {
        $parent = Parent::factory()->create();
        $student = Student::factory()->create();
        
        $result = $this->parentService->linkStudentToParent($parent, $student->id);
        
        $this->assertTrue($result);
        $this->assertDatabaseHas('parent_student', [
            'parent_id' => $parent->id,
            'student_id' => $student->id,
            'status' => 'pending'
        ]);
    }
    
    public function test_linking_nonexistent_student_throws_exception()
    {
        $parent = Parent::factory()->create();
        
        $this->expectException(ResourceNotFoundException::class);
        $this->parentService->linkStudentToParent($parent, 'invalid-id');
    }
    
    public function test_payment_confirmation_sends_to_parent_and_director()
    {
        Mail::fake();
        $payment = Payment::factory()->create();
        
        $this->emailService->sendPaymentConfirmation($payment);
        
        Mail::assertSent(PaymentConfirmationMail::class, 2);
    }
}
```

#### Property-Based Testing

**Purpose:** Verify universal properties across all valid inputs through randomized testing.

**Framework:** PHPUnit with custom property test helpers or Pest PHP with property testing plugins

**Configuration:** Minimum 100 iterations per property test

**Property Test Structure:**
```php
/**
 * Feature: lms-missing-features-complete, Property 1: Parent Registration Completeness
 * For any valid parent registration data, registering should create account and send verifications
 */
public function test_property_parent_registration_completeness()
{
    $this->propertyTest(100, function() {
        // Generate random valid parent data
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'password' => 'Password123!'
        ];
        
        Mail::fake();
        SMS::fake();
        
        $parent = $this->parentService->registerParent($data);
        
        // Assert account created
        $this->assertInstanceOf(Parent::class, $parent);
        $this->assertDatabaseHas('parents', ['email' => $data['email']]);
        
        // Assert both verifications sent
        Mail::assertSent(VerificationMail::class, function($mail) use ($data) {
            return $mail->hasTo($data['email']);
        });
        SMS::assertSent(function($sms) use ($data) {
            return $sms->to === $data['phone'];
        });
    });
}

/**
 * Feature: lms-missing-features-complete, Property 21: Certificate Verification Code Uniqueness
 * For any two certificates, their verification codes should be different
 */
public function test_property_certificate_verification_code_uniqueness()
{
    $this->propertyTest(100, function() {
        $student1 = Student::factory()->create();
        $student2 = Student::factory()->create();
        $course = Course::factory()->create();
        
        $cert1 = $this->certificateService->generateCertificate($student1, $course);
        $cert2 = $this->certificateService->generateCertificate($student2, $course);
        
        $this->assertNotEquals(
            $cert1->verification_code,
            $cert2->verification_code,
            'Certificate verification codes must be unique'
        );
    });
}

/**
 * Feature: lms-missing-features-complete, Property 12: Email Template Variable Substitution
 * For any template with variables and values, rendering should replace all placeholders
 */
public function test_property_email_template_variable_substitution()
{
    $this->propertyTest(100, function() {
        $template = EmailTemplate::factory()->create([
            'body_html' => 'Hello {name}, your payment of {amount} is confirmed.',
            'variables' => ['name', 'amount']
        ]);
        
        $variables = [
            'name' => $this->faker->name,
            'amount' => $this->faker->randomFloat(2, 100, 10000)
        ];
        
        $rendered = $template->renderWithVariables($variables);
        
        // Assert all variables replaced
        $this->assertStringContainsString($variables['name'], $rendered);
        $this->assertStringContainsString((string)$variables['amount'], $rendered);
        $this->assertStringNotContainsString('{name}', $rendered);
        $this->assertStringNotContainsString('{amount}', $rendered);
    });
}
```

#### Integration Testing

**Purpose:** Verify end-to-end workflows and interactions between multiple components.

**Coverage Areas:**
- Parent registration → email/SMS verification → student linking → approval workflow
- Payment processing → receipt generation → email notifications → payment history
- Course completion → certificate generation → email notification → verification
- Message sending → notification delivery → message history
- Exam creation → question selection → student attempt → grading → result notification

**Example Integration Test:**
```php
public function test_complete_parent_registration_and_linking_workflow()
{
    Mail::fake();
    SMS::fake();
    
    // Register parent
    $parentData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+8801712345678',
        'password' => 'Password123!'
    ];
    
    $response = $this->post('/parent/register', $parentData);
    $response->assertRedirect('/parent/verify');
    
    // Verify email
    $parent = Parent::where('email', $parentData['email'])->first();
    $this->get("/parent/verify-email/{$parent->verification_token}")
        ->assertRedirect('/parent/dashboard');
    
    // Link student
    $student = Student::factory()->create();
    $this->actingAs($parent, 'parent')
        ->post('/parent/link-student', ['student_id' => $student->id])
        ->assertRedirect('/parent/dashboard');
    
    // Verify pending status
    $this->assertDatabaseHas('parent_student', [
        'parent_id' => $parent->id,
        'student_id' => $student->id,
        'status' => 'pending'
    ]);
    
    // Admin approves
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin)
        ->post("/admin/approve-parent-link/{$parent->id}/{$student->id}")
        ->assertRedirect();
    
    // Verify approved status
    $this->assertDatabaseHas('parent_student', [
        'parent_id' => $parent->id,
        'student_id' => $student->id,
        'status' => 'approved'
    ]);
    
    // Verify notifications sent
    Mail::assertSent(LinkApprovedMail::class);
}
```

#### Test Data Generation

**Factories:** Use Laravel factories for generating test data with realistic values

**Seeders:** Create seeders for common test scenarios

**Faker:** Use Faker library for randomized data in property tests

#### Continuous Integration

- All tests run automatically on every commit
- Minimum 80% code coverage requirement
- Property tests run with 100 iterations in CI
- Integration tests run against test database
- Failed tests block merging to main branch

#### Test Organization

```
tests/
├── Unit/
│   ├── Services/
│   │   ├── ParentServiceTest.php
│   │   ├── EmailServiceTest.php
│   │   ├── CertificateServiceTest.php
│   │   └── ...
│   ├── Models/
│   │   ├── ParentTest.php
│   │   ├── CertificateTest.php
│   │   └── ...
│   └── Controllers/
│       ├── ParentControllerTest.php
│       └── ...
├── Property/
│   ├── ParentPortalPropertiesTest.php
│   ├── EmailServicePropertiesTest.php
│   ├── CertificateSystemPropertiesTest.php
│   ├── MessagingPropertiesTest.php
│   ├── ExamPropertiesTest.php
│   └── ApiPropertiesTest.php
├── Integration/
│   ├── ParentWorkflowTest.php
│   ├── PaymentWorkflowTest.php
│   ├── CertificateWorkflowTest.php
│   └── ...
└── Feature/
    ├── ParentPortalTest.php
    ├── EmailNotificationsTest.php
    └── ...
```

### Testing Best Practices

1. **Test Isolation:** Each test should be independent and not rely on other tests
2. **Database Transactions:** Use database transactions to rollback changes after each test
3. **Mocking External Services:** Mock SMS, email, payment gateways, and biometric devices
4. **Clear Assertions:** Use descriptive assertion messages
5. **Test Naming:** Use descriptive test names that explain what is being tested
6. **Property Test Documentation:** Each property test must reference its design document property number
7. **Edge Case Coverage:** Unit tests should cover boundary values, null inputs, empty collections
8. **Error Path Testing:** Test both success and failure scenarios
9. **Performance Testing:** Include tests for rate limiting, bulk operations, and query optimization
10. **Security Testing:** Test authentication, authorization, input validation, and SQL injection prevention

### Manual Testing Checklist

Before release, manually verify:
- [ ] Parent portal UI/UX across different devices
- [ ] Email templates render correctly in major email clients
- [ ] Certificate PDFs display correctly and are printable
- [ ] PWA installation and offline functionality
- [ ] Multi-language switching and translations
- [ ] Biometric device integration (if hardware available)
- [ ] SMS modem integration (if hardware available)
- [ ] Payment gateway integration with test transactions
- [ ] WhatsApp Business API integration
- [ ] API documentation accuracy
- [ ] Mobile responsiveness
- [ ] Browser compatibility (Chrome, Firefox, Safari, Edge)
- [ ] Accessibility compliance (WCAG 2.1 Level AA)
