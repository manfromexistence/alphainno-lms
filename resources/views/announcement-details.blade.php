@extends('layouts.frontend')

@section('title', $announcement->title . ' - XYZ School & College')

@section('content')
    <div class="py-16 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($announcement->type === 'notice') bg-blue-100 text-blue-800
                            @elseif($announcement->type === 'event') bg-green-100 text-green-800
                            @elseif($announcement->type === 'meeting') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($announcement->type) }}
                        </span>
                        <span class="text-sm text-gray-500">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ $announcement->published_at ? $announcement->published_at->format('F d, Y') : $announcement->created_at->format('F d, Y') }}
                        </span>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $announcement->title }}</h1>

                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>

                    @if($announcement->attachment)
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <h3 class="text-lg font-semibold mb-3">Attachment</h3>
                            <a href="{{ asset('storage/' . $announcement->attachment) }}" target="_blank" class="inline-flex items-center gap-2 text-primary hover:underline">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download Attachment
                            </a>
                        </div>
                    @endif
                </div>
                <div class="bg-gray-50 px-8 py-4 border-t border-gray-100">
                    <a href="{{ url('/') }}" class="text-primary font-medium hover:underline">&larr; Back to Home</a>
                </div>
            </div>
        </div>
    </div>
@endsection
