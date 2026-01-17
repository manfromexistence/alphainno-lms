@extends('layouts.admin')

@section('title', 'Edit Page - ' . $page->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Edit Page: {{ $page->title }}</h2>
            <p class="text-muted-foreground">Update page content and settings</p>
        </div>
        <x-ui.button variant="outline" as="a" href="{{ route('dashboard.cms.index') }}">
            <i class="fas fa-arrow-left mr-2"></i> Back to Pages
        </x-ui.button>
    </div>

    @if(session('success'))
        <x-ui.alert variant="default" class="mb-6 bg-emerald-50 text-emerald-800 border-emerald-200">
            <x-ui.alert-title>Success</x-ui.alert-title>
            <x-ui.alert-description>{{ session('success') }}</x-ui.alert-description>
        </x-ui.alert>
    @endif

    @if($errors->any())
        <x-ui.alert variant="destructive" class="mb-6">
            <x-ui.alert-title>Error</x-ui.alert-title>
            <x-ui.alert-description>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-ui.alert-description>
        </x-ui.alert>
    @endif

    <form action="{{ route('dashboard.cms.update', $page) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Page Details</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-ui.label for="title">Page Title <span class="text-destructive">*</span></x-ui.label>
                        <x-ui.input name="title" id="title" :value="old('title', $page->title)" required />
                    </div>
                    <div class="space-y-2">
                        <x-ui.label for="slug">URL Slug</x-ui.label>
                        <x-ui.input name="slug" id="slug" :value="$page->slug" disabled class="bg-muted text-muted-foreground" />
                        <p class="text-[0.8rem] text-muted-foreground">Slug cannot be changed after creation</p>
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Page Content</x-ui.card-title>
                <x-ui.card-description>Add custom content fields as JSON key-value pairs</x-ui.card-description>
            </x-ui.card-header>
            <x-ui.card-content>
                <div id="content-fields" class="space-y-4">
                    @if($page->content && count($page->content) > 0)
                        @foreach($page->content as $key => $value)
                        <div class="flex gap-4 items-start content-row">
                            <div class="flex-1">
                                <x-ui.input name="content_keys[]" value="{{ $key }}" placeholder="Field name" />
                            </div>
                            <div class="flex-1">
                                <x-ui.textarea name="content_values[]" rows="2" placeholder="Field value">{{ $value }}</x-ui.textarea>
                            </div>
                            <x-ui.button type="button" variant="ghost" size="icon" onclick="this.closest('.content-row').remove()" class="text-destructive hover:bg-destructive/10">
                                <i class="fas fa-trash"></i>
                            </x-ui.button>
                        </div>
                        @endforeach
                    @endif
                </div>
                
                <x-ui.button type="button" variant="secondary" onclick="addContentField()" class="mt-4">
                    <i class="fas fa-plus mr-2"></i> Add Content Field
                </x-ui.button>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>SEO Settings</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <x-ui.label for="meta_title">Meta Title</x-ui.label>
                        <x-ui.input name="meta_title" id="meta_title" :value="old('meta_title', $page->meta_title)" />
                    </div>
                    <div class="space-y-2">
                        <x-ui.label for="meta_description">Meta Description</x-ui.label>
                        <x-ui.textarea name="meta_description" id="meta_description" :value="old('meta_description', $page->meta_description)" rows="3" />
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Status</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="flex items-center space-x-2">
                    <x-ui.checkbox name="is_active" id="is_active" value="1" :checked="$page->is_active" />
                    <x-ui.label for="is_active" class="font-normal">Page is active and visible on the website</x-ui.label>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <div class="flex justify-end gap-4">
            <x-ui.button type="button" variant="outline" as="a" href="{{ route('dashboard.cms.index') }}">Cancel</x-ui.button>
            <x-ui.button type="submit">Save Changes</x-ui.button>
        </div>
    </form>
</div>

<script>
function addContentField() {
    const container = document.getElementById('content-fields');
    const row = document.createElement('div');
    row.className = 'flex gap-4 items-start content-row';
    row.innerHTML = `
        <div class="flex-1">
            <input type="text" name="content_keys[]" placeholder="Field name"
                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
        </div>
        <div class="flex-1">
            <textarea name="content_values[]" rows="2" placeholder="Field value"
                class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"></textarea>
        </div>
        <button type="button" onclick="this.closest('.content-row').remove()" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-destructive/10 hover:text-destructive h-10 w-10 text-destructive">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(row);
}
</script>
@endsection
