@props([
    'name',
    'id' => null,
    'label' => '',
    'value' => '',
    'placeholder' => 'Pick a date',
    'required' => false,
    'min' => '', // YYYY-MM-DD
    'max' => '', // YYYY-MM-DD
    'helperText' => '',
    'persist' => false,
])

@php
    $uniqueId = 'date-picker-' . uniqid();
    $componentId = 'cp-' . ($id ?: $uniqueId);
    $inputId = $id ?: ($uniqueId . '-input');
    $error = $errors->first($name);
    $currentValue = old($name, $value);
@endphp

<div class="relative custom-date-picker group" id="{{ $componentId }}" data-persist="{{ $persist ? 'true' : 'false' }}" data-name="{{ $name }}">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-semibold text-gray-700 mb-1">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    <div class="relative">
        <!-- Trigger Button -->
        <button type="button"
            id="{{ $componentId }}-trigger"
            class="w-full px-4 py-2.5 bg-white border {{ $error ? 'border-red-500' : 'border-gray-300' }} rounded-lg text-left shadow-sm focus:ring-2 focus:ring-bd-green focus:border-transparent transition-all outline-none flex items-center justify-between hover:bg-gray-50"
            aria-haspopup="dialog"
            aria-expanded="false">
            
            <div class="flex items-center text-gray-700">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span id="{{ $componentId }}-display" class="{{ $currentValue ? 'text-gray-900' : 'text-gray-500' }}">
                    {{ $currentValue ? \Carbon\Carbon::parse($currentValue)->format('M d, Y') : $placeholder }}
                </span>
            </div>
        </button>

        <!-- Hidden Input for Form Submission -->
        <input type="hidden" name="{{ $name }}" id="{{ $inputId }}" value="{{ $currentValue }}" @if($required) required @endif>

        <!-- Calendar Dropdown -->
        <div id="{{ $componentId }}-calendar" 
            class="hidden absolute top-full left-0 mt-2 p-4 bg-white border border-gray-200 rounded-xl shadow-xl z-50 w-[320px] animate-in fade-in zoom-in-95 duration-200">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <button type="button" id="{{ $componentId }}-prev-month" class="p-1 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <div class="font-semibold text-gray-900" id="{{ $componentId }}-month-year">
                    <!-- Month Year -->
                </div>
                <button type="button" id="{{ $componentId }}-next-month" class="p-1 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>

            <!-- Weekdays -->
            <div class="grid grid-cols-7 mb-2">
                @foreach(['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'] as $day)
                    <div class="text-center text-xs font-medium text-gray-500 py-1">{{ $day }}</div>
                @endforeach
            </div>

            <!-- Days Grid -->
            <div class="grid grid-cols-7 gap-1" id="{{ $componentId }}-days">
                <!-- Days will be injected here -->
            </div>
            
            <!-- Footer (Optional: Clear/Today) -->
             <div class="flex justify-between mt-4 pt-3 border-t border-gray-100">
                <button type="button" id="{{ $componentId }}-clear" class="text-xs text-red-500 hover:text-red-700 font-medium px-2 py-1 rounded hover:bg-red-50 transition-colors">Clear</button>
                <button type="button" id="{{ $componentId }}-today" class="text-xs text-bd-green hover:text-bd-green-dark font-medium px-2 py-1 rounded hover:bg-emerald-50 transition-colors">Today</button>
            </div>
        </div>
    </div>
    
    @if($helperText)
        <p class="text-xs text-gray-500 mt-1">{{ $helperText }}</p>
    @endif
    
    @if($error)
        <p class="text-xs text-red-600 mt-1">{{ $error }}</p>
    @endif
</div>

@once
    @push('scripts')
    <script>
        function getDateStorageKey(name) {
            return `date_persist_${window.location.pathname}_${name}`;
        }

        document.addEventListener('DOMContentLoaded', () => {
            function initDatePicker(rootId) {
                const root = document.getElementById(rootId);
                if (!root) return;
                
                const name = root.dataset.name;
                const isPersist = root.dataset.persist === 'true';
                const trigger = document.getElementById(rootId + '-trigger');
                const display = document.getElementById(rootId + '-display');
                const calendar = document.getElementById(rootId + '-calendar');
                const daysGrid = document.getElementById(rootId + '-days');
                const monthYearDisplay = document.getElementById(rootId + '-month-year');
                const prevBtn = document.getElementById(rootId + '-prev-month');
                const nextBtn = document.getElementById(rootId + '-next-month');
                const clearBtn = document.getElementById(rootId + '-clear');
                const todayBtn = document.getElementById(rootId + '-today');
                const input = root.querySelector('input[type="hidden"]');

                if (!input) return;

                let currentDate = new Date(); // Navigator date
                let selectedDate = input.value ? new Date(input.value) : null;
                
                // Persistence Load
                if (isPersist) {
                    const savedValue = localStorage.getItem(getDateStorageKey(name));
                    if (savedValue && !input.value) {
                        input.value = savedValue;
                        selectedDate = new Date(savedValue);
                        if (!isNaN(selectedDate.getTime())) {
                            // Update display
                            display.textContent = selectedDate.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
                            display.classList.remove('text-gray-500');
                            display.classList.add('text-gray-900');
                        }
                    }
                }

                // Initialize navigator to selected date if exists
                if(selectedDate && !isNaN(selectedDate.getTime())) {
                    currentDate = new Date(selectedDate);
                }

                const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                const renderCalendar = () => {
                    if (!daysGrid) return;
                    daysGrid.innerHTML = '';
                    const year = currentDate.getFullYear();
                    const month = currentDate.getMonth();

                    // Update Header
                    monthYearDisplay.innerHTML = `
                        <span class="mr-2">${months[month]}</span>
                        <div class="relative inline-block">
                            <button type="button" 
                                class="text-sm font-bold text-gray-700 hover:bg-gray-100 px-2 py-1 rounded flex items-center transition-colors"
                                onclick="document.getElementById('${rootId}-year-dropdown').classList.toggle('hidden')">
                                ${year}
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div id="${rootId}-year-dropdown" 
                                class="hidden absolute top-full left-1/2 -translate-x-1/2 mt-1 w-24 max-h-48 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                                <!-- Years populated below -->
                            </div>
                        </div>
                    `;

                    // Populate Years
                    const yearDropdown = document.getElementById(`${rootId}-year-dropdown`);
                    const currentYear = new Date().getFullYear();
                    const startYear = 1900;
                    const endYear = currentYear + 10;
                    
                    yearDropdown.innerHTML = '';
                    for(let y = endYear; y >= startYear; y--) {
                        const div = document.createElement('div');
                        div.className = `px-3 py-1 text-sm cursor-pointer hover:bg-emerald-50 hover:text-bd-green text-center ${y === year ? 'bg-emerald-50 text-bd-green font-bold' : 'text-gray-700'}`;
                        div.textContent = y;
                        div.onclick = (e) => {
                            e.stopPropagation();
                            currentDate.setFullYear(y);
                            yearDropdown.classList.add('hidden');
                            renderCalendar();
                        };
                        yearDropdown.appendChild(div);
                    }

                    // Click outside to close year dropdown
                    const closeYearDropdown = (e) => {
                        if (!e.target.closest('#' + rootId + '-year-dropdown') && !e.target.closest('#' + rootId + '-month-year button')) {
                            yearDropdown.classList.add('hidden');
                            document.removeEventListener('click', closeYearDropdown);
                        }
                    };
                    // Add listener with delay to avoid immediate trigger
                    setTimeout(() => document.addEventListener('click', closeYearDropdown), 0);


                    const firstDay = new Date(year, month, 1).getDay();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();
                    
                    // Padding days
                    for (let i = 0; i < firstDay; i++) {
                        const empty = document.createElement('div');
                        daysGrid.appendChild(empty);
                    }

                    // Actual days
                    for (let i = 1; i <= daysInMonth; i++) {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.textContent = i;
                        btn.className = `h-9 w-9 rounded-full text-sm flex items-center justify-center transition-all duration-200 hover:bg-emerald-50 hover:text-bd-green focus:outline-none focus:ring-2 focus:ring-bd-green/50`;
                        
                        // Check if selected
                        if (selectedDate && 
                            selectedDate.getDate() === i && 
                            selectedDate.getMonth() === month && 
                            selectedDate.getFullYear() === year) {
                            btn.className += ' bg-bd-green text-white hover:bg-bd-green hover:text-white shadow-md font-semibold';
                            btn.setAttribute('aria-selected', 'true');
                        } else {
                            // Check for today styling if not selected
                            const today = new Date();
                             if (i === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                                 btn.className += ' text-bd-green font-bold border border-bd-green/30';
                             } else {
                                 btn.className += ' text-gray-700';
                             }
                        }

                        btn.onclick = () => selectDate(year, month, i);
                        daysGrid.appendChild(btn);
                    }
                };

                const selectDate = (year, month, day) => {
                    selectedDate = new Date(year, month, day);
                    // Format YYYY-MM-DD
                    const yyyy = selectedDate.getFullYear();
                    const mm = String(selectedDate.getMonth() + 1).padStart(2, '0');
                    const dd = String(selectedDate.getDate()).padStart(2, '0');
                    const val = `${yyyy}-${mm}-${dd}`;
                    
                    input.value = val;
                    
                    // Persistence Save
                    if (isPersist) {
                        localStorage.setItem(getDateStorageKey(name), val);
                    }

                    // Trigger change for other listeners
                    input.dispatchEvent(new Event('change'));
                    
                    // Format for display (M d, Y)
                    display.textContent = selectedDate.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
                    display.classList.remove('text-gray-500');
                    display.classList.add('text-gray-900');
                    
                    closeCalendar();
                    renderCalendar(); // Re-render to update selection
                };

                const toggleCalendar = () => {
                    if (calendar.classList.contains('hidden')) {
                        openCalendar();
                    } else {
                        closeCalendar();
                    }
                };

                const openCalendar = () => {
                    // Close all others
                    document.querySelectorAll('.custom-date-picker [id$="-calendar"]').forEach(el => el.classList.add('hidden'));
                    
                    calendar.classList.remove('hidden');
                    trigger.setAttribute('aria-expanded', 'true');
                    renderCalendar();
                };

                const closeCalendar = () => {
                    calendar.classList.add('hidden');
                    trigger.setAttribute('aria-expanded', 'false');
                };

                // Event Listeners
                trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    toggleCalendar();
                });

                calendar.addEventListener('click', (e) => e.stopPropagation());

                document.addEventListener('click', (e) => {
                    if (!root.contains(e.target)) {
                        closeCalendar();
                    }
                });

                prevBtn.addEventListener('click', () => {
                    currentDate.setMonth(currentDate.getMonth() - 1);
                    renderCalendar();
                });

                nextBtn.addEventListener('click', () => {
                    currentDate.setMonth(currentDate.getMonth() + 1);
                    renderCalendar();
                });
                
                clearBtn.addEventListener('click', () => {
                   selectedDate = null;
                   input.value = '';
                   if (isPersist) {
                       localStorage.removeItem(getDateStorageKey(name));
                   }
                   input.dispatchEvent(new Event('change'));
                   display.textContent = '{{ $placeholder }}';
                   display.classList.remove('text-gray-900');
                   display.classList.add('text-gray-500');
                   closeCalendar();
                   renderCalendar();
                });

                todayBtn.addEventListener('click', () => {
                    const today = new Date();
                    selectDate(today.getFullYear(), today.getMonth(), today.getDate());
                    currentDate = new Date(); // Reset view to today
                });
                
                // Initial Render
                if(input.value) {
                    renderCalendar();
                 }
            }

            // Init all
            document.querySelectorAll('.custom-date-picker').forEach(el => {
                initDatePicker(el.id);
            });
        });
    </script>
    @endpush
@endonce
