@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">My Exams</h1>
        <p class="text-gray-600">View all exams for your batches</p>
    </div>

    <div class="bg-white rounded-lg shadow">
        @if($exams->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Marks</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($exams as $exam)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $exam->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $exam->batch->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $exam->type === 'mcq' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ strtoupper($exam->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($exam->start_time)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    {{ $exam->duration }} min
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    {{ $exam->total_marks }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $examDate = \Carbon\Carbon::parse($exam->start_time);
                                        $status = $examDate->isFuture() ? 'upcoming' : 'completed';
                                        $statusColor = $status === 'upcoming' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800';
                                    @endphp
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('dashboard.exams.show', $exam->id) }}" class="text-blue-600 hover:text-blue-900">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $exams->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No exams found</h3>
                <p class="mt-1 text-sm text-gray-500">There are no exams scheduled for your batches.</p>
            </div>
        @endif
    </div>
</div>
@endsection
