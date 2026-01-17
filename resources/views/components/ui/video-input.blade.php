@props([
    'label' => '',
    'name',
    'value' => '',
    'required' => false,
    'helperText' => 'Direct link, YouTube or Vimeo',
])

<div class="space-y-2" x-data="{ 
    videoUrl: '{{ $value }}',
    previewUrl: '{{ $value }}',
    handleFileChange(event) {
        const file = event.target.files[0];
        if (file) {
            this.previewUrl = URL.createObjectURL(file);
            this.videoUrl = file.name;
        }
    },
    handleUrlChange(val) {
        this.previewUrl = val;
        this.videoUrl = val;
    }
}">
    @if($label)
        <label class="block text-sm font-semibold text-gray-700">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    <div class="relative group">
        <div class="w-full aspect-video rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex flex-col items-center justify-center overflow-hidden relative transition-all group-hover:border-[#006A4E]">
            <template x-if="previewUrl">
                <div class="w-full h-full">
                    <!-- Basic Video Preview for direct links -->
                    <video :src="previewUrl" class="w-full h-full object-cover" muted></video>
                    <!-- Icon overlay to indicate it's a video -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-16 h-16 bg-white bg-opacity-50 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </template>
            
            <template x-if="!previewUrl">
                <div class="flex flex-col items-center text-gray-400">
                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm">Click or drag a video</span>
                </div>
            </template>

            <!-- Overlay on hover -->
            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                <label class="cursor-pointer bg-white text-gray-900 px-4 py-2 rounded-lg font-medium text-sm hover:bg-gray-100">
                    Change Video
                    <input type="file" name="{{ $name }}_file" class="hidden" @change="handleFileChange">
                </label>
            </div>
        </div>
    </div>

    <div class="space-y-1 mt-3">
        <label class="block text-xs font-medium text-gray-500">Video URL</label>
        <input 
            type="text" 
            name="{{ $name }}" 
            x-model="videoUrl"
            @input="handleUrlChange($event.target.value)"
            placeholder="Paste video URL here..."
            class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#006A4E] focus:border-transparent outline-none transition-all"
        >
        @if($helperText)
            <p class="text-[10px] text-gray-400">{{ $helperText }}</p>
        @endif
    </div>

    @error($name)
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
