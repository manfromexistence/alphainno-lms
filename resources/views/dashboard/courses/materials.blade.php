@extends('layouts.admin')

@section('title', 'Study Materials')
@section('page-title', 'পাঠ্য উপকরণ')
@section('page-description', 'Upload and manage course resources and study materials')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Study Materials Management</h2>
                    <p class="text-sm text-gray-500 mt-1">Upload PDFs, videos, and other study materials for students</p>
                </div>
                <button onclick="openUploadModal()"
                   class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Upload Material
                </button>
            </div>
        </div>

        <!-- Course Materials Grid -->
        @if($courses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <!-- Course Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-4 py-3">
                            <h3 class="text-white font-semibold">{{ $course->name }}</h3>
                            <p class="text-blue-100 text-sm">{{ $course->code }}</p>
                        </div>

                        <!-- Materials List -->
                        <div class="p-4">
                            @if($course->materials_url || rand(0, 1)) <!-- Simulating some materials exist -->
                                <div class="space-y-3">
                                    <!-- Sample materials - in real app, these would come from database -->
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <div class="p-2 bg-red-100 rounded-lg mr-3">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">Course Syllabus.pdf</p>
                                            <p class="text-xs text-gray-500">2.3 MB • Uploaded 2 days ago</p>
                                        </div>
                                        <button class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">Introduction Video.mp4</p>
                                            <p class="text-xs text-gray-500">45.2 MB • Uploaded 1 week ago</p>
                                        </div>
                                        <button class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <div class="p-2 bg-green-100 rounded-lg mr-3">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">Practice Exercises.zip</p>
                                            <p class="text-xs text-gray-500">1.8 MB • Uploaded 3 days ago</p>
                                        </div>
                                        <button class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <button onclick="openUploadModal({{ $course->id }})"
                                       class="w-full inline-flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add More Materials
                                    </button>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-12 h-12 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                    </div>
                                    <h4 class="text-gray-500 font-medium mb-2">No materials uploaded</h4>
                                    <p class="text-gray-400 text-sm mb-4">Upload study materials for this course</p>
                                    <button onclick="openUploadModal({{ $course->id }})"
                                       class="inline-flex items-center px-3 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        Upload First Material
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No Courses Available</h3>
                    <p class="text-gray-500 max-w-sm mx-auto mb-6">Create courses first to start uploading study materials.</p>
                    <a href="{{ route('dashboard.courses.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create First Course
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Upload Modal (Hidden by default) -->
    <div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Upload Study Material</h3>
                    <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="material_course_id" class="block text-sm font-medium text-gray-700 mb-2">Course</label>
                        <select id="material_course_id" name="course_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="material_file" class="block text-sm font-medium text-gray-700 mb-2">File</label>
                        <input type="file" id="material_file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4,.avi,.mov,.zip,.rar"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="mt-1 text-sm text-gray-500">Supported: PDF, DOC, PPT, Video files, ZIP (Max 100MB)</p>
                    </div>

                    <div class="mb-4">
                        <label for="material_title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" id="material_title" name="title" placeholder="e.g., Chapter 1 Notes"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div class="mb-6">
                        <label for="material_description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                        <textarea id="material_description" name="description" rows="3" placeholder="Brief description of the material"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" onclick="closeUploadModal()"
                                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Upload Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openUploadModal(courseId = null) {
            document.getElementById('uploadModal').classList.remove('hidden');
            if (courseId) {
                document.getElementById('material_course_id').value = courseId;
            }
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
            document.getElementById('uploadForm').reset();
        }

        // Close modal when clicking outside
        document.getElementById('uploadModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeUploadModal();
            }
        });
    </script>
@endsection