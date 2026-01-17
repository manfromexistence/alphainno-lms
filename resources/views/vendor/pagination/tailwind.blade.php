@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between px-2">
        <div class="flex-1 text-sm text-gray-500 hidden sm:block">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </div>
        
        <div class="flex items-center space-x-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium rounded-md text-gray-300 bg-white cursor-not-allowed">
                    <span class="sr-only">Previous</span>
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>
                    <span class="ml-1 hidden sm:inline">Previous</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <span class="sr-only">Previous</span>
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>
                    <span class="ml-1 hidden sm:inline">Previous</span>
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="hidden sm:flex items-center space-x-1">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="inline-flex items-center justify-center w-9 h-9 border border-transparent text-sm font-medium text-gray-500">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="inline-flex items-center justify-center w-9 h-9 border border-bd-green text-sm font-medium rounded-md text-white bg-bd-green cursor-default shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 border border-gray-200 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Mobile Current Page Indicator --}}
            <span class="sm:hidden text-sm text-gray-700 font-medium px-2">
                Page {{ $paginator->currentPage() }}
            </span>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <span class="mr-1 hidden sm:inline">Next</span>
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="sr-only">Next</span>
                </a>
            @else
                <span class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium rounded-md text-gray-300 bg-white cursor-not-allowed">
                    <span class="mr-1 hidden sm:inline">Next</span>
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>
                    <span class="sr-only">Next</span>
                </span>
            @endif
        </div>
    </nav>
@endif
