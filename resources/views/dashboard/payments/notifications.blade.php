@extends('layouts.admin')

@section('title', 'Payment Notifications')
@section('page-title', 'Payment Reminders')
@section('page-description', 'Send payment reminder notifications to students with outstanding dues')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
            <p class="text-sm font-medium text-gray-600">Students with Dues</p>
            <p class="text-2xl font-bold text-red-600 mt-2">{{ $studentsWithDue->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-amber-500">
            <p class="text-sm font-medium text-gray-600">Total Outstanding</p>
            <p class="text-2xl font-bold text-amber-600 mt-2">৳{{ number_format($studentsWithDue->sum('due_amount'), 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-sm font-medium text-gray-600">Average Due</p>
            <p class="text-2xl font-bold text-blue-600 mt-2">৳{{ number_format($studentsWithDue->avg('due_amount') ?? 0, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Students List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Students with Outstanding Dues</h2>
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="selectAll()" class="text-sm text-bd-green hover:text-bd-green-dark">
                            Select All
                        </button>
                        <span class="text-gray-300">|</span>
                        <button type="button" onclick="deselectAll()" class="text-sm text-gray-500 hover:text-gray-700">
                            Deselect All
                        </button>
                    </div>
                </div>

                <form id="notificationForm" action="{{ route('dashboard.payments.send-notification') }}" method="POST">
                    @csrf
                    
                    @if($studentsWithDue->count() > 0)
                        <div class="max-h-[500px] overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                                            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)"
                                                class="rounded border-gray-300 text-bd-green focus:ring-bd-green">
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($studentsWithDue as $student)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                                    class="student-checkbox rounded border-gray-300 text-bd-green focus:ring-bd-green">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                        {{ strtoupper(substr($student->user->name ?? 'S', 0, 1)) }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">{{ $student->user->name ?? 'N/A' }}</div>
                                                        <div class="text-xs text-gray-500">{{ $student->phone ?? $student->user->email ?? 'No contact' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $student->batch->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm font-bold text-red-600">৳{{ number_format($student->due_amount, 2) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-green-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">All Clear!</h3>
                            <p class="text-gray-500">No students have outstanding dues at the moment.</p>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Message Composer -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md border border-gray-100 sticky top-24">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Compose Message</h2>
                </div>
                <div class="p-6">
                    <!-- Message Template Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quick Templates</label>
                        <div class="space-y-2">
                            <button type="button" onclick="useTemplate('reminder')"
                                class="w-full text-left px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                📋 Payment Reminder
                            </button>
                            <button type="button" onclick="useTemplate('urgent')"
                                class="w-full text-left px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                ⚠️ Urgent Payment Notice
                            </button>
                            <button type="button" onclick="useTemplate('friendly')"
                                class="w-full text-left px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                👋 Friendly Reminder
                            </button>
                        </div>
                    </div>

                    <!-- Message Input -->
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Message <span class="text-red-500">*</span>
                        </label>
                        <textarea name="message" id="message" rows="6" form="notificationForm" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent text-sm"
                            placeholder="Enter your message here...">Dear {student_name}, this is a reminder that you have an outstanding balance of ৳{due_amount}. Please make your payment at your earliest convenience. Thank you.</textarea>
                        <p class="mt-1 text-xs text-gray-500">
                            Available placeholders: {student_name}, {due_amount}, {total_amount}, {paid_amount}
                        </p>
                    </div>

                    <!-- Character Count -->
                    <div class="mb-4 flex justify-between text-xs text-gray-500">
                        <span>Characters: <span id="charCount">0</span></span>
                        <span>SMS Parts: <span id="smsCount">1</span></span>
                    </div>

                    <!-- Selected Count -->
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">
                            Selected: <span id="selectedCount" class="font-bold text-bd-green">0</span> students
                        </p>
                    </div>

                    <!-- Send Button -->
                    <button type="submit" form="notificationForm" id="sendButton" disabled
                        class="w-full px-4 py-3 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Send Reminders
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const templates = {
            reminder: 'Dear {student_name}, this is a reminder that you have an outstanding balance of ৳{due_amount}. Please make your payment at your earliest convenience. Thank you.',
            urgent: 'URGENT: Dear {student_name}, your payment of ৳{due_amount} is overdue. Please clear your dues immediately to avoid any inconvenience. Contact the office for assistance.',
            friendly: 'Hi {student_name}! 👋 Just a friendly reminder about your pending balance of ৳{due_amount}. Feel free to reach out if you have any questions. We\'re here to help!'
        };

        function useTemplate(type) {
            document.getElementById('message').value = templates[type];
            updateCharCount();
        }

        function updateCharCount() {
            const message = document.getElementById('message').value;
            const charCount = message.length;
            const smsCount = Math.ceil(charCount / 160) || 1;
            
            document.getElementById('charCount').textContent = charCount;
            document.getElementById('smsCount').textContent = smsCount;
        }

        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('.student-checkbox:checked');
            const count = checkboxes.length;
            document.getElementById('selectedCount').textContent = count;
            document.getElementById('sendButton').disabled = count === 0;
        }

        function selectAll() {
            document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = true);
            document.getElementById('selectAllCheckbox').checked = true;
            updateSelectedCount();
        }

        function deselectAll() {
            document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('selectAllCheckbox').checked = false;
            updateSelectedCount();
        }

        function toggleSelectAll(checkbox) {
            document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = checkbox.checked);
            updateSelectedCount();
        }

        // Event listeners
        document.getElementById('message').addEventListener('input', updateCharCount);
        document.querySelectorAll('.student-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSelectedCount);
        });

        // Initialize
        updateCharCount();
        updateSelectedCount();
    </script>
    @endpush
@endsection
