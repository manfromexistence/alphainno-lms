@props(['type' => 'success', 'message'])

@php
    $id = 'toast-' . uniqid();
    
    $colors = [
        'success' => 'border-emerald-500 text-emerald-600',
        'error' => 'border-red-500 text-red-600',
        'warning' => 'border-yellow-500 text-yellow-600',
        'info' => 'border-blue-500 text-blue-600',
    ];
    
    $icons = [
        'success' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        'error' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
        'warning' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
        'info' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
    ];
@endphp

<div id="{{ $id }}" 
     class="fixed bottom-5 right-5 z-50 transform transition-all duration-300 ease-out translate-y-10 opacity-0 bg-white border border-gray-100 border-l-4 rounded shadow-lg p-4 flex items-start gap-3 w-80 {{ $colors[$type] ?? $colors['info'] }}"
     role="alert">
    
    <div class="flex-shrink-0">
        {!! $icons[$type] ?? $icons['info'] !!}
    </div>
    
    <div class="flex-1 text-sm font-medium text-gray-800">
        {{ $message }}
    </div>

    <button onclick="closeToast('{{ $id }}')" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
</div>

<script>
    (function() {
        const id = '{{ $id }}';
        const el = document.getElementById(id);
        
        // Animate In
        setTimeout(() => {
            el.classList.remove('translate-y-10', 'opacity-0');
        }, 100);

        // Auto Close
        const timeout = setTimeout(() => {
            closeToast(id);
        }, 5000);

        // Global Close Function if not exists
        if (!window.closeToast) {
            window.closeToast = function(toastId) {
                const toast = document.getElementById(toastId);
                if (toast) {
                    toast.classList.add('opacity-0', 'translate-y-4'); // Animate out
                    setTimeout(() => {
                        toast.remove();
                    }, 300); // Wait for transition
                }
            }
        }
    })();
</script>
