@extends('layouts.frontend')

@section('title', 'XYZ School & College - স্বাগতম')

@push('styles')
    <style>
        .slide {
            display: none;
            animation: fadeIn 0.5s;
        }

        .slide.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Hero Slider -->
    <section class="hero hero--image hero--dark !p-0 flex items-center justify-center !bg-white">
        <div class="hero-inner relative h-screen overflow-hidden !w-full !max-w-none !m-0 !p-0 px-0!">
            <!-- Slide 1 -->
            <div class="slide active absolute inset-0 w-full h-full">
                <img src="{{ $page ? $page->getContent('slide1_image', 'https://plus.unsplash.com/premium_photo-1677567996070-68fa4181775a?q=80&w=1172&auto=format&fit=crop') : 'https://plus.unsplash.com/premium_photo-1677567996070-68fa4181775a?q=80&w=1172&auto=format&fit=crop' }}" alt="Students"
                    class="w-full h-full object-cover brightness-75">
                <div class="absolute inset-0 flex items-center justify-center bg-black/40 !mix-blend-normal z-10">
                    <div class="text-center px-4 max-w-5xl mx-auto">
                        <h2 class="text-4xl md:text-6xl font-bold mb-4 !text-white drop-shadow-xl leading-tight opacity-100">{{ $page ? $page->getContent('slide1_title', 'স্বাগতম এক্সওয়াইজেডস স্কুল এন্ড') : 'স্বাগতম এক্সওয়াইজেডস স্কুল এন্ড' }}</h2>
                        <h3 class="text-2xl md:text-3xl font-semibold !text-white drop-shadow-lg opacity-100">{{ $page ? $page->getContent('slide1_subtitle', 'কলেজে এর পক্ষ থেকে!') : 'কলেজে এর পক্ষ থেকে!' }}</h3>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="slide absolute inset-0 w-full h-full">
                <img src="{{ $page ? $page->getContent('slide2_image', 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=1920') : 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=1920' }}" alt="Campus"
                    class="w-full h-full object-cover brightness-75">
                <div class="absolute inset-0 flex items-center justify-center bg-black/40 !mix-blend-normal z-10">
                    <div class="text-center px-4">
                        <h2 class="text-4xl md:text-6xl font-bold mb-4 !text-white drop-shadow-lg leading-tight opacity-100">{{ $page ? $page->getContent('slide2_title', 'শিক্ষার আলোয় আলোকিত') : 'শিক্ষার আলোয় আলোকিত' }}</h2>
                        <h3 class="text-2xl md:text-3xl font-semibold !text-white drop-shadow-md opacity-100">{{ $page ? $page->getContent('slide2_subtitle', 'ভবিষ্যৎ প্রজন্ম') : 'ভবিষ্যৎ প্রজন্ম' }}</h3>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="slide absolute inset-0 w-full h-full">
                <img src="{{ $page ? $page->getContent('slide3_image', 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=1920') : 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=1920' }}" alt="Learning"
                    class="w-full h-full object-cover brightness-75">
                <div class="absolute inset-0 flex items-center justify-center bg-black/40 !mix-blend-normal z-10">
                    <div class="text-center px-4">
                        <h2 class="text-4xl md:text-6xl font-bold mb-4 !text-white drop-shadow-lg leading-tight opacity-100">{{ $page ? $page->getContent('slide3_title', 'মানসম্মত শিক্ষা') : 'মানসম্মত শিক্ষা' }}</h2>
                        <h3 class="text-2xl md:text-3xl font-semibold !text-white drop-shadow-md opacity-100">{{ $page ? $page->getContent('slide3_subtitle', 'আধুনিক শিক্ষা ব্যবস্থা') : 'আধুনিক শিক্ষা ব্যবস্থা' }}</h3>
                    </div>
                </div>
            </div>

            <!-- Bottom Navigation -->
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-3">
                <!-- Previous Button -->
                <button onclick="prevSlide()"
                    class="bg-white bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 transition">
                    <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Dots -->
                <div class="flex gap-2">
                    <button onclick="goToSlide(0)" class="dot border border-white w-3 h-3 rounded-full bg-white"></button>
                    <button onclick="goToSlide(1)" class="dot border border-white w-3 h-3 rounded-full bg-white bg-opacity-50"></button>
                    <button onclick="goToSlide(2)" class="dot border border-white w-3 h-3 rounded-full bg-white bg-opacity-50"></button>
                </div>

                <!-- Next Button -->
                <button onclick="nextSlide()"
                    class="bg-white bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 transition">
                    <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <!-- Banner Section -->
    <section class="py-16 bg-gradient-to-br from-blue-50 to-purple-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <!-- Left Side - Image -->
                    <div class="relative">
                        <img src="{{ $page ? $page->getContent('banner_image', 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800') : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800' }}" alt="Learning Banner"
                            class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/30"></div>
                    </div>

                    <!-- Right Side - Content -->
                    <div class="p-8 md:p-12">
                        <div class="mb-6">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                                {{ $page ? $page->getContent('banner_title', 'Alphainno') : 'Alphainno' }}<span class="text-primary">{{ $page ? $page->getContent('banner_title_highlight', 'এর সাথেই শিখাছে') : 'এর সাথেই শিখাছে' }}</span>
                            </h2>
                            <h3 class="text-2xl md:text-3xl font-bold text-primary mb-4">
                                {{ $page ? $page->getContent('banner_subtitle', 'সবাই, জিতছে সবাই') : 'সবাই, জিতছে সবাই' }}
                            </h3>
                        </div>

                        <p class="text-gray-700 leading-relaxed mb-6">
                            {{ $page ? $page->getContent('banner_description', 'এখন তুমি যেখানেই থাকো, ম্যাচার কথা না কিছু! কারণ সবাই শিখাছে আর জিতছে দেশের সবচেয়ে বিশ্বস্ত ডিজিটাল লার্নিং প্ল্যাটফর্ম শিখোতে।') : 'এখন তুমি যেখানেই থাকো, ম্যাচার কথা না কিছু! কারণ সবাই শিখাছে আর জিতছে দেশের সবচেয়ে বিশ্বস্ত ডিজিটাল লার্নিং প্ল্যাটফর্ম শিখোতে।' }}
                        </p>

                        <a href="{{ route('courses') }}"
                            class="inline-block bg-primary hover:opacity-90 text-white font-semibold px-8 py-3 rounded-lg transition-all shadow-lg hover:shadow-xl">
                            {{ $page ? $page->getContent('banner_button', 'আমাদের সম্পর্কে জেনে নাও') : 'আমাদের সম্পর্কে জেনে নাও' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses Slider Section -->
    <section class="py-16 bg-gradient-to-br from-orange-50 via-pink-50 to-purple-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $page ? $page->getContent('courses_section_title', 'জনপ্রিয় কোর্সসমূহ') : 'জনপ্রিয় কোর্সসমূহ' }}</h2>
                <p class="text-gray-600 text-lg">{{ $page ? $page->getContent('courses_section_subtitle', 'আমাদের সবচেয়ে জনপ্রিয় এবং চাহিদা সম্পন্ন কোর্সগুলি দেখুন') : 'আমাদের সবচেয়ে জনপ্রিয় এবং চাহিদা সম্পন্ন কোর্সগুলি দেখুন' }}</p>
            </div>

            <div class="relative px-8">
                @if($popularCourses->count() > 0)
                <x-ui.carousel class="w-full">
                    @php
                        $gradients = [
                            'from-blue-200 to-blue-300',
                            'from-teal-200 to-teal-300',
                            'from-rose-200 to-rose-300',
                            'from-indigo-200 to-indigo-300',
                            'from-purple-200 to-purple-300',
                            'from-orange-200 to-orange-300',
                            'from-green-200 to-green-300',
                            'from-pink-200 to-pink-300',
                        ];
                        $colors = ['blue', 'purple', 'rose', 'indigo', 'teal', 'orange', 'green', 'pink'];
                    @endphp
                    @foreach($popularCourses as $index => $course)
                        @php
                            $gradient = $gradients[$index % count($gradients)];
                            $color = $colors[$index % count($colors)];
                        @endphp
                        <x-ui.carousel-item class="basis-full md:basis-1/2 lg:basis-1/3 select-none">
                            <div onclick="openCourseModal(this)" data-course='@json($course)' class="cursor-pointer bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-1 mx-2 h-full">
                                <div class="relative h-48 bg-gradient-to-br {{ $gradient }}">
                                    @if($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}"
                                            class="w-full h-full object-cover pointer-events-none">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <div class="text-center">
                                                <svg class="w-16 h-16 mx-auto text-white opacity-75 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                                <p class="text-white text-sm font-semibold">{{ $course->name }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
                                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg">
                                            <svg class="w-8 h-8 text-{{ $color }}-600 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                                            </svg>
                                        </div>
                                    </div>
                                    @if($course->category)
                                        <div class="absolute top-4 right-4 bg-white px-3 py-1 rounded-full text-xs font-semibold">
                                            {{ $course->category }}
                                        </div>
                                    @endif
                                </div>
                                <div class="p-5">
                                    @if($course->code)
                                        <div class="flex items-center mb-2">
                                            <div class="w-2 h-2 bg-{{ $color }}-500 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-600">{{ $course->code }}</span>
                                        </div>
                                    @endif
                                    <h4 class="text-lg font-bold text-gray-900 truncate">{{ $course->name }}</h4>
                                    @if($course->description)
                                        <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ Str::limit($course->description, 80) }}</p>
                                    @endif
                                    @if($course->videos_count > 0)
                                        <div class="flex items-center mt-3 text-xs text-gray-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ $course->videos_count }} টি ভিডিও</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </x-ui.carousel-item>
                    @endforeach
                </x-ui.carousel>
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <p class="text-gray-600">কোন কোর্স পাওয়া যায়নি</p>
                </div>
                @endif
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('courses') }}"
                    class="inline-flex items-center gap-2 bg-primary hover:opacity-90 text-white font-semibold px-8 py-3 rounded-lg transition-all shadow-lg">
                    <span>সকল কোর্স দেখুন</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Students Section -->
    @if($featuredStudents->count() > 0)
    <section class="py-16 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $page ? $page->getContent('students_section_title', 'আমাদের সেরা শিক্ষার্থীরা') : 'আমাদের সেরা শিক্ষার্থীরা' }}</h2>
                <p class="text-gray-600 text-lg">{{ $page ? $page->getContent('students_section_subtitle', 'যারা এক্সেলেন্স এবং ডেডিকেশনের সাথে তাদের শিক্ষাজীবন অতিবাহিত করছেন') : 'যারা এক্সেলেন্স এবং ডেডিকেশনের সাথে তাদের শিক্ষাজীবন অতিবাহিত করছেন' }}</p>
            </div>

            <div class="relative px-8">
                <x-ui.carousel class="w-full">
                    @foreach($featuredStudents as $student)
                        <x-ui.carousel-item class="basis-full md:basis-1/2 lg:basis-1/4 select-none">
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-1 mx-2 h-full">
                                <div class="relative h-48 bg-gradient-to-br from-blue-400 to-purple-500">
                                    @if($student->profile_image)
                                        @if(str_starts_with($student->profile_image, 'http'))
                                            <img src="{{ $student->profile_image }}" alt="{{ $student->user->name ?? 'Student' }}" class="w-full h-full object-cover pointer-events-none">
                                        @else
                                            <img src="{{ asset('storage/' . $student->profile_image) }}" alt="{{ $student->user->name ?? 'Student' }}" class="w-full h-full object-cover pointer-events-none">
                                        @endif
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg">
                                                <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="absolute top-4 right-4 bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                        ⭐ Featured
                                    </div>
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 truncate">{{ $student->user->name ?? 'Student Name' }}</h3>
                                    <p class="text-gray-600 mb-3 truncate">{{ $student->name_bn ?? '' }}</p>
                                    <div class="space-y-2 text-sm text-gray-600">
                                        @if($student->batch)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                                <span class="truncate">{{ $student->batch->name }}</span>
                                            </div>
                                        @endif
                                        @if($student->class)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>Class {{ $student->class }}</span>
                                            </div>
                                        @endif
                                        @if($student->registration_no)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                                <span class="truncate">{{ $student->registration_no }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </x-ui.carousel-item>
                    @endforeach
                </x-ui.carousel>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('students') }}"
                    class="inline-flex items-center gap-2 bg-primary hover:opacity-90 text-white font-semibold px-8 py-3 rounded-lg transition-all shadow-lg">
                    <span>সকল শিক্ষার্থী দেখুন</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Random Students Section -->
    @if($randomStudents->count() > 0)
    <section class="py-16 bg-gradient-to-br from-purple-50 via-pink-50 to-orange-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $page ? $page->getContent('random_students_title', 'আমাদের শিক্ষার্থীরা') : 'আমাদের শিক্ষার্থীরা' }}</h2>
                <p class="text-gray-600 text-lg">{{ $page ? $page->getContent('random_students_subtitle', 'আমাদের প্রতিষ্ঠানের মেধাবী ও পরিশ্রমী শিক্ষার্থীদের সাথে পরিচিত হন') : 'আমাদের প্রতিষ্ঠানের মেধাবী ও পরিশ্রমী শিক্ষার্থীদের সাথে পরিচিত হন' }}</p>
            </div>

            <div class="relative px-8">
                <x-ui.carousel class="w-full">
                    @php
                        $studentGradients = [
                            'from-blue-400 to-indigo-500',
                            'from-green-400 to-teal-500',
                            'from-purple-400 to-pink-500',
                            'from-orange-400 to-red-500',
                            'from-cyan-400 to-blue-500',
                            'from-pink-400 to-rose-500',
                            'from-indigo-400 to-purple-500',
                            'from-teal-400 to-green-500',
                        ];
                    @endphp
                    @foreach($randomStudents as $index => $student)
                        @php
                            $studentGradient = $studentGradients[$index % count($studentGradients)];
                        @endphp
                        <x-ui.carousel-item class="basis-full md:basis-1/2 lg:basis-1/4 select-none">
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-1 mx-2 h-full">
                                <div class="relative h-48 bg-gradient-to-br {{ $studentGradient }}">
                                    @if($student->profile_image)
                                        @if(str_starts_with($student->profile_image, 'http'))
                                            <img src="{{ $student->profile_image }}" alt="{{ $student->user->name ?? 'Student' }}" class="w-full h-full object-cover pointer-events-none">
                                        @else
                                            <img src="{{ asset('storage/' . $student->profile_image) }}" alt="{{ $student->user->name ?? 'Student' }}" class="w-full h-full object-cover pointer-events-none">
                                        @endif
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg">
                                                <svg class="w-10 h-10 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 truncate">{{ $student->user->name ?? 'Student Name' }}</h3>
                                    @if($student->name_bn)
                                        <p class="text-gray-600 mb-3 truncate">{{ $student->name_bn }}</p>
                                    @endif
                                    <div class="space-y-2 text-sm text-gray-600">
                                        @if($student->batch)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                                <span class="truncate">{{ $student->batch->name }}</span>
                                            </div>
                                        @endif
                                        @if($student->class)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>{{ $student->class }}</span>
                                            </div>
                                        @endif
                                        @if($student->registration_no)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                                <span class="truncate">{{ $student->registration_no }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </x-ui.carousel-item>
                    @endforeach
                </x-ui.carousel>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('students') }}"
                    class="inline-flex items-center gap-2 bg-primary hover:opacity-90 text-white font-semibold px-8 py-3 rounded-lg transition-all shadow-lg">
                    <span>সকল শিক্ষার্থী দেখুন</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- About & Notice Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8 items-stretch">
                <div class="md:col-span-2 bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                    <div class="grid md:grid-cols-2 gap-6 p-6 flex-1">
                        <div>
                            <img src="{{ $page ? $page->getContent('about_section_image', 'https://images.unsplash.com/photo-1562774053-701939374585?w=600') : 'https://images.unsplash.com/photo-1562774053-701939374585?w=600' }}" alt="School Building"
                                class="w-full h-full object-cover rounded-lg">
                        </div>
                        <div class="flex flex-col">
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $page ? $page->getContent('about_section_title', 'প্রতিষ্ঠান সম্পর্কে') : 'প্রতিষ্ঠান সম্পর্কে' }}</h2>
                                <p class="text-gray-600 leading-relaxed mb-4 text-sm">{{ $page ? $page->getContent('about_section_text1', 'এক্সওয়াইজেডস স্কুল এন্ড কলেজ বাংলাদেশের একটি অগ্রগামী শিক্ষা প্রতিষ্ঠান...') : 'এক্সওয়াইজেডস স্কুল এন্ড কলেজ বাংলাদেশের একটি অগ্রগামী শিক্ষা প্রতিষ্ঠান...' }}</p>
                                <p class="text-gray-600 leading-relaxed mb-4 text-sm">{{ $page ? $page->getContent('about_section_text2', 'প্রতিষ্ঠানটি ১৯৮৮ সালে প্রতিষ্ঠিত হয়। আমাদের লক্ষ্য মানসম্মত শিক্ষা প্রদান করা...') : 'প্রতিষ্ঠানটি ১৯৮৮ সালে প্রতিষ্ঠিত হয়। আমাদের লক্ষ্য মানসম্মত শিক্ষা প্রদান করা...' }}</p>
                            </div>
                            <a href="{{ route('about') }}"
                                class="block w-full text-center bg-primary text-white px-6 py-2 rounded-md text-sm font-medium hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-[{{ $primaryColor ?? '#3b82f6' }}] transition mt-4">
                                {{ $page ? $page->getContent('about_section_button', 'বিস্তারিত পড়ুন') : 'বিস্তারিত পড়ুন' }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 flex flex-col">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" />
                        </svg>
                        <h3 class="text-xl font-bold text-gray-800">{{ $page ? $page->getContent('notice_title', 'নোটিশ বোর্ড') : 'নোটিশ বোর্ড' }}</h3>
                    </div>
                    <div class="space-y-4 flex-1">
                        <a href="#"
                            class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition group">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-600 font-bold">
                                1</div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-700 group-hover:text-primary">{{ $page ? $page->getContent('notice_1', 'নতুন শিক্ষাবর্ষের ভর্তি কার্যক্রম শুরু...') : 'নতুন শিক্ষাবর্ষের ভর্তি কার্যক্রম শুরু...' }}</p>
                            </div>
                        </a>
                    </div>
                    <div class="mt-4 text-center">
                        <a href="#"
                            class="inline-flex items-center gap-1 text-[{{ $primaryColor ?? '#006A4E' }}] text-sm font-medium hover:underline"><span>{{ $page ? $page->getContent('notice_view_all', 'সকল নোটিশ') : 'সকল নোটিশ' }}</span><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Course Modal -->
    <div id="courseModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeCourseModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="relative bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="absolute top-0 right-0 pt-4 pr-4">
                        <button type="button" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none" onclick="closeCourseModal()">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4" id="modal-title">Course Title</h3>
                            <div class="mt-2">
                                <img id="modal-image" src="" alt="Course Image" class="w-full h-48 object-cover rounded-lg mb-4 shadow-sm">
                                <div class="prose prose-sm text-gray-500">
                                    <p id="modal-description">Course Description</p>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center text-sm text-gray-600">
                                     <span id="modal-videos" class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <span id="modal-videos-text"></span>
                                     </span>
                                     <span id="modal-category" class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-xs font-semibold"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary/90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm" onclick="closeCourseModal()">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove('active');
                dots[i].classList.remove('bg-white');
                dots[i].classList.add('bg-opacity-50');
            });
            slides[index].classList.add('active');
            dots[index].classList.add('bg-white');
            dots[index].classList.remove('bg-opacity-50');
        }

        function nextSlide() { currentSlide = (currentSlide + 1) % slides.length; showSlide(currentSlide); }
        function prevSlide() { currentSlide = (currentSlide - 1 + slides.length) % slides.length; showSlide(currentSlide); }
        function goToSlide(index) { currentSlide = index; showSlide(currentSlide); }
        setInterval(nextSlide, 5000);

        function openCourseModal(element) {
            const course = JSON.parse(element.getAttribute('data-course'));
            document.getElementById('modal-title').innerText = course.name;
            document.getElementById('modal-description').innerText = course.description || 'No description available.';
            
            let imageUrl = '';
            if (course.thumbnail) {
                imageUrl = course.thumbnail.startsWith('http') ? course.thumbnail : `/storage/${course.thumbnail}`;
            } else {
                // Placeholder if no image
                imageUrl = 'https://via.placeholder.com/640x360?text=No+Image';
            }
            document.getElementById('modal-image').src = imageUrl;

            if (course.videos_count) {
                 document.getElementById('modal-videos-text').innerText = `${course.videos_count} টি ভিডিও`;
                 document.getElementById('modal-videos').style.display = 'flex';
            } else {
                 document.getElementById('modal-videos').style.display = 'none';
            }
            
            if (course.category) {
                document.getElementById('modal-category').innerText = course.category;
                document.getElementById('modal-category').style.display = 'inline-block';
            } else {
                document.getElementById('modal-category').style.display = 'none';
            }

            document.getElementById('courseModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeCourseModal() {
            document.getElementById('courseModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endpush