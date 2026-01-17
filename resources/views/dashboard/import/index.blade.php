@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold tracking-tight">Bulk Data Import</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Upload Card -->
        <x-ui.card class="h-full">
            <x-ui.card-header class="bg-primary/5 pb-2">
                <x-ui.card-title class="text-lg text-primary">Step 1: Upload File</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="pt-6">
                <form action="{{ route('dashboard.import.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div class="space-y-2">
                        <x-ui.select name="type" label="Select Import Type" required>
                            @foreach($importTypes as $key => $config)
                                <option value="{{ $key }}">{{ $config['label'] }}</option>
                            @endforeach
                        </x-ui.select>
                    </div>

                    <div class="space-y-2">
                        <x-ui.label for="file">Upload File (CSV, Excel)</x-ui.label>
                        <input type="file" name="file" id="file" accept=".csv, .xlsx, .xls" required class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" />
                        <p class="text-[0.8rem] text-muted-foreground">Supported formats: .csv, .xlsx, .xls (Max 10MB)</p>
                    </div>

                    <x-ui.button type="submit" class="w-full">
                        Proceed to Preview <i class="fas fa-arrow-right ml-2"></i>
                    </x-ui.button>
                </form>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Instructions Card -->
        <x-ui.card class="h-full">
            <x-ui.card-header class="bg-emerald-500/5 pb-2">
                <x-ui.card-title class="text-lg text-emerald-700">Instructions</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="pt-6">
                <h6 class="font-medium mb-2">Before you start:</h6>
                <ul class="list-disc list-inside space-y-1 text-sm text-muted-foreground mb-4">
                    <li>Prepare your data in CSV or Excel format.</li>
                    <li>Ensure the file has a header row.</li>
                    <li>Required fields for Students: <strong>Name</strong></li>
                    <li>Optional fields: Email, Phone, Guardian Name, etc.</li>
                </ul>
                <x-ui.alert>
                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                    <x-ui.alert-description>
                        You can map columns manually in the next step if your headers don't match exactly.
                    </x-ui.alert-description>
                </x-ui.alert>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>
@endsection
