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
                    class="{{ Request::is('/') ? 'bg-[#006A4E] text-white' : 'text-gray-700 hover:text-[#006A4E]' }} px-4 py-2 rounded-md text-md font-medium">
                    হোম
                </a>
                <a href="{{ route('courses') }}"
                    class="{{ Request::routeIs('courses') ? 'bg-[#006A4E] text-white' : 'text-gray-700 hover:text-[#006A4E]' }} px-4 py-2 rounded-md text-md font-medium">কোর্স</a>
                <a href="{{ route('students') }}"
                    class="{{ Request::routeIs('students') ? 'bg-[#006A4E] text-white' : 'text-gray-700 hover:text-[#006A4E]' }} px-4 py-2 rounded-md text-md font-medium">শিক্ষার্থী</a>
                <a href="{{ route('results') }}"
                    class="{{ Request::routeIs('results') ? 'bg-[#006A4E] text-white' : 'text-gray-700 hover:text-[#006A4E]' }} px-4 py-2 rounded-md text-md font-medium">পরীক্ষার
                    ফলাফল</a>
                                <a href="{{ route('about') }}"
                    class="{{ Request::routeIs('about') ? 'bg-[#006A4E] text-white' : 'text-gray-700 hover:text-[#006A4E]' }} px-4 py-2 rounded-md text-md font-medium">পরিচিতি</a>
                    <a href="{{ route('contact') }}"
                    class="{{ Request::routeIs('contact') ? 'bg-[#006A4E] text-white' : 'text-gray-700 hover:text-[#006A4E]' }} px-4 py-2 rounded-md text-md font-medium">যোগাযোগ</a>
            </nav>

            @auth
                <a href="{{ route('dashboard') }}"
                    class="bg-[#006A4E] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#005a42] transition text-center">
                    View Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="bg-[#006A4E] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#005a42] transition text-center">
                    Login
                </a>
            @endauth
        </div>
    </div>
</header>