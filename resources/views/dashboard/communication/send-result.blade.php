@extends('layouts.admin')

@section('title', 'Send Result Notifications')
@section('page-title', 'Send Result Notifications')
@section('page-description', 'Send exam result notifications to students via SMS')

@section('content')
<div class="space-y-6">
    <!-- Send Result Notification Form - Requirement 13.1, 13.2, 13.3 -->
    <x-ui.card>
        <x-ui.card-header>
            <x-ui.card-title>Send Exam Result Notifications</x-ui.card-title>
            <x-ui.card-description>Send SMS notifications to students about their exam results</x-ui.card-description>
        </x-ui.card-header>
        <x-ui.card-content>
            <form id="resultNotificationForm" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Exam Selection -->
                    <div>
                        <x-ui.label for="exam_id">Select Exam <span class="text-red-500">*</span></x-ui.label>
                        <x-ui.select-native name="exam_id" id="exam_id" required>
                            <option value="">Choose an exam...</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}">
                                    {{ $exam->title }} 
                                    @if($exam->batch)
                                        ({{ $exam->batch->name }})
                                    @endif
                                    - {{ $exam->created_at->format('d M Y') }}
                                </option>
                            @endforeach
                        </x-ui.select-native>
                        <p class="text-xs text-gray-500 mt-1">Select the exam for which you want to send result notifications</p>
                    </div>
                    
                    <!-- Batch Filter (Optional) -->
                    <div>
                        <x-ui.label for="batch_id">Filter by Batch (Optional)</x-ui.label>
                        <x-ui.select-native name="batch_id" id="batch_id">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}">
                                    {{ $batch->name }}
                                    @if($batch->course)
                                        - {{ $batch->course->name }}
                                    @endif
                                </option>
                            @endforeach
                        </x-ui.select-native>
                        <p class="text-xs text-gray-500 mt-1">Optionally filter students by batch</p>
                    </div>
                </div>

                <!-- Message Preview -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Message Preview</h4>
                    <p class="text-sm text-gray-600 bg-white p-3 rounded border border-gray-200">
                        Dear Parent, <span class="text-blue-600">{student_name}</span> scored 
                        <span class="text-blue-600">{marks}</span>/<span class="text-blue-600">{total_marks}</span> 
                        (<span class="text-blue-600">{grade}</span>) in <span class="text-blue-600">{exam_name}</span>. 
                        Keep up the good work!
                    </p>
                    <p class="text-xs text-gray-500 mt-2">
                        <strong>Note:</strong> Placeholders will be replaced with actual student data when sending.
                    </p>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800">How it works</h4>
                            <ul class="text-sm text-blue-700 mt-1 list-disc list-inside space-y-1">
                                <li>SMS will be sent to students who have exam results recorded</li>
                                <li>Messages will be sent to student's phone or guardian's phone</li>
                                <li>Each SMS includes exam name, marks obtained, total marks, and grade</li>
                                <li>All sent messages will be logged for tracking</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <x-ui.button type="submit" id="sendBtn">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Send Result Notifications
                    </x-ui.button>
                    
                    <a href="{{ route('dashboard.communication.index') }}" class="text-gray-600 hover:text-gray-800">
                        Back to SMS Dashboard
                    </a>
                </div>
            </form>

            <!-- Progress indicator -->
            <div id="sendProgress" class="hidden mt-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="animate-spin h-5 w-5 text-blue-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-blue-700 font-medium">Sending result notifications...</span>
                    </div>
                </div>
            </div>

            <!-- Result display -->
            <div id="sendResult" class="hidden mt-6"></div>
        </x-ui.card-content>
    </x-ui.card>

    <!-- Alternative: Legacy Form for Direct Submission -->
    <x-ui.card>
        <x-ui.card-header>
            <x-ui.card-title>Quick Send (Legacy)</x-ui.card-title>
            <x-ui.card-description>Simple form for sending result notifications</x-ui.card-description>
        </x-ui.card-header>
        <x-ui.card-content>
            <form action="{{ route('dashboard.communication.send-result') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-ui.label for="legacy_batch_id">Select Batch</x-ui.label>
                        <x-ui.select-native name="batch_id" id="legacy_batch_id" required>
                            <option value="">Choose a batch...</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                            @endforeach
                        </x-ui.select-native>
                    </div>
                    
                    <div>
                        <x-ui.label for="legacy_exam_id">Select Exam</x-ui.label>
                        <x-ui.select-native name="exam_id" id="legacy_exam_id" required>
                            <option value="">Choose an exam...</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}">{{ $exam->title }}</option>
                            @endforeach
                        </x-ui.select-native>
                    </div>
                </div>
                
                <x-ui.button type="submit" variant="secondary">
                    Send Results
                </x-ui.button>
            </form>
        </x-ui.card-content>
    </x-ui.card>
</div>

@push('scripts')
<script>
document.getElementById('resultNotificationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = document.getElementById('sendBtn');
    const progressDiv = document.getElementById('sendProgress');
    const resultDiv = document.getElementById('sendResult');
    
    const examId = formData.get('exam_id');
    if (!examId) {
        alert('Please select an exam');
        return;
    }
    
    // Show progress, hide result
    submitBtn.disabled = true;
    progressDiv.classList.remove('hidden');
    resultDiv.classList.add('hidden');
    
    try {
        const response = await fetch('{{ route("dashboard.communication.result-notification") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                exam_id: formData.get('exam_id'),
                batch_id: formData.get('batch_id') || null,
            }),
        });
        
        const data = await response.json();
        
        // Hide progress
        progressDiv.classList.add('hidden');
        submitBtn.disabled = false;
        
        // Show result - Requirement 13.4: Log SMS with result details
        if (data.success) {
            resultDiv.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-green-700 font-medium">${data.message}</span>
                    </div>
                    <div class="mt-3 grid grid-cols-3 gap-4 text-sm">
                        <div class="bg-white rounded p-2 text-center">
                            <p class="text-gray-500">Total</p>
                            <p class="text-lg font-bold text-gray-900">${data.data.total}</p>
                        </div>
                        <div class="bg-white rounded p-2 text-center">
                            <p class="text-gray-500">Successful</p>
                            <p class="text-lg font-bold text-green-600">${data.data.successful}</p>
                        </div>
                        <div class="bg-white rounded p-2 text-center">
                            <p class="text-gray-500">Failed</p>
                            <p class="text-lg font-bold text-red-600">${data.data.failed}</p>
                        </div>
                    </div>
                    ${data.data.exam_name ? `<p class="mt-2 text-sm text-green-600">Exam: ${data.data.exam_name}</p>` : ''}
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="text-red-700 font-medium">${data.message}</span>
                    </div>
                </div>
            `;
        }
        resultDiv.classList.remove('hidden');
        
    } catch (error) {
        progressDiv.classList.add('hidden');
        submitBtn.disabled = false;
        resultDiv.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span class="text-red-700 font-medium">An error occurred. Please try again.</span>
                </div>
            </div>
        `;
        resultDiv.classList.remove('hidden');
    }
});
</script>
@endpush
@endsection
