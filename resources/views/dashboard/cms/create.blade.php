@extends('layouts.admin')

@section('title', 'Create New Page')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Create New Page</h2>
            <p class="text-muted-foreground">Add a new page to your website</p>
        </div>
        <x-ui.button variant="outline" as="a" href="{{ route('dashboard.cms.index') }}">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </x-ui.button>
    </div>

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

    <form action="{{ route('dashboard.cms.store') }}" method="POST" class="space-y-6">
        @csrf

        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Page Details</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-ui.label for="title">Page Title <span class="text-destructive">*</span></x-ui.label>
                        <x-ui.input name="title" id="title" :value="old('title')" required placeholder="e.g., Privacy Policy" />
                    </div>
                    <div class="space-y-2">
                        <x-ui.label for="slug">URL Slug <span class="text-destructive">*</span></x-ui.label>
                        <x-ui.input name="slug" id="slug" :value="old('slug')" required placeholder="e.g., privacy-policy" />
                        <p class="text-[0.8rem] text-muted-foreground">This will be the URL: /page/your-slug</p>
                    </div>
                </div>
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
                        <x-ui.input name="meta_title" id="meta_title" :value="old('meta_title')" placeholder="SEO title for search engines" />
                    </div>
                    <div class="space-y-2">
                        <x-ui.label for="meta_description">Meta Description</x-ui.label>
                        <x-ui.textarea name="meta_description" id="meta_description" :value="old('meta_description')" rows="3" placeholder="Brief description for search engines" />
                    </div>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <div class="flex justify-end gap-4">
            <x-ui.button type="button" variant="outline" as="a" href="{{ route('dashboard.cms.index') }}">Cancel</x-ui.button>
            <x-ui.button type="submit">Create Page</x-ui.button>
        </div>
    </form>
</div>
@endsection
