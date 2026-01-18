@php
    $settingsService = app(\App\Services\SettingsService::class);
    $primaryColor = $settingsService->get('theme_primary_color', '#3b82f6');
    $primaryForeground = $settingsService->get('theme_primary_foreground', '#ffffff');
@endphp

<header class="bg-white border-b sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('logo.png') }}" alt="Alpha LMS"
                        class="h-12 w-auto object-contain cursor-pointer hover:opacity-80 transition">
                </a>
            </div>

            <nav class="hidden md:flex items-center gap-0.5">
                <a href="{{ url('/') }}"
                    class="px-4 py-2 rounded-md text-md font-medium hover:opacity-80 transition"
                    style="{{ Request::is('/') ? 'background-color: ' . $primaryColor . '; color: ' . $primaryForeground : 'color: #374151' }}">
                    হোম
                </a>
                <a href="{{ route('courses') }}"
                    class="px-4 py-2 rounded-md text-md font-medium hover:opacity-80 transition"
                    style="{{ Request::routeIs('courses') ? 'background-color: ' . $primaryColor . '; color: ' . $primaryForeground : 'color: ' . $primaryColor }}">কোর্স</a>
                <a href="{{ route('students') }}"
                    class="px-4 py-2 rounded-md text-md font-medium hover:opacity-80 transition"
                    style="{{ Request::routeIs('students') ? 'background-color: ' . $primaryColor . '; color: ' . $primaryForeground : 'color: ' . $primaryColor }}">শিক্ষার্থী</a>
                <a href="{{ route('results') }}"
                    class="px-4 py-2 rounded-md text-md font-medium hover:opacity-80 transition"
                    style="{{ Request::routeIs('results') ? 'background-color: ' . $primaryColor . '; color: ' . $primaryForeground : 'color: ' . $primaryColor }}">পরীক্ষার ফলাফল</a>
                <a href="{{ route('about') }}"
                    class="px-4 py-2 rounded-md text-md font-medium hover:opacity-80 transition"
                    style="{{ Request::routeIs('about') ? 'background-color: ' . $primaryColor . '; color: ' . $primaryForeground : 'color: ' . $primaryColor }}">পরিচিতি</a>
                <a href="{{ route('contact') }}"
                    class="px-4 py-2 rounded-md text-md font-medium hover:opacity-80 transition"
                    style="{{ Request::routeIs('contact') ? 'background-color: ' . $primaryColor . '; color: ' . $primaryForeground : 'color: ' . $primaryColor }}">যোগাযোগ</a>
            </nav>

            @auth
                <a href="{{ route('dashboard') }}"
                    class="px-4 py-2 rounded-md text-sm font-medium hover:opacity-90 transition text-center"
                    style="background-color: {{ $primaryColor }}; color: {{ $primaryForeground }}">
                    View Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="px-4 py-2 rounded-md text-sm font-medium hover:opacity-90 transition text-center"
                    style="background-color: {{ $primaryColor }}; color: {{ $primaryForeground }}">
                    Login
                </a>
            @endauth
        </div>
    </div>
</header>
