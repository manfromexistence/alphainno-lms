{{-- Custom Confirmation Dialog Component --}}
<div id="confirm-dialog" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeConfirmDialog()"></div>

    <!-- Dialog panel -->
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <!-- Icon -->
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    
                    <!-- Content -->
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="confirm-dialog-title">
                            Confirm Action
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="confirm-dialog-message">
                                Are you sure you want to proceed with this action?
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button type="button" 
                        id="confirm-dialog-confirm-btn"
                        class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                    Delete
                </button>
                <button type="button" 
                        onclick="closeConfirmDialog()"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
    <script>
        let confirmDialogCallback = null;

        function showConfirmDialog(options = {}) {
            const dialog = document.getElementById('confirm-dialog');
            const title = document.getElementById('confirm-dialog-title');
            const message = document.getElementById('confirm-dialog-message');
            const confirmBtn = document.getElementById('confirm-dialog-confirm-btn');

            // Set content
            title.textContent = options.title || 'Confirm Action';
            message.textContent = options.message || 'Are you sure you want to proceed?';
            confirmBtn.textContent = options.confirmText || 'Delete';
            
            // Set button color based on type
            const btnClass = options.type === 'danger' || !options.type 
                ? 'bg-red-600 hover:bg-red-500' 
                : 'bg-blue-600 hover:bg-blue-500';
            confirmBtn.className = `inline-flex w-full justify-center rounded-md ${btnClass} px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto`;

            // Store callback
            confirmDialogCallback = options.onConfirm;

            // Show dialog
            dialog.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeConfirmDialog() {
            const dialog = document.getElementById('confirm-dialog');
            dialog.classList.add('hidden');
            document.body.style.overflow = '';
            confirmDialogCallback = null;
        }

        function confirmDialogAction() {
            if (confirmDialogCallback) {
                confirmDialogCallback();
            }
            closeConfirmDialog();
        }

        // Attach confirm action to button
        document.addEventListener('DOMContentLoaded', function() {
            const confirmBtn = document.getElementById('confirm-dialog-confirm-btn');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', confirmDialogAction);
            }

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeConfirmDialog();
                }
            });
        });

        // Helper function for delete forms
        function confirmDelete(form, message = 'Are you sure you want to delete this item? This action cannot be undone.') {
            showConfirmDialog({
                title: 'Confirm Deletion',
                message: message,
                confirmText: 'Delete',
                type: 'danger',
                onConfirm: () => form.submit()
            });
            return false; // Prevent default form submission
        }
    </script>
    @endpush
@endonce
