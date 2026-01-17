@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold tracking-tight">Import Preview: {{ $typeConfig['label'] }}</h2>

    @if(session('error'))
        <x-ui.alert variant="destructive">
            <x-ui.alert-title>Error</x-ui.alert-title>
            <x-ui.alert-description>{{ session('error') }}</x-ui.alert-description>
        </x-ui.alert>
    @endif

    <form action="{{ route('dashboard.import.execute') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Field Mapping -->
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Step 2: Map Columns</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach(array_merge($typeConfig['required_fields'], $typeConfig['optional_fields']) as $field)
                    <div class="space-y-2">
                        <x-ui.label>
                            {{ ucfirst(str_replace('_', ' ', $field)) }} 
                            @if(in_array($field, $typeConfig['required_fields'])) <span class="text-destructive">*</span> @endif
                        </x-ui.label>
                        <x-ui.select name="mapping[{{ $field }}]" label="-- Select Column --">
                            <option value="">-- Select Column --</option>
                            @foreach($headers as $header)
                                <option value="{{ $header }}" {{ strtolower($header) == strtolower($field) ? 'selected' : '' }}>
                                    {{ $header }}
                                </option>
                            @endforeach
                        </x-ui.select>
                    </div>
                    @endforeach
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <!-- Data Preview -->
        <x-ui.card>
            <x-ui.card-header class="flex flex-row items-center justify-between space-y-0 pb-2">
                <x-ui.card-title>Data Preview (First 20 rows)</x-ui.card-title>
                <x-ui.badge variant="secondary">{{ $totalRows }} rows found</x-ui.badge>
            </x-ui.card-header>
            <x-ui.card-content>
                <div class="overflow-x-auto">
                    <x-ui.table>
                        <x-ui.table-header>
                            <x-ui.table-row>
                                @foreach($headers as $header)
                                    <x-ui.table-head class="whitespace-nowrap">{{ $header }}</x-ui.table-head>
                                @endforeach
                            </x-ui.table-row>
                        </x-ui.table-header>
                        <x-ui.table-body>
                            @foreach($data as $row)
                            <x-ui.table-row>
                                @foreach($headers as $header)
                                    <x-ui.table-cell class="whitespace-nowrap">{{ $row[$header] ?? '' }}</x-ui.table-cell>
                                @endforeach
                            </x-ui.table-row>
                            @endforeach
                        </x-ui.table-body>
                    </x-ui.table>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <div class="flex justify-end gap-4">
            <x-ui.button variant="outline" as="a" href="{{ route('dashboard.import.index') }}">Cancel</x-ui.button>
            <x-ui.button type="submit" class="bg-emerald-600 hover:bg-emerald-700">
                <i class="fas fa-check mr-2"></i> Import {{ $totalRows }} Records
            </x-ui.button>
        </div>
    </form>
</div>
@endsection
