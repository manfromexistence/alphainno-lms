@extends('layouts.admin')

@section('title', 'CQ Exam')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Exam Header -->
        <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-indigo-600">
                <h1 class="text-2xl font-bold text-white">{{ $exam->name }}</h1>
                <p class="text-purple-200">Creative Questions Exam</p>
            </div>
            
            <div class="px-6 py-4">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-sm text-gray-500">Total Marks</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $exam->total_marks }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Duration</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $exam->duration }} mins</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Scheduled</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $exam->scheduled_at?->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($submission)
        <!-- Submission Status -->
        <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Your Submission</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $submission->isEvaluated() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $submission->isEvaluated() ? 'Evaluated' : 'Pending Evaluation' }}
                    </span>
                    <span class="text-sm text-gray-500">Submitted {{ $submission->submitted_at->diffForHumans() }}</span>
                </div>
                
                @if($submission->isEvaluated())
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="text-center">
                        <p class="text-4xl font-bold text-indigo-600">{{ $submission->marks }}/{{ $exam->total_marks }}</p>
                        <p class="text-gray-500">Your Score</p>
                    </div>
                </div>
                
                @if($submission->feedback)
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-blue-800">Feedback:</p>
                    <p class="text-blue-700 mt-1">{{ $submission->feedback }}</p>
                </div>
                @endif
                @endif
                
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Uploaded Files ({{ $submission->file_count }}):</p>
                    <ul class="space-y-2">
                        @foreach($submission->files as $file)
                        <li class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                            {{ $file['original_name'] }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @else
        <!-- Upload Form -->
        <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Upload Your Answer</h2>
                <p class="text-sm text-gray-500">Upload your handwritten answers as PDF or images</p>
            </div>
            
            <form action="{{ route('student.exams.cq.upload', $exam) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                
                @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center" id="drop-zone">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-600">Drag and drop files here, or click to browse</p>
                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG up to 10MB each</p>
                    <input type="file" name="files[]" multiple accept=".pdf,.jpg,.jpeg,.png" class="hidden" id="file-input">
                    <button type="button" onclick="document.getElementById('file-input').click()" 
                            class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Select Files
                    </button>
                </div>
                
                <div id="file-list" class="mt-4 space-y-2"></div>
                
                <div class="mt-6">
                    <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                        Submit Answer
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Question Paper -->
        @if($exam->question_paper)
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Question Paper</h2>
            </div>
            <div class="p-6">
                <div class="prose max-w-none">
                    {!! nl2br(e($exam->question_paper)) !!}
                </div>
            </div>
        </div>
        @endif
        
        <div class="mt-6">
            <a href="{{ route('student.exams') }}" class="text-indigo-600 hover:text-indigo-500">← Back to Exams</a>
        </div>
    </div>
</div>

<script>
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const fileList = document.getElementById('file-list');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.add('border-indigo-500', 'bg-indigo-50'), false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.remove('border-indigo-500', 'bg-indigo-50'), false);
    });
    
    dropZone.addEventListener('drop', handleDrop, false);
    fileInput.addEventListener('change', handleFiles, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        handleFiles();
    }
    
    function handleFiles() {
        fileList.innerHTML = '';
        [...fileInput.files].forEach((file, i) => {
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
            div.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm text-gray-700">${file.name}</span>
                </div>
                <span class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
            `;
            fileList.appendChild(div);
        });
    }
</script>
@endsection
