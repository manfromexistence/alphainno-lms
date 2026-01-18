@extends('layouts.frontend')

@section('title', $page ? $page->getContent('page_title', 'প্রতিষ্ঠান পরিচিতি') : 'প্রতিষ্ঠান পরিচিতি' . ' - XYZ School & College')

@section('content')
    <!-- Page Header -->
    <section class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-4xl font-bold text-gray-900 text-center mb-4">{{ $page ? $page->getContent('page_title', 'প্রতিষ্ঠান পরিচিতি') : 'প্রতিষ্ঠান পরিচিতি' }}</h1>
            <div class="flex items-center justify-center gap-2 text-sm">
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-primary flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    মূলপাতা
                </a>
                <span class="text-gray-400">></span>
                <span class="text-primary font-medium">{{ $page ? $page->getContent('page_title', 'প্রতিষ্ঠান পরিচিতি') : 'প্রতিষ্ঠান পরিচিতি' }}</span>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <!-- Image -->
                <div>
                    <img src="{{ $page ? $page->getContent('about_image', 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=800&q=80') : 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=800&q=80' }}" alt="School Building"
                        class="rounded-lg shadow-lg w-full h-auto object-cover">
                </div>

                <!-- Content -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $page ? $page->getContent('about_title', 'প্রতিষ্ঠান সম্পর্কে') : 'প্রতিষ্ঠান সম্পর্কে' }}</h2>
                    <div class="w-16 h-1 bg-primary mb-6"></div>

                    <div class="text-gray-700 leading-relaxed space-y-4">
                        {!! nl2br(e($page ? $page->getContent('about_text', 'এক্সওয়াইজেডস স্কুল এন্ড কলেজ জামালপুর জেলার অন্যতম প্রধান শিক্ষা প্রতিষ্ঠান।') : 'এক্সওয়াইজেডস স্কুল এন্ড কলেজ জামালপুর জেলার অন্যতম প্রধান শিক্ষা প্রতিষ্ঠান।')) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">প্রতিষ্ঠানের পরিসংখ্যান</h2>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-8">
                @php
                    $stats = [
                        ['val' => $page ? $page->getContent('stats_students', '৫২০') : '৫২০', 'label' => 'সর্বমোট শিক্ষার্থী'],
                        ['val' => $page ? $page->getContent('stats_teachers', '২০') : '২০', 'label' => 'শিক্ষক/শিক্ষিকা'],
                        ['val' => $page ? $page->getContent('stats_staff', '৮') : '৮', 'label' => 'অফিস কর্মচারী'],
                        ['val' => $page ? $page->getContent('stats_rooms', '১৫') : '১৫', 'label' => 'সর্বমোট কক্ষ'],
                        ['val' => $page ? $page->getContent('stats_buildings', '৬') : '৬', 'label' => 'বিদ্যালয় ভবন'],
                    ];
                @endphp
                @foreach ($stats as $stat)
                    <div class="flex flex-col items-center">
                        <div
                            class="w-32 h-32 rounded-full border-4 border-gray-200 flex flex-col items-center justify-center mb-4 bg-white">
                            <span class="text-4xl font-bold text-primary">{{ $stat['val'] }}</span>
                        </div>
                        <p class="text-gray-700 font-medium text-center">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-start">
                <!-- Mission -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $page ? $page->getContent('mission_title', 'প্রতিষ্ঠানের মিশন') : 'প্রতিষ্ঠানের মিশন' }}</h2>
                    <div class="w-16 h-1 bg-primary mb-6"></div>
                    <div class="text-gray-700 leading-relaxed">
                        {!! nl2br(e($page ? $page->getContent('mission_text', 'এক্সওয়াইজেডস স্কুল এন্ড কলেজের মিশন হল শিক্ষার্থীদের একটি উচ্চমানের শিক্ষা প্রদান করা...') : 'এক্সওয়াইজেডস স্কুল এন্ড কলেজের মিশন হল শিক্ষার্থীদের একটি উচ্চমানের শিক্ষা প্রদান করা...')) !!}
                    </div>
                </div>

                <!-- Vision -->
                <div>
                    <div class="mb-8">
                        <img src="{{ $page ? $page->getContent('vision_image', 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=500&q=80') : 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=500&q=80' }}" alt="Students"
                            class="rounded-lg shadow-lg w-full h-auto object-cover">
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $page ? $page->getContent('vision_title', 'প্রতিষ্ঠানের ভিশন') : 'প্রতিষ্ঠানের ভিশন' }}</h2>
                    <div class="w-16 h-1 bg-primary mb-6"></div>
                    <div class="text-gray-700 leading-relaxed">
                        {!! nl2br(e($page ? $page->getContent('vision_text', 'এক্সওয়াইজেডস স্কুল এন্ড কলেজের ভিশন হল একটি বিশ্বমানের শিক্ষা প্রতিষ্ঠান হিসাবে গড়ে তুলা...') : 'এক্সওয়াইজেডস স্কুল এন্ড কলেজের ভিশন হল একটি বিশ্বমানের শিক্ষা প্রতিষ্ঠান হিসাবে গড়ে তুলা...')) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
