@php
    $settingsService = app(\App\Services\SettingsService::class);
    $primaryColor = $settingsService->get('theme_primary_color', '#3b82f6');
@endphp

<div class="text-white py-2 px-4 text-xs md:text-sm font-medium bg-primary">
    <div class="max-w-7xl mx-auto px-4 flex justify-between items-center gap-4">
        <div class="max-w-xl overflow-hidden">
            <div class="ticker">
                <span>মাধ্যমিক শিক্ষা বোর্ড জামালপুরের অধীনস্থ প্রতিষ্ঠান এবং একটি বেসরকারি শিক্ষা প্রতিষ্ঠান।</span>
                @if(Request::is('/'))
                    <span class="mx-4">★</span>
                    <span>২০২৬ সালের এসএসসি পরীক্ষার্থীদের জন্য বিশেষ নোটিশ।</span>
                    <span class="mx-4">★</span>
                    <span>নতুন শিক্ষাবর্ষে ভর্তি চলছে।</span>
                    <span class="mx-4">★</span>
                @endif
            </div>
        </div>
        <div class="shrink-0 flex items-center gap-4">
            <span>EIIN No: <strong>123354</strong></span>
            <span>School code: <strong>123456</strong></span>
            <span>Reg. No: <strong>12334455617</strong></span>
        </div>
    </div>
</div>