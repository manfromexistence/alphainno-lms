<?php

namespace App\Services;

use App\Models\FormConfig;
use Illuminate\Support\Collection;

class FormConfigService
{
    /**
     * Default form fields configuration.
     */
    protected array $defaultFields = [
        'student_registration' => [
            // Basic Information
            ['field_name' => 'name_bn', 'label' => 'Name (Bengali)', 'visible' => true, 'required' => true, 'order' => 1],
            ['field_name' => 'phone', 'label' => 'Phone Number', 'visible' => true, 'required' => true, 'order' => 2],
            ['field_name' => 'dob', 'label' => 'Date of Birth', 'visible' => true, 'required' => false, 'order' => 3],
            ['field_name' => 'gender', 'label' => 'Gender', 'visible' => true, 'required' => false, 'order' => 4],
            ['field_name' => 'blood_group', 'label' => 'Blood Group', 'visible' => true, 'required' => false, 'order' => 5],
            ['field_name' => 'religion', 'label' => 'Religion', 'visible' => true, 'required' => false, 'order' => 6],
            ['field_name' => 'profile_image', 'label' => 'Profile Photo', 'visible' => true, 'required' => false, 'order' => 7],

            // Academic Information
            ['field_name' => 'class', 'label' => 'Class', 'visible' => true, 'required' => true, 'order' => 10],
            ['field_name' => 'batch_id', 'label' => 'Batch', 'visible' => true, 'required' => true, 'order' => 11],

            // Parent/Guardian Information
            ['field_name' => 'father_name', 'label' => 'Father\'s Name', 'visible' => true, 'required' => false, 'order' => 20],
            ['field_name' => 'mother_name', 'label' => 'Mother\'s Name', 'visible' => true, 'required' => false, 'order' => 21],
            ['field_name' => 'father_phone', 'label' => 'Father\'s Phone', 'visible' => true, 'required' => false, 'order' => 22],
            ['field_name' => 'mother_phone', 'label' => 'Mother\'s Phone', 'visible' => true, 'required' => false, 'order' => 23],
            ['field_name' => 'guardian_name', 'label' => 'Guardian Name', 'visible' => true, 'required' => false, 'order' => 24],
            ['field_name' => 'guardian_phone', 'label' => 'Guardian Phone', 'visible' => true, 'required' => false, 'order' => 25],

            // Address Information
            ['field_name' => 'present_address', 'label' => 'Present Address', 'visible' => true, 'required' => false, 'order' => 30],
            ['field_name' => 'permanent_address', 'label' => 'Permanent Address', 'visible' => true, 'required' => false, 'order' => 31],

            // Educational Background
            ['field_name' => 'ssc_info', 'label' => 'SSC Information', 'visible' => true, 'required' => false, 'order' => 40],
            ['field_name' => 'hsc_info', 'label' => 'HSC Information', 'visible' => true, 'required' => false, 'order' => 41],
            ['field_name' => 'undergrad_info', 'label' => 'Undergraduate Information', 'visible' => false, 'required' => false, 'order' => 42],

            // Payment Information
            ['field_name' => 'total_amount', 'label' => 'Total Fee', 'visible' => true, 'required' => false, 'order' => 50],
            ['field_name' => 'paid_amount', 'label' => 'Paid Amount', 'visible' => true, 'required' => false, 'order' => 51],
            ['field_name' => 'payment_method', 'label' => 'Payment Method', 'visible' => true, 'required' => false, 'order' => 52],
        ],
    ];

    /**
     * Get visible fields for a form type.
     */
    public function getVisibleFields(string $formType, ?string $role = null): Collection
    {
        $fields = FormConfig::where('form_type', $formType)
            ->where('visible', true)
            ->orderBy('order')
            ->get();

        // If no fields in database, return defaults
        if ($fields->isEmpty()) {
            $fields = collect($this->getDefaultFields($formType));
        }

        // Filter by role if provided
        if ($role) {
            $fields = $fields->filter(function ($field) use ($role) {
                $roleVisibility = is_array($field) 
                    ? ($field['role_visibility'] ?? null)
                    : $field->role_visibility;

                if (empty($roleVisibility)) {
                    return true;
                }

                return in_array($role, $roleVisibility);
            });
        }

        return $fields;
    }

    /**
     * Set field visibility for a form type.
     */
    public function setFieldVisibility(string $formType, string $fieldName, bool $visible): FormConfig
    {
        return FormConfig::updateOrCreate(
            ['form_type' => $formType, 'field_name' => $fieldName],
            ['visible' => $visible]
        );
    }

    /**
     * Set field order for a form type.
     */
    public function setFieldOrder(string $formType, array $order): void
    {
        foreach ($order as $index => $fieldName) {
            FormConfig::updateOrCreate(
                ['form_type' => $formType, 'field_name' => $fieldName],
                ['order' => $index + 1]
            );
        }
    }

    /**
     * Get role-based field permissions.
     */
    public function getRoleFieldPermissions(string $role): array
    {
        $permissions = [];

        foreach (array_keys($this->defaultFields) as $formType) {
            $fields = FormConfig::where('form_type', $formType)->get();

            if ($fields->isEmpty()) {
                $fields = collect($this->getDefaultFields($formType));
            }

            $permissions[$formType] = $fields->filter(function ($field) use ($role) {
                $roleVisibility = is_array($field) 
                    ? ($field['role_visibility'] ?? null)
                    : $field->role_visibility;

                if (empty($roleVisibility)) {
                    return true;
                }

                return in_array($role, $roleVisibility);
            })->pluck('field_name')->toArray();
        }

        return $permissions;
    }

    /**
     * Set role visibility for a field.
     */
    public function setRoleVisibility(string $formType, string $fieldName, array $roles): FormConfig
    {
        return FormConfig::updateOrCreate(
            ['form_type' => $formType, 'field_name' => $fieldName],
            ['role_visibility' => $roles]
        );
    }

    /**
     * Get all fields for a form type (including hidden).
     */
    public function getAllFields(string $formType): Collection
    {
        $fields = FormConfig::where('form_type', $formType)
            ->orderBy('order')
            ->get();

        if ($fields->isEmpty()) {
            return collect($this->getDefaultFields($formType));
        }

        return $fields;
    }

    /**
     * Get default fields for a form type.
     */
    public function getDefaultFields(string $formType): array
    {
        return $this->defaultFields[$formType] ?? [];
    }

    /**
     * Initialize form configuration from defaults.
     */
    public function initializeFromDefaults(string $formType): void
    {
        $defaults = $this->getDefaultFields($formType);

        foreach ($defaults as $field) {
            FormConfig::updateOrCreate(
                ['form_type' => $formType, 'field_name' => $field['field_name']],
                [
                    'visible' => $field['visible'],
                    'order' => $field['order'],
                    'role_visibility' => $field['role_visibility'] ?? null,
                ]
            );
        }
    }

    /**
     * Bulk update field configuration.
     */
    public function bulkUpdate(string $formType, array $fields): void
    {
        foreach ($fields as $fieldName => $config) {
            FormConfig::updateOrCreate(
                ['form_type' => $formType, 'field_name' => $fieldName],
                [
                    'visible' => $config['visible'] ?? true,
                    'order' => $config['order'] ?? 999,
                    'role_visibility' => $config['role_visibility'] ?? null,
                ]
            );
        }
    }

    /**
     * Get available form types.
     */
    public function getFormTypes(): array
    {
        return array_keys($this->defaultFields);
    }

    /**
     * Check if a field is visible for a role.
     */
    public function isFieldVisibleForRole(string $formType, string $fieldName, string $role): bool
    {
        $field = FormConfig::where('form_type', $formType)
            ->where('field_name', $fieldName)
            ->first();

        if (!$field) {
            // Check defaults
            $defaults = $this->getDefaultFields($formType);
            foreach ($defaults as $default) {
                if ($default['field_name'] === $fieldName) {
                    if (!$default['visible']) {
                        return false;
                    }
                    if (empty($default['role_visibility'] ?? null)) {
                        return true;
                    }
                    return in_array($role, $default['role_visibility']);
                }
            }
            return false;
        }

        if (!$field->visible) {
            return false;
        }

        if (empty($field->role_visibility)) {
            return true;
        }

        return in_array($role, $field->role_visibility);
    }
}
