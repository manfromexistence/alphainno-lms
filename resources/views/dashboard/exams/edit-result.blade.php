@extends('layouts.admin')

@section('title', 'Edit Result')
@section('page-title', 'ফলাফল সম্পাদনা করুন')
@section('page-description', 'Edit exam result marks and grade')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Result</h2>
                    <p class="text-gray-500 mt-1">{{ $exam->title }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('dashboard.exams.view-result', [$exam, $result]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </a>
                </div>
            </div>
        </div>

        <!-- Student Information -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Information</h3>
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 h-16 w-16">
                    <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center">
                        <span class="text-indigo-600 font-bold text-xl">
                            {{ substr($result->student->name_bn ?? $result->student->user->name ?? 'S', 0, 2) }}
                        </span>
                    </div>
                </div>
                <div>
                    <div class="text-lg font-semibold text-gray-900">
                        {{ $result->student->user->name ?? $result->student->name_bn ?? 'N/A' }}
                    </div>
                    <div class="text-sm text-gray-500">
                        ID: {{ $result->student->registration_no ?? 'N/A' }}
                    </div>
                    <div class="text-sm text-gray-500">
                        Batch: {{ $result->student->batch->name ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Edit Marks and Grade</h3>

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('dashboard.exams.update-result', [$exam, $result]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Obtained Marks -->
                    <div>
                        <label for="obtained_marks" class="block text-sm font-semibold text-gray-700 mb-2">
                            Obtained Marks *
                        </label>
                        <input type="number" 
                               name="obtained_marks" 
                               id="obtained_marks" 
                               value="{{ old('obtained_marks', $result->obtained_marks) }}"
                               min="0" 
                               max="{{ $exam->total_marks }}"
                               step="0.01"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Maximum: {{ $exam->total_marks }} marks</p>
                    </div>

                    <!-- Grade -->
                    <div>
                        <label for="grade" class="block text-sm font-semibold text-gray-700 mb-2">
                            Grade
                        </label>
                        <select name="grade" 
                                id="grade" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent">
                            <option value="">Auto Calculate</option>
                            <option value="A+" {{ old('grade', $result->grade) === 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A" {{ old('grade', $result->grade) === 'A' ? 'selected' : '' }}>A</option>
                            <option value="A-" {{ old('grade', $result->grade) === 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('grade', $result->grade) === 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B" {{ old('grade', $result->grade) === 'B' ? 'selected' : '' }}>B</option>
                            <option value="B-" {{ old('grade', $result->grade) === 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="C+" {{ old('grade', $result->grade) === 'C+' ? 'selected' : '' }}>C+</option>
                            <option value="C" {{ old('grade', $result->grade) === 'C' ? 'selected' : '' }}>C</option>
                            <option value="C-" {{ old('grade', $result->grade) === 'C-' ? 'selected' : '' }}>C-</option>
                            <option value="D" {{ old('grade', $result->grade) === 'D' ? 'selected' : '' }}>D</option>
                            <option value="F" {{ old('grade', $result->grade) === 'F' ? 'selected' : '' }}>F</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Leave empty to auto-calculate based on marks</p>
                    </div>
                </div>

                <!-- Current Result Summary -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Current Result</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <div class="text-xs text-gray-500">Obtained Marks</div>
                            <div class="text-lg font-bold text-gray-900">{{ $result->obtained_marks }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Total Marks</div>
                            <div class="text-lg font-bold text-gray-900">{{ $result->total_marks }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Percentage</div>
                            <div class="text-lg font-bold text-gray-900">
                                {{ number_format(($result->obtained_marks / $result->total_marks) * 100, 2) }}%
                            </div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Grade</div>
                            <div class="text-lg font-bold text-gray-900">{{ $result->grade }}</div>
                        </div>
                    </div>
                </div>

                <!-- Exam Information -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h4 class="text-sm font-semibold text-blue-700 mb-3">Exam Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-blue-600 font-medium">Total Marks:</span>
                            <span class="text-blue-900 ml-2">{{ $exam->total_marks }}</span>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">Pass Marks:</span>
                            <span class="text-blue-900 ml-2">{{ $exam->pass_marks }}</span>
                        </div>
                        <div>
                            <span class="text-blue-600 font-medium">Total Questions:</span>
                            <span class="text-blue-900 ml-2">{{ $exam->questions->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Auto-Calculate Preview -->
                <div class="mt-6 p-4 bg-green-50 rounded-lg" id="preview" style="display: none;">
                    <h4 class="text-sm font-semibold text-green-700 mb-3">Preview (Auto-calculated)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <div class="text-xs text-green-600">New Percentage</div>
                            <div class="text-lg font-bold text-green-900" id="preview-percentage">-</div>
                        </div>
                        <div>
                            <div class="text-xs text-green-600">New Grade</div>
                            <div class="text-lg font-bold text-green-900" id="preview-grade">-</div>
                        </div>
                        <div>
                            <div class="text-xs text-green-600">Status</div>
                            <div class="text-lg font-bold" id="preview-status">-</div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('dashboard.exams.view-result', [$exam, $result]) }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors">
                        Update Result
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-calculate preview
        const obtainedMarksInput = document.getElementById('obtained_marks');
        const gradeSelect = document.getElementById('grade');
        const previewDiv = document.getElementById('preview');
        const totalMarks = {{ $exam->total_marks }};
        const passMarks = {{ $exam->pass_marks }};

        function calculateGrade(percentage) {
            if (percentage >= 80) return 'A+';
            if (percentage >= 70) return 'A';
            if (percentage >= 60) return 'A-';
            if (percentage >= 50) return 'B';
            if (percentage >= 40) return 'C';
            if (percentage >= 33) return 'D';
            return 'F';
        }

        function updatePreview() {
            const obtainedMarks = parseFloat(obtainedMarksInput.value) || 0;
            const percentage = (obtainedMarks / totalMarks) * 100;
            const grade = gradeSelect.value || calculateGrade(percentage);
            const passed = obtainedMarks >= passMarks;

            document.getElementById('preview-percentage').textContent = percentage.toFixed(2) + '%';
            document.getElementById('preview-grade').textContent = grade;
            
            const statusEl = document.getElementById('preview-status');
            if (passed) {
                statusEl.textContent = 'PASSED';
                statusEl.className = 'text-lg font-bold text-green-900';
            } else {
                statusEl.textContent = 'FAILED';
                statusEl.className = 'text-lg font-bold text-red-900';
            }

            previewDiv.style.display = 'block';
        }

        obtainedMarksInput.addEventListener('input', updatePreview);
        gradeSelect.addEventListener('change', updatePreview);

        // Initial preview
        updatePreview();
    </script>
@endsection
