@extends('layouts.admin')

@section('title', 'Edit Course')
@section('page-title', 'Edit Course')
@section('page-description', 'Update course information')

@section('content')
    <div class="max-w-7xl mx-auto">
        <form action="{{ route('dashboard.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Global Validation Error Display --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <strong class="font-semibold">Please fix the following errors:</strong>
                    </div>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Course Information</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-ui.text-input name="name" label="Course Name" required placeholder="Enter course name" :value="old('name', $course->name)" />
                    <x-ui.text-input name="code" label="Course Code" required placeholder="e.g., WEB001" :value="old('code', $course->code)" />
                    
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent transition-all outline-none text-gray-900 placeholder:text-gray-400"
                                  placeholder="Course description...">{{ old('description', $course->description) }}</textarea>
                        @error('description')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing & Duration -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pricing & Duration</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <x-ui.text-input name="price" label="Price (৳)" type="number" required placeholder="0.00" :value="old('price', $course->price)" />
                    <x-ui.text-input name="duration" label="Duration" type="number" placeholder="e.g., 6" :value="old('duration', $course->duration)" />
                    
                    <x-ui.select name="duration_unit" label="Duration Unit" :selected="old('duration_unit', $course->duration_unit)">
                        <option value="months">Months</option>
                        <option value="weeks">Weeks</option>
                        <option value="days">Days</option>
                        <option value="hours">Hours</option>
                    </x-ui.select>
                </div>
            </div>

            <!-- Category & Level -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Category & Level</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <x-ui.text-input name="category" label="Category" placeholder="e.g., Programming, Design" :value="old('category', $course->category)" />
                    
                    <x-ui.select name="class" label="Class (Grade)" :selected="old('class', $course->class)">
                        <option value="">Select Class</option>
                        @foreach(range(1, 12) as $c)
                            <option value="{{ $c }}">Class {{ $c }}</option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.select name="level" label="Difficulty Level" required :selected="old('level', $course->level)">
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </x-ui.select>
                </div>
            </div>

            <!-- Status & Image -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Status & Image</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-ui.select name="status" label="Status" required :selected="old('status', $course->status)">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="draft">Draft</option>
                    </x-ui.select>

                    <div class="md:col-span-1">
                        <x-ui.image-input name="image" label="Course Image" :value="$course->image" helperText="Upload a new thumbnail (JPEG, PNG, max 2MB). Leave empty to keep current." />
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mb-6">
                <a href="{{ route('dashboard.courses.index') }}"
                   class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium">
                    Update Course
                </button>
            </div>
        </form>

        <!-- Video Manager Section -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 mb-10">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Video Manager</h3>
                    <p class="text-sm text-gray-600 mt-1">Upload videos or add YouTube/Facebook/Vimeo links</p>
                </div>
                <button onclick="openVideoModal()" class="px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium text-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Video
                </button>
            </div>

            <div class="p-6">
                <div id="videosList" class="space-y-4">
                    @forelse($course->videos()->orderBy('order')->get() as $video)
                        <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg hover:border-bd-green transition-colors group" data-video-id="{{ $video->id }}">
                            <div class="flex-shrink-0 w-40 h-24 bg-gray-100 rounded-lg overflow-hidden relative">
                                @if($video->video_type === 'youtube' && $video->external_id)
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-400 to-pink-500" data-youtube-id="{{ $video->external_id }}" data-video-title="{{ $video->title }}">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                    </div>
                                @elseif($video->video_type === 'youtube')
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-400 to-pink-500">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                    </div>
                                @elseif($video->video_type === 'vimeo')
                                    <img src="{{ $video->thumbnail ? asset('storage/' . $video->thumbnail) : 'https://via.placeholder.com/160x90?text=Vimeo' }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
                                @elseif($video->thumbnail)
                                    <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-emerald-400 to-teal-500">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all flex items-center justify-center">
                                    <button onclick="playVideo({{ $video->id }}, '{{ $video->video_type }}', '{{ $video->external_id }}', '{{ $video->video_path }}')" class="opacity-0 group-hover:opacity-100 transition-opacity bg-white rounded-full p-2">
                                        <svg class="w-6 h-6 text-bd-green" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-base font-semibold text-gray-900 truncate">{{ $video->title }}</h4>
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $video->description ?? 'No description' }}</p>
                                <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100">
                                        {{ ucfirst($video->video_type) }}
                                    </span>
                                    @if($video->duration)
                                        <span>{{ gmdate('H:i:s', $video->duration) }}</span>
                                    @endif
                                    @if($video->is_preview)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">Preview</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="editVideo({{ $video->id }})" class="p-2 text-gray-500 hover:text-bd-green hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form action="{{ route('dashboard.courses.videos.destroy', [$course, $video]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this video?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No videos yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding your first video.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Video Modal -->
    <div id="videoModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white">
                <h3 class="text-xl font-semibold text-gray-900" id="modalTitle">Add Video</h3>
                <button onclick="closeVideoModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="videoForm" action="{{ route('dashboard.courses.videos.store', $course) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Video Title *</label>
                    <input type="text" name="title" id="videoTitle" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent transition-all outline-none" placeholder="Enter video title">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="videoDescription" rows="3" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent transition-all outline-none" placeholder="Video description..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Video Type *</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative flex items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-bd-green transition-colors">
                            <input type="radio" name="video_type" value="youtube" onchange="toggleVideoInput('youtube')" class="sr-only peer" checked>
                            <div class="text-center peer-checked:text-bd-green">
                                <svg class="w-8 h-8 mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                                <span class="text-sm font-medium">YouTube</span>
                            </div>
                            <div class="absolute inset-0 border-2 border-bd-green rounded-lg opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                        </label>

                        <label class="relative flex items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-bd-green transition-colors">
                            <input type="radio" name="video_type" value="vimeo" onchange="toggleVideoInput('vimeo')" class="sr-only peer">
                            <div class="text-center peer-checked:text-bd-green">
                                <svg class="w-8 h-8 mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.977 6.416c-.105 2.338-1.739 5.543-4.894 9.609-3.268 4.247-6.026 6.37-8.29 6.37-1.409 0-2.578-1.294-3.553-3.881L5.322 11.4C4.603 8.816 3.834 7.522 3.01 7.522c-.179 0-.806.378-1.881 1.132L0 7.197c1.185-1.044 2.351-2.084 3.501-3.128C5.08 2.701 6.266 1.984 7.055 1.91c1.867-.18 3.016 1.1 3.447 3.838.465 2.953.789 4.789.971 5.507.539 2.45 1.131 3.674 1.776 3.674.502 0 1.256-.796 2.265-2.385 1.004-1.589 1.54-2.797 1.612-3.628.144-1.371-.395-2.061-1.614-2.061-.574 0-1.167.121-1.777.391 1.186-3.868 3.434-5.757 6.762-5.637 2.473.06 3.628 1.664 3.493 4.797l-.013.01z"/>
                                </svg>
                                <span class="text-sm font-medium">Vimeo</span>
                            </div>
                            <div class="absolute inset-0 border-2 border-bd-green rounded-lg opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                        </label>

                        <label class="relative flex items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-bd-green transition-colors">
                            <input type="radio" name="video_type" value="upload" onchange="toggleVideoInput('upload')" class="sr-only peer">
                            <div class="text-center peer-checked:text-bd-green">
                                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <span class="text-sm font-medium">Upload</span>
                            </div>
                            <div class="absolute inset-0 border-2 border-bd-green rounded-lg opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                        </label>
                    </div>
                </div>

                <div id="externalUrlInput">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Video URL *</label>
                    <input type="text" name="external_id" id="videoExternalId" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent transition-all outline-none" placeholder="Paste YouTube or Vimeo URL">
                    <p class="text-xs text-gray-500 mt-1">Supports: youtube.com, youtu.be, vimeo.com, facebook.com/watch</p>
                </div>

                <div id="uploadInput" class="hidden">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Video File *</label>
                    <input type="file" name="video_file" id="videoFile" accept="video/*" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent transition-all outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-bd-green file:text-white hover:file:bg-bd-green-dark">
                    <p class="text-xs text-gray-500 mt-1">Max size: 500MB. Supported: MP4, MOV, AVI</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Thumbnail (Optional)</label>
                    <input type="file" name="thumbnail_file" id="videoThumbnail" accept="image/*" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent transition-all outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Duration (seconds)</label>
                        <input type="number" name="duration" id="videoDuration" min="0" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-bd-green focus:border-transparent transition-all outline-none" placeholder="e.g., 300">
                    </div>

                    <div class="flex items-end">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="is_preview" id="videoIsPreview" class="w-4 h-4 text-bd-green border-gray-300 rounded focus:ring-bd-green">
                            <span class="text-sm font-medium text-gray-700">Free Preview</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeVideoModal()" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium">
                        Save Video
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Video Player Modal -->
    <div id="playerModal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-5xl">
            <button onclick="closePlayerModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div id="playerContainer" class="bg-black rounded-lg overflow-hidden aspect-video">
                <!-- Video player will be injected here -->
            </div>
        </div>
    </div>

    <script>
        function getYoutubeId(url) {
            if (!url) return null;
            // Handle plain IDs (11 chars, alphanumeric, dashes, underscores)
            if (/^[a-zA-Z0-9_-]{11}$/.test(url)) {
                return url;
            }
            
            // Handle various URL formats
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = url.match(regExp);
            
            if (match && match[2] && match[2].length >= 11) {
                return match[2];
            }
            
            console.warn('Could not extract YouTube ID from:', url);
            return url; // Fallback to original
        }

        function toggleVideoInput(type) {
            const externalInput = document.getElementById('externalUrlInput');
            const uploadInput = document.getElementById('uploadInput');
            const externalIdField = document.getElementById('videoExternalId');
            const videoFileField = document.getElementById('videoFile');

            if (type === 'upload') {
                externalInput.classList.add('hidden');
                uploadInput.classList.remove('hidden');
                externalIdField.removeAttribute('required');
                videoFileField.setAttribute('required', 'required');
            } else {
                externalInput.classList.remove('hidden');
                uploadInput.classList.add('hidden');
                externalIdField.setAttribute('required', 'required');
                videoFileField.removeAttribute('required');
                
                // Update placeholder based on type
                if (type === 'youtube') {
                    externalIdField.placeholder = 'Paste YouTube URL (e.g., https://www.youtube.com/watch?v=...)';
                } else if (type === 'vimeo') {
                    externalIdField.placeholder = 'Paste Vimeo URL (e.g., https://vimeo.com/...)';
                }
            }
        }

        function openVideoModal() {
            document.getElementById('videoModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Add Video';
            document.getElementById('videoForm').reset();
            document.getElementById('videoForm').action = '{{ route("dashboard.courses.videos.store", $course) }}';
            document.getElementById('formMethod').value = 'POST';
            toggleVideoInput('youtube');
        }

        function closeVideoModal() {
            document.getElementById('videoModal').classList.add('hidden');
        }

        function editVideo(videoId) {
            // Redirect to edit page
            window.location.href = `/dashboard/courses/{{ $course->id }}/videos/${videoId}/edit`;
        }

        function playVideo(videoId, type, externalId, videoPath) {
            const modal = document.getElementById('playerModal');
            const container = document.getElementById('playerContainer');
            
            let playerHTML = '';
            
            if (type === 'youtube') {
                const youtubeId = getYoutubeId(externalId);
                playerHTML = `<iframe width="100%" height="100%" src="https://www.youtube.com/embed/${youtubeId}?autoplay=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
            } else if (type === 'vimeo') {
                playerHTML = `<iframe width="100%" height="100%" src="https://player.vimeo.com/video/${externalId}?autoplay=1" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>`;
            } else if (type === 'upload' && videoPath) {
                playerHTML = `<video width="100%" height="100%" controls autoplay><source src="{{ asset('storage/') }}/${videoPath}" type="video/mp4">Your browser does not support the video tag.</video>`;
            }
            
            container.innerHTML = playerHTML;
            modal.classList.remove('hidden');
        }

        function closePlayerModal() {
            const modal = document.getElementById('playerModal');
            const container = document.getElementById('playerContainer');
            container.innerHTML = '';
            modal.classList.add('hidden');
        }

        // Close modals on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeVideoModal();
                closePlayerModal();
            }
        });

        // Close modals on background click
        document.getElementById('videoModal').addEventListener('click', function(e) {
            if (e.target === this) closeVideoModal();
        });
        
        document.getElementById('playerModal').addEventListener('click', function(e) {
            if (e.target === this) closePlayerModal();
        });

        // Load YouTube thumbnails asynchronously to avoid 404 errors
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-youtube-id]').forEach(function(container) {
                const rawId = container.getAttribute('data-youtube-id');
                const videoId = getYoutubeId(rawId);
                const videoTitle = container.getAttribute('data-video-title');
                
                if (!videoId) return;
                
                // Try to load thumbnail with fetch to avoid console errors
                fetch(`https://img.youtube.com/vi/${videoId}/hqdefault.jpg`, { method: 'HEAD' })
                    .then(response => {
                        if (response.ok) {
                            // Thumbnail exists, replace icon with image
                            container.innerHTML = `<img src="https://img.youtube.com/vi/${videoId}/hqdefault.jpg" alt="${videoTitle}" class="w-full h-full object-cover">`;
                        }
                        // If not ok, keep the YouTube icon (already rendered)
                    })
                    .catch(() => {
                        // On error, keep the YouTube icon (already rendered)
                    });
            });
        });
    </script>
@endsection