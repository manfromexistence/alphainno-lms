<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(
        protected SettingsService $settingsService
    ) {
    }

    /**
     * Display all settings grouped by category.
     */
    public function index(): View
    {
        $settings = $this->settingsService->getAllGrouped();
        $groups = $this->settingsService->getGroups();

        return view('dashboard.settings.index', compact('settings', 'groups'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Institution Settings
            'institution_name' => 'nullable|string|max:255',
            'institution_logo' => 'nullable|string|max:500',
            'institution_address' => 'nullable|string|max:1000',
            'institution_phone' => 'nullable|string|max:20',
            'institution_email' => 'nullable|email|max:255',
            'institution_website' => 'nullable|url|max:255',

            // Student Settings
            'student_id_format' => 'nullable|string|max:100',
            'student_id_sequence_start' => 'nullable|integer|min:1',

            // SMS Settings
            'sms_gateway_enabled' => 'nullable|boolean',
            'sms_gateway_provider' => 'nullable|string|in:twilio,nexmo,custom',
            'sms_gateway_api_key' => 'nullable|string|max:255',
            'sms_gateway_api_secret' => 'nullable|string|max:255',
            'sms_sender_id' => 'nullable|string|max:20',

            // Attendance Settings
            'attendance_threshold' => 'nullable|integer|min:0|max:100',

            // Payment Settings
            'currency' => 'nullable|string|max:10',
            'receipt_prefix' => 'nullable|string|max:10',
            'invoice_prefix' => 'nullable|string|max:10',

            // Theme Settings
            'theme_primary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'theme_primary_foreground' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'theme_secondary_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'theme_secondary_foreground' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        try {
            foreach ($validated as $key => $value) {
                if ($value !== null) {
                    $this->settingsService->set($key, $value);
                }
            }

            // Handle checkbox for sms_gateway_enabled (unchecked = not in request)
            if (!$request->has('sms_gateway_enabled')) {
                $this->settingsService->set('sms_gateway_enabled', false);
            }

            return redirect()->route('dashboard.settings.index')
                ->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.settings.index')
                ->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    /**
     * Update form field configuration.
     */
    public function updateFormFields(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fields' => 'required|array',
            'fields.*.visible' => 'required|boolean',
            'fields.*.required' => 'required|boolean',
            'fields.*.order' => 'required|integer|min:1',
        ]);

        try {
            $this->settingsService->set('student_form_fields', $validated['fields']);

            return redirect()->route('dashboard.settings.index')
                ->with('success', 'Form fields configuration updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.settings.index')
                ->with('error', 'Failed to update form fields: ' . $e->getMessage());
        }
    }

    /**
     * Clear settings cache.
     */
    public function clearCache(): RedirectResponse
    {
        $this->settingsService->clearCache();

        return redirect()->route('dashboard.settings.index')
            ->with('success', 'Settings cache cleared successfully.');
    }
}
