@extends('layouts.admin')

@section('title', 'MCQ Exams')
@section('page-title', 'এমসিকিউ পরীক্ষা')
@section('page-description', 'Create and manage online MCQ assessments')

@section('content')
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h2 class="text-lg font-semibold text-gray-900">MCQ Exams</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $exams->total() }} Exams
                </span>
            </div>
            @if(!auth()->user()->isStudent())
                {{-- Only show Create button for admins and teachers --}}
                <a href="{{ route('dashboard.exams.create') }}" class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium text-sm shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create MCQ Exam
                </a>
            @endif
        </div>

        <!-- Exams List -->
        <div class="p-6">
            @if($exams->count() > 0)
                @php
                    $headers = [
                        ['key' => 'title', 'label' => 'Exam Title'],
                        ['key' => 'batch', 'label' => 'Batch'],
                        ['key' => 'course', 'label' => 'Course'],
                        ['key' => 'duration', 'label' => 'Duration'],
                        ['key' => 'marks', 'label' => 'Marks'],
                        ['key' => 'status', 'label' => 'Status'],
                        ['key' => 'actions', 'label' => 'Actions'],
                    ];
                @endphp

                <x-ui.data-table id="mcq-exams-table" :headers="$headers" :rows="$exams" :route="route('dashboard.exams.mcq')">
                    @foreach($exams as $exam)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $exam->title }}</div>
                                <div class="text-xs text-gray-500">{{ $exam->questions_count ?? 0 }} Questions</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $exam->batch->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $exam->course->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $exam->duration_minutes }} min
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $exam->total_marks }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($exam->status === 'active') bg-green-100 text-green-800
                                    @elseif($exam->status === 'draft') bg-gray-100 text-gray-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst($exam->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if(auth()->user()->isStudent())
                                    {{-- Students only see "Take Exam" action --}}
                                    <a href="{{ route('student.exams.start', $exam) }}" class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                                        Take Exam
                                    </a>
                                @else
                                    {{-- Admins and Teachers see View, Edit, Delete actions --}}
                                    <div class="flex space-x-2">
                                        <a href="{{ route('dashboard.exams.show', $exam) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        <a href="{{ route('dashboard.exams.edit', $exam) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form action="{{ route('dashboard.exams.destroy', $exam) }}" method="POST" class="inline" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this exam? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </x-ui.data-table>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No MCQ Exams Yet</h3>
                    <p class="text-gray-500 max-w-sm mx-auto mb-4">Get started by creating your first MCQ exam.</p>
                    <a href="{{ route('dashboard.exams.create') }}" class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors">
                        Create MCQ Exam
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection