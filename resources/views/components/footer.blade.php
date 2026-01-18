<footer class="text-gray-800" style="background-color: #e9e9e9;">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('logo.png') }}" alt="Alpha LMS" class="h-10 w-auto object-contain">
                </div>
                <p class="text-primary text-sm leading-relaxed mb-4">
                    এক্সওয়াইজেডস স্কুল এন্ড কলেজ - মানসম্মত শিক্ষা এবং নৈতিক মূল্যবোধ সৃষ্টিতে আমরা প্রতিশ্রুতিবদ্ধ।
                </p>
                <div class="flex gap-3">
                    <a href="#"
                        class="w-10 h-10 bg-primary hover:opacity-80 rounded-full flex items-center justify-center transition-all text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    <a href="#"
                        class="w-10 h-10 bg-primary hover:opacity-80 rounded-full flex items-center justify-center transition-all text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4 text-primary">দ্রুত লিংক</h3>
                <ul class="space-y-3">
                    <li><a href="{{ url('/') }}"
                            class="text-primary hover:opacity-80 transition-colors">প্রচ্ছদ</a></li>
                    <li><a href="{{ route('about') }}"
                            class="text-primary hover:opacity-80 transition-colors">পরিচিতি</a></li>
                    <li><a href="{{ route('courses') }}"
                            class="text-primary hover:opacity-80 transition-colors">কোর্স</a></li>
                    <li><a href="{{ route('teachers') }}"
                            class="text-primary hover:opacity-80 transition-colors">শিক্ষক</a></li>
                    <li><a href="{{ route('students') }}"
                            class="text-primary hover:opacity-80 transition-colors">শিক্ষার্থী</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4 text-primary">গুরুত্বপূর্ণ লিংক</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('results') }}"
                            class="text-primary hover:opacity-80 transition-colors">পরীক্ষার ফলাফল</a></li>
                    <li><a href="{{ route('contact') }}"
                            class="text-primary hover:opacity-80 transition-colors">যোগাযোগ</a></li>
                    <li><a href="{{ route('login') }}"
                            class="text-primary hover:opacity-80 transition-colors">লগইন</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4 text-primary">যোগাযোগ</h3>
                <ul class="space-y-3 text-sm text-primary">
                    <li>ঠিকানা: জামালপুর সদর, জামালপুর</li>
                    <li>ফোন: +880 1XXX-XXXXXX</li>
                    <li>ইমেইল: info@xyzschool.edu.bd</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="border-t border-primary">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center">
            <p class="text-primary text-sm">© ২০২৬ এক্সওয়াইজেডস স্কুল এন্ড কলেজ। সর্বস্বত্ব সংরক্ষিত।</p>
        </div>
    </div>
</footer>