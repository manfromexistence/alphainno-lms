@extends('layouts.admin')

@section('title', 'Manage Batches')
@section('page-title', 'Batch Management')
@section('page-description', 'Manage all batches and class schedules')

@section('content')
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h2 class="text-lg font-semibold text-gray-900">All Batches</h2>
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                    {{ $batches->total() ?? $batches->count() }} Total
                </span>
            </div>
            <a href="{{ route('dashboard.batches.create') }}"
                class="inline-flex items-center px-4 py-2 bg-bd-green text-white rounded-lg hover:bg-bd-green-dark transition-colors font-medium text-sm shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Batch
            </a>
        </div>

        @php
            $headers = [
                ['key' => 'batch', 'label' => 'Batch'],
                ['key' => 'course', 'label' => 'Course'],
                ['key' => 'schedule', 'label' => 'Schedule'],
                ['key' => 'students', 'label' => 'Students'],
                ['key' => 'status', 'label' => 'Status'],
                ['key' => 'actions', 'label' => 'Actions'],
            ];
        @endphp

        <x-ui.data-table id="batch-table" :headers="$headers" :rows="$batches" :route="route('dashboard.batches.index')">
            @forelse($batches as $batch)
                <tr class="hover:bg-gray-50 transition-colors">
                    <!-- Batch -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center text-white font-bold shadow-sm">
                                {{ strtoupper(substr($batch->name ?? 'B', 0, 2)) }}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $batch->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $batch->code ?? 'No code' }}</div>
                            </div>
                        </div>
                    </td>

                    <!-- Course -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                            {{ $batch->course->name ?? 'No course' }}
                        </span>
                    </td>

                    <!-- Schedule -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $batch->schedule ?? 'Not scheduled' }}
                    </td>

                    <!-- Students -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-900">{{ $batch->students_count ?? 0 }}</span>
                            <span class="text-sm text-gray-500 ml-1">/ {{ $batch->max_students ?? '∞' }}</span>
                        </div>
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if (($batch->status ?? 'active') === 'active') bg-green-100 text-green-800
                            @elseif(($batch->status ?? 'active') === 'completed') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($batch->status ?? 'Active') }}
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                        <div class="flex justify-start space-x-2">
                            <a href="{{ route('dashboard.batches.show', $batch) }}"
                                class="text-gray-500 hover:text-blue-600 transition-colors" title="View">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="{{ route('dashboard.batches.edit', $batch) }}"
                                class="text-gray-500 hover:text-bd-green transition-colors" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('dashboard.batches.destroy', $batch) }}" method="POST" class="inline"
                                onsubmit="return confirmDelete(this, 'Are you sure you want to delete this batch? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-600 transition-colors"
                                    title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="text-sm font-medium text-gray-900">No batches found</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new batch.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-ui.data-table>
    </div>
@endsection
