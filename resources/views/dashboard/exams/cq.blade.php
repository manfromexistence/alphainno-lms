@extends('layouts.admin')

@section('title', 'Written Exams (CQ)')
@section('page-title', 'লিখিত পরীক্ষা (সৃজনশীল)')
@section('page-description', 'Manage written/creative question exams')

@section('content')
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h2 class="text-lg font-semibold text-gray-900">Written Exams (CQ)</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    {{ $exams->total() }} Exams
                </span>
            </div>
            @if(!auth()->user()->isStudent())
                {{-- Only show Create button for admins and teachers --}}
                <a href="{{ route('dashboard.exams.create') }}" class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium text-sm shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create CQ Exam
                </a>
            @endif
        </div>

        <!-- Stats Cards -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total CQ Exams</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $exams->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Active</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $exams->where('status', 'active')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Scheduled</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $exams->where('status', 'scheduled')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Draft</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $exams->where('status', 'draft')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exams List -->
        <div class="p-6">
            @if($exams->count() > 0)
                @php
                    $headers = [
                        ['key' => 'title', 'label' => 'Exam Title'],
                        ['key' => 'batch', 'label' => 'Batch'],
                        ['key' => 'course', 'label' => 'Course'],
                        ['key' => 'schedule', 'label' => 'Schedule'],
                        ['key' => 'duration', 'label' => 'Duration'],
                        ['key' => 'marks', 'label' => 'Marks'],
                        ['key' => 'submissions', 'label' => 'Submissions'],
                        ['key' => 'status', 'label' => 'Status'],
                        ['key' => 'actions', 'label' => 'Actions'],
                    ];
                @endphp

                <x-ui.data-table id="cq-exams-table" :headers="$headers" :rows="$exams" :route="route('dashboard.exams.cq')">
                    @foreach($exams as $exam)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $exam->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $exam->questions->count() }} Questions</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $exam->batch->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $exam->course->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($exam->start_time)
                                    <div>{{ $exam->start_time->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $exam->start_time->format('h:i A') }}</div>
                                @else
                                    <span class="text-gray-400">Not scheduled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $exam->duration_minutes }} min
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $exam->total_marks }}</span>
                                <span class="text-xs text-gray-400">(Pass: {{ $exam->pass_marks }})</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="font-semibold">{{ $exam->results->count() }}</span> students
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($exam->status === 'active') bg-green-100 text-green-800
                                    @elseif($exam->status === 'draft') bg-gray-100 text-gray-800
                                    @elseif($exam->status === 'scheduled') bg-blue-100 text-blue-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($exam->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if(auth()->user()->isStudent())
                                    {{-- Students only see "Take Exam" action --}}
                                    <a href="{{ $exam->type === 'mcq' ? route('student.exams.start', $exam) : route('student.exams.cq', $exam) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                                        Take Exam
                                    </a>
                                @else
                                    {{-- Admins and Teachers see View, Edit, Delete actions --}}
                                    <div class="flex space-x-2">
                                        <a href="{{ route('dashboard.exams.show', $exam) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('dashboard.exams.edit', $exam) }}" 
                                           class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('dashboard.exams.destroy', $exam) }}" method="POST" class="inline" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this exam? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </x-ui.data-table>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-purple-50 text-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No CQ Exams Yet</h3>
                    <p class="text-gray-500 max-w-sm mx-auto mb-4">Create your first written/creative question exam to get started.</p>
                    <a href="{{ route('dashboard.exams.create') }}" class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create CQ Exam
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
