<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Batch;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $batches = Batch::with('course')->get();
        $courses = Course::all();

        return view('dashboard.announcements.index', compact('announcements', 'batches', 'courses'));
    }

    public function create()
    {
        $batches = Batch::all();
        $courses = Course::all();
        return view('dashboard.announcements.create', compact('batches', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_type' => 'required|in:all,batch,course',
            'target_id' => 'nullable|integer',
            'priority' => 'required|in:normal,high,urgent',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');

        Announcement::create($validated);

        return redirect()->route('dashboard.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function show(Announcement $announcement)
    {
        return view('dashboard.announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        $batches = Batch::all();
        $courses = Course::all();
        return view('dashboard.announcements.edit', compact('announcement', 'batches', 'courses'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_type' => 'required|in:all,batch,course',
            'target_id' => 'nullable|integer',
            'priority' => 'required|in:normal,high,urgent',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $announcement->update($validated);

        return redirect()->route('dashboard.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('dashboard.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}
