<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImportService;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function __construct(protected ImportService $importService)
    {}

    public function index()
    {
        $importTypes = $this->importService->getImportTypes();
        return view('dashboard.import.index', compact('importTypes'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
            'type' => 'required|string|in:students',
        ]);

        try {
            $result = $this->importService->parseFile($request->file('file'));
            $preview = $this->importService->previewImport($result['data'], $request->type);

            session([
                'import_data' => $result['data'],
                'import_headers' => $result['headers'],
                'import_type' => $request->type,
            ]);

            return redirect()->route('dashboard.import.preview')
                ->with('preview', $preview);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to parse file: ' . $e->getMessage());
        }
    }

    public function preview()
    {
        $data = session('import_data', []);
        $headers = session('import_headers', []);
        $type = session('import_type', 'students');

        if (empty($data)) {
            return redirect()->route('dashboard.import.index')
                ->with('error', 'No data to preview. Please upload a file first.');
        }

        $importTypes = $this->importService->getImportTypes();
        $typeConfig = $importTypes[$type] ?? [];

        return view('dashboard.import.preview', [
            'data' => array_slice($data, 0, 20),
            'headers' => $headers,
            'type' => $type,
            'typeConfig' => $typeConfig,
            'totalRows' => count($data),
        ]);
    }

    public function execute(Request $request)
    {
        $data = session('import_data', []);
        $type = session('import_type', 'students');
        $mapping = $request->get('mapping', []);

        if (empty($data)) {
            return redirect()->route('dashboard.import.index')
                ->with('error', 'No data to import. Please upload a file first.');
        }

        try {
            $result = $this->importService->executeImport($data, $type, $mapping);

            session()->forget(['import_data', 'import_headers', 'import_type']);

            return redirect()->route('dashboard.import.index')
                ->with('success', "Import completed. {$result['imported']} records imported, {$result['failed']} failed.");
        } catch (\Exception $e) {
            return redirect()->route('dashboard.import.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
