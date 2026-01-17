@props([
    'label' => '',
    'name',
    'value' => '',
    'type' => 'text',
    'placeholder' => '',
    'min' => 0,
    'max' => 255,
    'required' => false,
    'helperText' => null,
    'persist' => false,
])

<div class="space-y-1.5 input-group-{{ $name }} custom-input-group" id="input-group-{{ $name }}" data-persist="{{ $persist ? 'true' : 'false' }}" data-name="{{ $name }}">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    <div class="relative">
        @if($type === 'tel')
            <!-- Phone Input Trigger -->
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <span class="text-xl">🇧🇩</span>
                <span class="text-gray-300 mx-2">|</span>
            </div>
        @endif

        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($min) minlength="{{ $min }}" @endif
            @if($max) maxlength="{{ $max }}" @endif
            @if($required) required @endif
            oninput="handleInput(this, '{{ $name }}', '{{ $type }}', {{ $min }}, {{ $max }})"
            onblur="validateInput(this, '{{ $name }}', '{{ $type }}')"
            {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent transition-all outline-none text-gray-900 placeholder:text-gray-400 ' . ($type === 'tel' ? 'pl-14' : '')]) }}
        >
        
        <!-- Icons/Validation Feedback -->
        <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center space-x-2 pointer-events-none">
             <!-- Email Validation Icon -->
            @if($type === 'email')
                <svg id="{{ $name }}-email-check" class="w-5 h-5 text-green-500 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            @endif
        </div>

        @if($type === 'text' || $type === 'textarea')
            <div 
                id="{{ $name }}-counter"
                class="absolute right-3 bottom-0 -mb-5 text-[10px] font-medium transition-colors text-gray-400"
            >
                <span id="{{ $name }}-current">0</span>/<span id="{{ $name }}-max">{{ $max }}</span>
            </div>
        @endif
    </div>

    <!-- Error Message Area -->
    <p id="{{ $name }}-js-error" class="text-xs text-red-600 mt-1 hidden"></p>

    @if($helperText)
        <p class="text-xs text-gray-500 mt-1">{{ $helperText }}</p>
    @endif

    @error($name)
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

@once
    @push('scripts')
    <script>
        function getInputStorageKey(name) {
            return `input_persist_${window.location.pathname}_${name}`;
        }

        function handleInput(input, name, type, min, max) {
            const group = document.getElementById('input-group-' + name);
            const isPersist = group && group.dataset.persist === 'true';

            // Persistence
            if (isPersist) {
                localStorage.setItem(getInputStorageKey(name), input.value);
            }

            // Character Counter for Text
            if (type === 'text' || type === 'textarea') {
                const current = input.value.length;
                const counter = document.getElementById(name + '-counter');
                const currentSpan = document.getElementById(name + '-current');
                
                if (currentSpan) {
                    currentSpan.textContent = current;
                    
                    if (current > max) {
                        counter.classList.remove('text-gray-400', 'text-orange-500');
                        counter.classList.add('text-red-500');
                    } else if (current < min) {
                        counter.classList.remove('text-gray-400', 'text-red-500');
                        counter.classList.add('text-orange-500');
                    } else {
                        counter.classList.remove('text-red-500', 'text-orange-500');
                        counter.classList.add('text-gray-400');
                    }
                }
            }

            // Real-time Email Validation (Visual only)
            if (type === 'email') {
                const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
                const icon = document.getElementById(name + '-email-check');
                if (input.value.length > 0) {
                     if (isValid) icon.classList.remove('hidden');
                     else icon.classList.add('hidden');
                } else {
                    icon.classList.add('hidden');
                }
            }
        }

        function validateInput(input, name, type) {
            const errorElement = document.getElementById(name + '-js-error');
            const value = input.value;
            let isValid = true;
            let errorMessage = '';

            // Reset
            if(errorElement) {
                errorElement.classList.add('hidden');
                errorElement.textContent = '';
            }
            input.classList.remove('border-red-500', 'focus:ring-red-500');
            input.classList.add('border-gray-300', 'focus:ring-bd-green');

            if (type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid email address.';
                }
            }

            if (type === 'tel' && value) {
                if (value.length < 10) {
                     isValid = false;
                     errorMessage = 'Phone number seems too short.';
                }
            }

            if (!isValid && errorElement) {
                errorElement.textContent = errorMessage;
                errorElement.classList.remove('hidden');
                input.classList.remove('border-gray-300', 'focus:ring-bd-green');
                input.classList.add('border-red-500', 'focus:ring-red-500');
            }
        }

        // Initialize counts and checks on load
        document.addEventListener('DOMContentLoaded', function() {
            const groups = document.querySelectorAll('.custom-input-group');
            groups.forEach(group => {
                const name = group.dataset.name;
                const input = group.querySelector('input, textarea');
                const isPersist = group.dataset.persist === 'true';

                if (isPersist && input) {
                    const savedValue = localStorage.getItem(getInputStorageKey(name));
                    if (savedValue !== null && savedValue !== "") {
                        // Only override if current value is empty (to avoid overwriting old() values or server-set values)
                        if (!input.value) {
                            input.value = savedValue;
                        }
                    }

                    // Save on change as well
                    input.addEventListener('change', () => {
                        localStorage.setItem(getInputStorageKey(name), input.value);
                    });
                }

                // Trigger input event to run any initial logic/validation
                if (input) {
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
        });
    </script>
    @endpush
@endonce
