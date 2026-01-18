@extends('layouts.admin')

@section('title', 'System Settings')
@section('page-title', 'System Settings')

@push('scripts')
<script>
    // Sync color picker with text input
    document.addEventListener('DOMContentLoaded', function() {
        const primaryColorPicker = document.getElementById('theme_primary_color');
        const primaryColorText = document.getElementById('theme_primary_color_text');
        const secondaryColorPicker = document.getElementById('theme_secondary_color');
        const secondaryColorText = document.getElementById('theme_secondary_color_text');

        if (primaryColorPicker && primaryColorText) {
            primaryColorPicker.addEventListener('input', function() {
                primaryColorText.value = this.value;
            });
        }

        if (secondaryColorPicker && secondaryColorText) {
            secondaryColorPicker.addEventListener('input', function() {
                secondaryColorText.value = this.value;
            });
        }
    });
</script>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('dashboard.settings.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Institution Settings -->
        <x-ui.card>
            <x-ui.card-header>
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                        <i class="fas fa-university text-lg"></i>
                    </div>
                    <div>
                        <x-ui.card-title>Institution Information</x-ui.card-title>
                        <x-ui.card-description>Basic information about your institution</x-ui.card-description>
                    </div>
                </div>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-ui.label for="institution_name">Institution Name</x-ui.label>
                        <x-ui.input type="text" name="institution_name" id="institution_name" 
                            value="{{ $settings['institution']['institution_name']['value'] ?? '' }}" />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="institution_email">Email Address</x-ui.label>
                        <x-ui.input type="email" name="institution_email" id="institution_email" 
                            value="{{ $settings['institution']['institution_email']['value'] ?? '' }}" />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="institution_phone">Phone Number</x-ui.label>
                        <x-ui.input type="text" name="institution_phone" id="institution_phone" 
                            value="{{ $settings['institution']['institution_phone']['value'] ?? '' }}" />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="institution_website">Website</x-ui.label>
                        <x-ui.input type="url" name="institution_website" id="institution_website" 
                            value="{{ $settings['institution']['institution_website']['value'] ?? '' }}" />
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <x-ui.label for="institution_address">Address</x-ui.label>
                        <x-ui.textarea name="institution_address" id="institution_address" rows="2">
                            {{ $settings['institution']['institution_address']['value'] ?? '' }}
                        </x-ui.textarea>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Student ID Settings -->
        <x-ui.card>
            <x-ui.card-header>
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-600">
                        <i class="fas fa-id-card text-lg"></i>
                    </div>
                    <div>
                        <x-ui.card-title>Student ID Format</x-ui.card-title>
                        <x-ui.card-description>Configure how student registration numbers are generated</x-ui.card-description>
                    </div>
                </div>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-ui.label for="student_id_format">ID Format Pattern</x-ui.label>
                        <x-ui.input type="text" name="student_id_format" id="student_id_format" 
                            value="{{ $settings['student']['student_id_format']['value'] ?? '{YEAR}{BATCH}{SEQ:4}' }}" />
                        <p class="text-[0.8rem] text-muted-foreground">Tokens: {YEAR}, {MONTH}, {BATCH}, {SEQ:n}</p>
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="student_id_sequence_start">Sequence Start Number</x-ui.label>
                        <x-ui.input type="number" name="student_id_sequence_start" id="student_id_sequence_start" min="1"
                            value="{{ $settings['student']['student_id_sequence_start']['value'] ?? 1 }}" />
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- SMS Gateway Settings -->
        <x-ui.card>
            <x-ui.card-header>
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-600">
                        <i class="fas fa-comment-alt text-lg"></i>
                    </div>
                    <div>
                        <x-ui.card-title>SMS Gateway</x-ui.card-title>
                        <x-ui.card-description>Configure SMS notification settings</x-ui.card-description>
                    </div>
                </div>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="mb-6 flex items-center space-x-2">
                    <input type="checkbox" id="sms_gateway_enabled" name="sms_gateway_enabled" value="1"
                        {{ ($settings['sms']['sms_gateway_enabled']['value'] ?? false) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-input text-primary focus:ring-ring">
                    <x-ui.label for="sms_gateway_enabled" class="font-normal">Enable SMS Notifications</x-ui.label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-ui.select name="sms_gateway_provider" label="SMS Provider">
                            <option value="twilio" {{ ($settings['sms']['sms_gateway_provider']['value'] ?? '') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                            <option value="nexmo" {{ ($settings['sms']['sms_gateway_provider']['value'] ?? '') == 'nexmo' ? 'selected' : '' }}>Nexmo</option>
                            <option value="custom" {{ ($settings['sms']['sms_gateway_provider']['value'] ?? '') == 'custom' ? 'selected' : '' }}>Custom</option>
                        </x-ui.select>
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="sms_sender_id">Sender ID</x-ui.label>
                        <x-ui.input type="text" name="sms_sender_id" id="sms_sender_id" 
                            value="{{ $settings['sms']['sms_sender_id']['value'] ?? '' }}" />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="sms_gateway_api_key">API Key</x-ui.label>
                        <x-ui.input type="password" name="sms_gateway_api_key" id="sms_gateway_api_key" 
                            value="{{ $settings['sms']['sms_gateway_api_key']['value'] ?? '' }}" />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="sms_gateway_api_secret">API Secret</x-ui.label>
                        <x-ui.input type="password" name="sms_gateway_api_secret" id="sms_gateway_api_secret" 
                            value="{{ $settings['sms']['sms_gateway_api_secret']['value'] ?? '' }}" />
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Attendance & Payment Settings -->
        <x-ui.card>
             <x-ui.card-header>
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-600">
                        <i class="fas fa-coins text-lg"></i>
                    </div>
                    <div>
                        <x-ui.card-title>Attendance & Payment</x-ui.card-title>
                        <x-ui.card-description>Configure attendance thresholds and payment settings</x-ui.card-description>
                    </div>
                </div>
            </x-ui.card-header>
            <x-ui.card-content>
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <x-ui.label for="attendance_threshold">Attendance Threshold (%)</x-ui.label>
                        <x-ui.input type="number" name="attendance_threshold" id="attendance_threshold" min="0" max="100"
                            value="{{ $settings['attendance']['attendance_threshold']['value'] ?? 75 }}" />
                        <p class="text-[0.8rem] text-muted-foreground">Students below this will be flagged</p>
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="currency">Currency</x-ui.label>
                        <x-ui.input type="text" name="currency" id="currency" 
                            value="{{ $settings['payment']['currency']['value'] ?? 'BDT' }}" />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="receipt_prefix">Receipt Prefix</x-ui.label>
                        <x-ui.input type="text" name="receipt_prefix" id="receipt_prefix" 
                            value="{{ $settings['payment']['receipt_prefix']['value'] ?? 'RCP' }}" />
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="invoice_prefix">Invoice Prefix</x-ui.label>
                        <x-ui.input type="text" name="invoice_prefix" id="invoice_prefix" 
                            value="{{ $settings['payment']['invoice_prefix']['value'] ?? 'INV' }}" />
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Theme Settings -->
        <x-ui.card>
            <x-ui.card-header>
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-lg bg-pink-500/10 flex items-center justify-center text-pink-600">
                        <i class="fas fa-palette text-lg"></i>
                    </div>
                    <div>
                        <x-ui.card-title>Theme Colors</x-ui.card-title>
                        <x-ui.card-description>Customize the primary and secondary colors of your website</x-ui.card-description>
                    </div>
                </div>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-ui.label for="theme_primary_color">Primary Color</x-ui.label>
                        <div class="flex gap-3 items-center">
                            <input type="color" name="theme_primary_color" id="theme_primary_color" 
                                value="{{ $settings['theme']['theme_primary_color']['value'] ?? '#3b82f6' }}"
                                class="h-10 w-20 rounded border border-input cursor-pointer" />
                            <x-ui.input type="text" id="theme_primary_color_text" 
                                value="{{ $settings['theme']['theme_primary_color']['value'] ?? '#3b82f6' }}"
                                placeholder="#3b82f6" class="flex-1" readonly />
                        </div>
                        <p class="text-[0.8rem] text-muted-foreground">Main brand color used throughout the site</p>
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="theme_secondary_color">Secondary Color</x-ui.label>
                        <div class="flex gap-3 items-center">
                            <input type="color" name="theme_secondary_color" id="theme_secondary_color" 
                                value="{{ $settings['theme']['theme_secondary_color']['value'] ?? '#8b5cf6' }}"
                                class="h-10 w-20 rounded border border-input cursor-pointer" />
                            <x-ui.input type="text" id="theme_secondary_color_text" 
                                value="{{ $settings['theme']['theme_secondary_color']['value'] ?? '#8b5cf6' }}"
                                placeholder="#8b5cf6" class="flex-1" readonly />
                        </div>
                        <p class="text-[0.8rem] text-muted-foreground">Accent color for highlights and CTAs</p>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4">
            <x-ui.button type="button" variant="outline" as="a" href="{{ route('dashboard.settings.clear-cache') }}">
                Clear Cache
            </x-ui.button>
            <x-ui.button type="submit">
                Save Settings
            </x-ui.button>
        </div>
    </form>
</div>
@endsection
