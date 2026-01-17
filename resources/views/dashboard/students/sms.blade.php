@extends('layouts.admin')

@section('title', 'SMS Notification')
@section('page-title', 'এসএমএস বিজ্ঞপ্তি')
@section('page-description', 'Send SMS notifications to students')

@section('content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Sent</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_sent'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Delivered</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_delivered'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Failed</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_failed'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_pending'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- SMS Compose Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Compose SMS</h2>
                </div>

                <form action="{{ route('dashboard.communication.send') }}" method="POST" class="p-6 space-y-6" id="smsForm">
                    @csrf
                    <input type="hidden" name="type" value="sms">

                    <!-- Recipient Selection Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Send To</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-bd-green transition-colors">
                                <input type="radio" name="recipient_type" value="individual" class="peer sr-only" checked onchange="toggleRecipientFields()">
                                <div class="flex items-center w-full">
                                    <div class="p-2 bg-blue-100 rounded-lg peer-checked:bg-bd-green peer-checked:text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-900">Individual</span>
                                </div>
                                <div class="absolute inset-0 border-2 border-bd-green rounded-lg opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            </label>

                            <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-bd-green transition-colors">
                                <input type="radio" name="recipient_type" value="batch" class="peer sr-only" onchange="toggleRecipientFields()">
                                <div class="flex items-center w-full">
                                    <div class="p-2 bg-purple-100 rounded-lg peer-checked:bg-bd-green peer-checked:text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-900">Batch</span>
                                </div>
                                <div class="absolute inset-0 border-2 border-bd-green rounded-lg opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            </label>

                            <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-bd-green transition-colors">
                                <input type="radio" name="recipient_type" value="all" class="peer sr-only" onchange="toggleRecipientFields()">
                                <div class="flex items-center w-full">
                                    <div class="p-2 bg-green-100 rounded-lg peer-checked:bg-bd-green peer-checked:text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-900">All Students</span>
                                </div>
                                <div class="absolute inset-0 border-2 border-bd-green rounded-lg opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Individual Student Selection -->
                    <div id="individualField">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Students</label>
                        <select name="student_ids[]" multiple size="8"
                            class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-bd-green focus:border-bd-green p-2">
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" class="py-2">
                                    {{ $student->user->name ?? 'N/A' }} - {{ $student->phone }} 
                                    @if($student->batch)
                                        ({{ $student->batch->name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-gray-500">Hold Ctrl (Cmd on Mac) to select multiple students</p>
                    </div>

                    <!-- Batch Selection -->
                    <div id="batchField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Batch</label>
                        <select name="batch_id" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-bd-green focus:border-bd-green p-2">
                            <option value="">Choose a batch...</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMS Message *</label>
                        <textarea name="message" id="smsMessage" rows="5" required maxlength="320"
                            class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-bd-green focus:border-bd-green p-3"
                            placeholder="Type your message here..."></textarea>
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-xs text-gray-500">Maximum 320 characters (2 SMS)</p>
                            <p class="text-xs font-medium text-gray-700">
                                <span id="charCount">0</span>/320 characters
                                <span class="ml-2">(<span id="smsCount">0</span> SMS)</span>
                            </p>
                        </div>
                    </div>

                    <!-- Message Templates -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quick Templates</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <button type="button" onclick="insertTemplate('Class reminder: Your class is scheduled for tomorrow at [TIME]. Please be on time.')" 
                                class="text-left px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                📚 Class Reminder
                            </button>
                            <button type="button" onclick="insertTemplate('Exam notification: Your exam is scheduled on [DATE]. Please prepare accordingly.')" 
                                class="text-left px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                📝 Exam Notification
                            </button>
                            <button type="button" onclick="insertTemplate('Fee reminder: Your payment is due. Please clear your dues at the earliest.')" 
                                class="text-left px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                💰 Fee Reminder
                            </button>
                            <button type="button" onclick="insertTemplate('Holiday notice: The institution will remain closed on [DATE] due to [REASON].')" 
                                class="text-left px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                🏖️ Holiday Notice
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <button type="button" onclick="document.getElementById('smsForm').reset(); updateCharCount();"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                            Clear
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Send SMS
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Recent SMS Logs -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Recent SMS</h2>
                </div>

                <div class="p-4 space-y-3 max-h-[600px] overflow-y-auto">
                    @forelse($recentSms as $sms)
                        <div class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">{{ $sms->phone }}</span>
                                </div>
                                @php
                                    $statusColors = [
                                        'sent' => 'bg-blue-100 text-blue-800',
                                        'delivered' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusColors[$sms->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($sms->status) }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 mb-2 line-clamp-2">{{ $sms->message }}</p>
                            <p class="text-xs text-gray-400">{{ $sms->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No SMS sent yet</p>
                        </div>
                    @endforelse
                </div>

                @if($recentSms->count() > 0)
                    <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                        <a href="{{ route('dashboard.communication.logs') }}" class="text-sm text-bd-green hover:text-bd-green-dark font-medium">
                            View All Logs →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Toggle recipient fields based on selection
        function toggleRecipientFields() {
            const recipientType = document.querySelector('input[name="recipient_type"]:checked').value;
            const individualField = document.getElementById('individualField');
            const batchField = document.getElementById('batchField');

            if (recipientType === 'individual') {
                individualField.classList.remove('hidden');
                batchField.classList.add('hidden');
            } else if (recipientType === 'batch') {
                individualField.classList.add('hidden');
                batchField.classList.remove('hidden');
            } else {
                individualField.classList.add('hidden');
                batchField.classList.add('hidden');
            }
        }

        // Character counter
        const messageField = document.getElementById('smsMessage');
        const charCount = document.getElementById('charCount');
        const smsCount = document.getElementById('smsCount');

        function updateCharCount() {
            const length = messageField.value.length;
            charCount.textContent = length;
            
            // Calculate SMS count (160 chars per SMS)
            const count = length === 0 ? 0 : Math.ceil(length / 160);
            smsCount.textContent = count;
        }

        messageField.addEventListener('input', updateCharCount);

        // Insert template
        function insertTemplate(template) {
            messageField.value = template;
            updateCharCount();
            messageField.focus();
        }

        // Initialize
        updateCharCount();
    </script>
    @endpush
@endsection