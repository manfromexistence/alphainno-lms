@extends('layouts.admin')

@section('title', 'SMS Templates')
@section('page-title', 'SMS Templates')
@section('page-description', 'Create and manage SMS templates with placeholders')

@section('content')
<div class="space-y-6">
    <!-- Create Template Card - Requirement 12.1: Store template name and message content -->
    <x-ui.card>
        <x-ui.card-header>
            <x-ui.card-title>Create New Template</x-ui.card-title>
            <x-ui.card-description>Create reusable SMS templates with dynamic placeholders</x-ui.card-description>
        </x-ui.card-header>
        <x-ui.card-content>
            <form action="{{ route('dashboard.communication.templates.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-ui.label for="name">Template Name <span class="text-red-500">*</span></x-ui.label>
                        <x-ui.input type="text" name="name" id="name" required placeholder="e.g., Payment Reminder" value="{{ old('name') }}" />
                        @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <x-ui.label for="slug">Slug (Optional)</x-ui.label>
                        <x-ui.input type="text" name="slug" id="slug" placeholder="e.g., payment-reminder" value="{{ old('slug') }}" />
                        <p class="text-xs text-gray-500 mt-1">Auto-generated from name if left empty</p>
                        @error('slug')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <x-ui.label for="content">Template Content <span class="text-red-500">*</span></x-ui.label>
                    <x-ui.textarea name="content" id="content" rows="4" required maxlength="500" placeholder="Enter your template message here...">{{ old('content') }}</x-ui.textarea>
                    <div class="flex justify-between mt-1">
                        <p class="text-xs text-gray-500"><span id="createCharCount">0</span>/500 characters</p>
                    </div>
                    @error('content')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Available Placeholders - Requirement 12.2: Support placeholders for dynamic values -->
                @if(!empty($placeholders))
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-blue-800 mb-2">Available Placeholders (click to insert):</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($placeholders as $placeholder)
                            <button type="button" 
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors"
                                onclick="insertPlaceholderCreate('{{ $placeholder }}')">
                                {{ $placeholder }}
                            </button>
                        @endforeach
                    </div>
                    <p class="text-xs text-blue-600 mt-2">These placeholders will be replaced with actual values when sending SMS.</p>
                </div>
                @endif
                
                <x-ui.button type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Template
                </x-ui.button>
            </form>
        </x-ui.card-content>
    </x-ui.card>

    <!-- Predefined Templates -->
    @if(!empty($predefinedTemplates))
    <x-ui.card>
        <x-ui.card-header>
            <x-ui.card-title>Quick Start Templates</x-ui.card-title>
            <x-ui.card-description>Click to use these predefined templates as a starting point</x-ui.card-description>
        </x-ui.card-header>
        <x-ui.card-content>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($predefinedTemplates as $key => $template)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:bg-blue-50 transition-colors cursor-pointer" onclick="usePredefinedTemplate('{{ $template['name'] }}', '{{ addslashes($template['content']) }}')">
                        <h4 class="font-medium text-gray-900 mb-2">{{ $template['name'] }}</h4>
                        <p class="text-sm text-gray-600 line-clamp-3">{{ $template['content'] }}</p>
                    </div>
                @endforeach
            </div>
        </x-ui.card-content>
    </x-ui.card>
    @endif

    <!-- Templates List - Requirement 12.1, 12.3, 12.4: CRUD operations -->
    <x-ui.card>
        <x-ui.card-header>
            <x-ui.card-title>All Templates</x-ui.card-title>
            <x-ui.card-description>{{ $templates->count() }} templates available</x-ui.card-description>
        </x-ui.card-header>
        <x-ui.card-content class="p-0">
            <x-ui.table>
                <x-ui.table-header>
                    <x-ui.table-row>
                        <x-ui.table-head>Name</x-ui.table-head>
                        <x-ui.table-head>Slug</x-ui.table-head>
                        <x-ui.table-head>Content Preview</x-ui.table-head>
                        <x-ui.table-head>Placeholders</x-ui.table-head>
                        <x-ui.table-head>Status</x-ui.table-head>
                        <x-ui.table-head>Actions</x-ui.table-head>
                    </x-ui.table-row>
                </x-ui.table-header>
                <x-ui.table-body>
                    @forelse($templates as $template)
                        <x-ui.table-row>
                            <x-ui.table-cell class="font-medium">{{ $template->name }}</x-ui.table-cell>
                            <x-ui.table-cell>
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $template->slug }}</code>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="max-w-xs">
                                <span class="truncate block text-sm text-gray-600" title="{{ $template->content }}">
                                    {{ Str::limit($template->content, 50) }}
                                </span>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="flex flex-wrap gap-1">
                                    @forelse($template->placeholders ?? [] as $placeholder)
                                        <x-ui.badge variant="secondary" class="text-xs">{{ $placeholder }}</x-ui.badge>
                                    @empty
                                        <span class="text-gray-400 text-xs">None</span>
                                    @endforelse
                                </div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <x-ui.badge variant="{{ $template->is_active ? 'success' : 'secondary' }}">
                                    {{ $template->is_active ? 'Active' : 'Inactive' }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="flex items-center space-x-2">
                                    <!-- Edit Button -->
                                    <button type="button" 
                                        class="text-gray-500 hover:text-blue-600 transition-colors" 
                                        title="Edit"
                                        onclick="openEditModal({{ json_encode($template) }})">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    
                                    <!-- Toggle Status -->
                                    <form action="{{ route('dashboard.communication.templates.update', $template) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="name" value="{{ $template->name }}">
                                        <input type="hidden" name="content" value="{{ $template->content }}">
                                        <input type="hidden" name="is_active" value="{{ $template->is_active ? '0' : '1' }}">
                                        <button type="submit" class="text-gray-500 hover:text-amber-600 transition-colors" title="{{ $template->is_active ? 'Deactivate' : 'Activate' }}">
                                            @if($template->is_active)
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                    
                                    <!-- Delete Button - Requirement 12.4 -->
                                    <form action="{{ route('dashboard.communication.templates.delete', $template) }}" method="POST" class="inline" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this template?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-600 transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="6" class="text-center py-12">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-purple-50 text-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900">No templates found</h3>
                                    <p class="mt-1 text-sm text-gray-500 max-w-sm">
                                        Create your first SMS template using the form above. Templates help you send consistent, personalized messages quickly.
                                    </p>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @endforelse
                </x-ui.table-body>
            </x-ui.table>
        </x-ui.card-content>
    </x-ui.card>
</div>

<!-- Edit Template Modal - Requirement 12.3: Update stored template content -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Edit Template</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <x-ui.label for="edit_name">Template Name <span class="text-red-500">*</span></x-ui.label>
                <x-ui.input type="text" name="name" id="edit_name" required />
            </div>
            
            <div>
                <x-ui.label for="edit_content">Template Content <span class="text-red-500">*</span></x-ui.label>
                <x-ui.textarea name="content" id="edit_content" rows="4" required maxlength="500"></x-ui.textarea>
                <p class="text-xs text-gray-500 mt-1"><span id="editCharCount">0</span>/500 characters</p>
            </div>

            @if(!empty($placeholders))
            <div class="bg-blue-50 rounded-lg p-3">
                <p class="text-xs font-medium text-blue-800 mb-2">Available Placeholders:</p>
                <div class="flex flex-wrap gap-1">
                    @foreach($placeholders as $placeholder)
                        <button type="button" 
                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200"
                            onclick="insertPlaceholderEdit('{{ $placeholder }}')">
                            {{ $placeholder }}
                        </button>
                    @endforeach
                </div>
            </div>
            @endif
            
            <div class="flex items-center space-x-2">
                <x-ui.checkbox name="is_active" id="edit_is_active" value="1" />
                <x-ui.label for="edit_is_active" class="cursor-pointer">Active</x-ui.label>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <x-ui.button type="button" variant="outline" onclick="closeEditModal()">Cancel</x-ui.button>
                <x-ui.button type="submit">Save Changes</x-ui.button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Character count for create form
document.getElementById('content').addEventListener('input', function() {
    document.getElementById('createCharCount').textContent = this.value.length;
});

// Character count for edit form
document.getElementById('edit_content').addEventListener('input', function() {
    document.getElementById('editCharCount').textContent = this.value.length;
});

// Insert placeholder into create form
function insertPlaceholderCreate(placeholder) {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    textarea.value = text.substring(0, start) + placeholder + text.substring(end);
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + placeholder.length;
    document.getElementById('createCharCount').textContent = textarea.value.length;
}

// Insert placeholder into edit form
function insertPlaceholderEdit(placeholder) {
    const textarea = document.getElementById('edit_content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    textarea.value = text.substring(0, start) + placeholder + text.substring(end);
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + placeholder.length;
    document.getElementById('editCharCount').textContent = textarea.value.length;
}

// Use predefined template
function usePredefinedTemplate(name, content) {
    document.getElementById('name').value = name;
    document.getElementById('content').value = content;
    document.getElementById('createCharCount').textContent = content.length;
    document.getElementById('name').scrollIntoView({ behavior: 'smooth' });
}

// Open edit modal
function openEditModal(template) {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editForm');
    
    form.action = `/admin/communication/templates/${template.id}`;
    document.getElementById('edit_name').value = template.name;
    document.getElementById('edit_content').value = template.content;
    document.getElementById('edit_is_active').checked = template.is_active;
    document.getElementById('editCharCount').textContent = template.content.length;
    
    modal.classList.remove('hidden');
}

// Close edit modal
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeEditModal();
});

// Close modal on backdrop click
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>
@endpush
@endsection
