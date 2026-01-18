@php
    $settingsService = app(\App\Services\SettingsService::class);
    $primaryColor = $settingsService->get('theme_primary_color', '#3b82f6');
@endphp

<header class="bg-white border-b sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('Alphainno LMS.png') }}" alt="Alpha LMS"
                        class="h-12 w-auto object-contain cursor-pointer hover:opacity-80 transition">
                </a>
            </div>

            <nav class="hidden md:flex items-center gap-0.5">
                <a href="{{ url('/') }}"
                    class="{{ Request::is('/') ? 'text-white' : 'text-gray-700' }} px-4 py-2 rounded-md text-md font-medium hover:opacity-80 transition"
                    style="{{ Request::is('/') ? 'background-color: ' . $primaryColor : '' }}">
                    হোম
                </a>
                <a href="{{ route('courses') }}"
                    class="{{ Request::routeIs('courses') ? 'text-white' : 'text-gray-700' }} px-4 py-2 rounded-md text-md font-medium hover:opacity-80 transition"
                    style="{{ Request::routeIs('courses') ? 'background-color: ' . $primaryColor : 'color: ' . $primaryColor }}">কোর্স</a>
                <a href="{{ route('students') }}"
                    class="{{ Request::routeIs('students') ? 'text-white' : 'text-gray-700' }} px-4 py-2 rounded-md text-md font-medium hover:opacity-80 transition"
                    style="{{ Request::routeIs('students') ? 'background-color: ' . $primaryColor : 'color: ' . $primaryColor }}">শিক্ষার্থী</a>
                <a href="{{ route('results') }}"
                    class="{{ Request::routeIs('results') ? 'text-white' : 'text-gray-700' }} px-4 py-2 rounded-md text-md font-medium hover:opacity-80 transition"
                    style="{{ Request::routeIs('results') ? 'background-color: ' . $primaryColor : 'color: ' . $primaryColor }}">পরীক্ষার
                    ফলাফল</a>
                                <a href="{{ route('about') }}"
                    class="{{ Request::routeIs('about') ? 'text-white' : 'text-gray-700' }} px-4 py-2 rounded-md text-md font-medium hover:opacity-80 transition"
                    style="{{ Request::routeIs('about') ? 'background-color: ' . $primaryColor : 'color: ' . $primaryColor }}">পরিচিতি</a>
                    <a href="{{ route('contact') }}"
                    class="{{ Request::routeIs('contact') ? 'text-white' : 'text-gray-700' }} px-4 py-2 rounded-md text-md font-medium hover:opacity-80 transition"
                    style="{{ Request::routeIs('contact') ? 'background-color: ' . $primaryColor : 'color: ' . $primaryColor }}">যোগাযোগ</a>
            </nav>

            @auth
                <a href="{{ route('dashboard') }}"
                    class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-[{{ $primaryColor }}] transition text-center">
                    View Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-[{{ $primaryColor }}] transition text-center">
                    Login
                </a>
            @endauth
        </div>
    </div>
</header>