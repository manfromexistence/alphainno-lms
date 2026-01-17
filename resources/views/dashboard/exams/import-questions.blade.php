@extends('layouts.admin')

@section('title', 'Import Questions')
@section('page-title', 'প্রশ্ন আমদানি')
@section('page-description', 'Import questions from Excel, CSV, or JSON')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('dashboard.exams.show', $exam) }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Exam
        </a>
    </div>

    <!-- Exam Info -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
        <h2 class="text-2xl font-bold text-gray-900">{{ $exam->title }}</h2>
        <p class="text-gray-500 mt-1">Import questions in bulk</p>
    </div>

    <!-- Upload Form -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload File</h3>

        @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('dashboard.exams.process-import', $exam) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Select File Format</label>
                <div class="grid grid-cols-3 gap-4">
                    <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-bd-green transition">
                        <input type="radio" name="format" value="excel" checked class="sr-only peer">
                        <div class="flex items-center space-x-3 peer-checked:text-bd-green">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                <path d="M14 2v6h6M9 13h6M9 17h6M9 9h1"/>
                            </svg>
                            <div>
                                <div class="font-semibold">Excel</div>
                                <div class="text-xs text-gray-500">.xlsx, .xls</div>
                            </div>
                        </div>
                        <div class="absolute top-2 right-2 w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-bd-green peer-checked:bg-bd-green"></div>
                    </label>

                    <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-bd-green transition">
                        <input type="radio" name="format" value="csv" class="sr-only peer">
                        <div class="flex items-center space-x-3 peer-checked:text-bd-green">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                <path d="M14 2v6h6M12 18v-6M9 15l3-3 3 3"/>
                            </svg>
                            <div>
                                <div class="font-semibold">CSV</div>
                                <div class="text-xs text-gray-500">.csv</div>
                            </div>
                        </div>
                        <div class="absolute top-2 right-2 w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-bd-green peer-checked:bg-bd-green"></div>
                    </label>

                    <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-bd-green transition">
                        <input type="radio" name="format" value="json" class="sr-only peer">
                        <div class="flex items-center space-x-3 peer-checked:text-bd-green">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                <path d="M14 2v6h6M10 12h4M10 16h4"/>
                            </svg>
                            <div>
                                <div class="font-semibold">JSON</div>
                                <div class="text-xs text-gray-500">.json</div>
                            </div>
                        </div>
                        <div class="absolute top-2 right-2 w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-bd-green peer-checked:bg-bd-green"></div>
                    </label>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Upload File</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-bd-green transition" id="drop-zone">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-600">Drag and drop file here, or click to browse</p>
                    <p class="text-xs text-gray-500 mt-1">Maximum file size: 10MB</p>
                    <input type="file" name="file" id="file-input" accept=".xlsx,.xls,.csv,.json" required class="hidden">
                    <button type="button" onclick="document.getElementById('file-input').click()" 
                            class="mt-4 px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark">
                        Select File
                    </button>
                </div>
                <div id="file-name" class="mt-2 text-sm text-gray-600"></div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('dashboard.exams.show', $exam) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark">
                    Import Questions
                </button>
            </div>
        </form>
    </div>

    <!-- Format Instructions -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">File Format Instructions</h3>

        <div class="space-y-4">
            <!-- Excel/CSV Format -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-2">Excel/CSV Format:</h4>
                <p class="text-sm text-gray-600 mb-2">Your file should have the following columns:</p>
                <div class="bg-gray-50 p-4 rounded-lg overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-2 text-left">question_text</th>
                                <th class="px-4 py-2 text-left">type</th>
                                <th class="px-4 py-2 text-left">marks</th>
                                <th class="px-4 py-2 text-left">option_a</th>
                                <th class="px-4 py-2 text-left">option_b</th>
                                <th class="px-4 py-2 text-left">option_c</th>
                                <th class="px-4 py-2 text-left">option_d</th>
                                <th class="px-4 py-2 text-left">correct_answer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <td class="px-4 py-2">What is 2+2?</td>
                                <td class="px-4 py-2">mcq</td>
                                <td class="px-4 py-2">1</td>
                                <td class="px-4 py-2">3</td>
                                <td class="px-4 py-2">4</td>
                                <td class="px-4 py-2">5</td>
                                <td class="px-4 py-2">6</td>
                                <td class="px-4 py-2">4</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <strong>Note:</strong> For non-MCQ questions, leave option columns empty. Type can be: mcq, short, long
                </p>
            </div>

            <!-- JSON Format -->
            <div>
                <h4 class="font-semibold text-gray-900 mb-2">JSON Format:</h4>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <pre class="text-xs text-gray-700 overflow-x-auto"><code>[
  {
    "question_text": "What is 2+2?",
    "type": "mcq",
    "marks": 1,
    "options": ["3", "4", "5", "6"],
    "correct_answer": "4"
  },
  {
    "question_text": "Explain photosynthesis",
    "type": "long",
    "marks": 5
  }
]</code></pre>
                </div>
            </div>

            <!-- Download Template -->
            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                <div>
                    <h4 class="font-semibold text-blue-900">Need a template?</h4>
                    <p class="text-sm text-blue-700">Download our sample template to get started</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('dashboard.exams.download-template', ['format' => 'excel']) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Excel Template
                    </a>
                    <a href="{{ route('dashboard.exams.download-template', ['format' => 'csv']) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        CSV Template
                    </a>
                    <a href="{{ route('dashboard.exams.download-template', ['format' => 'json']) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        JSON Template
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const fileInput = document.getElementById('file-input');
    const dropZone = document.getElementById('drop-zone');
    const fileName = document.getElementById('file-name');

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            fileName.textContent = `Selected: ${this.files[0].name}`;
        }
    });

    // Drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.add('border-bd-green', 'bg-green-50'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.remove('border-bd-green', 'bg-green-50'), false);
    });

    dropZone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        if (files.length > 0) {
            fileName.textContent = `Selected: ${files[0].name}`;
        }
    }, false);
</script>
@endsection
