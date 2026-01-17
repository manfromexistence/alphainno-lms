<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BackupService;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function __construct(protected BackupService $backupService)
    {}

    public function index()
    {
        $backups = $this->backupService->getBackupList();
        return view('dashboard.backups.index', compact('backups'));
    }

    public function create()
    {
        try {
            $filename = $this->backupService->createBackup();
            return redirect()->route('dashboard.backups.index')
                ->with('success', "Backup created successfully: {$filename}");
        } catch (\Exception $e) {
            return redirect()->route('dashboard.backups.index')
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function restore(string $filename)
    {
        try {
            $this->backupService->restoreBackup($filename);
            return redirect()->route('dashboard.backups.index')
                ->with('success', 'Backup restored successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.backups.index')
                ->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    public function download(string $filename)
    {
        try {
            return $this->backupService->downloadBackup($filename);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.backups.index')
                ->with('error', 'Download failed: ' . $e->getMessage());
        }
    }

    public function destroy(string $filename)
    {
        try {
            $this->backupService->deleteBackup($filename);
            return redirect()->route('dashboard.backups.index')
                ->with('success', 'Backup deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.backups.index')
                ->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }
}
