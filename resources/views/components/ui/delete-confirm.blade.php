@props([
    'action',
    'title' => 'Confirm Delete',
    'message' => 'Are you sure you want to delete this item? This action cannot be undone.',
    'confirmText' => 'Delete',
    'cancelText' => 'Cancel',
    'triggerClass' => '',
])

@php
    $modalId = 'delete-modal-' . uniqid();
@endphp

<div class="inline-block">
    <!-- Trigger Button -->
    <button type="button" onclick="openDeleteModal('{{ $modalId }}')" {{ $attributes->merge(['class' => $triggerClass]) }}>
        {{ $trigger ?? '' }}
    </button>

    <!-- Modal Overlay -->
    <div id="{{ $modalId }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center p-4" onclick="closeDeleteModal('{{ $modalId }}')">
        
        <!-- Modal Content -->
        <div onclick="event.stopPropagation()" class="bg-white rounded-lg shadow-xl max-w-md w-full animate-scale-in">
            
            <!-- Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                    </div>
                    <button onclick="closeDeleteModal('{{ $modalId }}')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6">
                <p class="text-gray-600">{{ $message }}</p>
            </div>

            <!-- Footer -->
            <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                <button onclick="closeDeleteModal('{{ $modalId }}')" 
                        type="button"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    {{ $cancelText }}
                </button>
                <form action="{{ $action }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        {{ $confirmText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@once
    @push('styles')
    <style>
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-scale-in {
            animation: scaleIn 0.2s ease-out;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function openDeleteModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeDeleteModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('[id^="delete-modal-"]');
                modals.forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                });
            }
        });
    </script>
    @endpush
@endonce
