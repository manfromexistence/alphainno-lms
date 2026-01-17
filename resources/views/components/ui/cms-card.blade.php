@props(['title', 'description', 'route', 'color' => '#006A4E'])

<a href="{{ $route }}"
   class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border-l-4 group block"
   style="border-left-color: {{ $color }}">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg flex items-center justify-center transition-colors"
             style="background-color: {{ $color }}1A; color: {{ $color }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {{ $slot }}
            </svg>
        </div>
        <div>
            <h3 class="font-semibold text-gray-900 group-hover:text-opacity-80 transition-colors">{{ $title }}</h3>
            <p class="text-sm text-gray-500">{{ $description }}</p>
        </div>
    </div>
</a>
