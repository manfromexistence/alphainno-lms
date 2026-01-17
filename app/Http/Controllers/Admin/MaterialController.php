<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Course $course)
    {
        $materials = $course->materials()->orderBy('order')->get();
        return view('dashboard.materials.index', compact('course', 'materials'));
    }

    public function create(Course $course)
    {
        return view('dashboard.materials.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:pdf,video,document,link,image',
            'file' => 'required_unless:type,link|file|max:51200',
            'file_path' => 'required_if:type,link|nullable|url',
        ]);

        $order = $course->materials()->max('order') + 1;

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('materials/' . $course->id, 'public');
            $validated['file_path'] = $path;
        }

        $course->materials()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'file_path' => $validated['file_path'],
            'order' => $order,
        ]);

        return redirect()->route('dashboard.courses.materials.index', $course)
            ->with('success', 'Material uploaded successfully.');
    }

    public function edit(Course $course, CourseMaterial $material)
    {
        return view('dashboard.materials.edit', compact('course', 'material'));
    }

    public function update(Request $request, Course $course, CourseMaterial $material)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:pdf,video,document,link,image',
            'file' => 'nullable|file|max:51200',
            'file_path' => 'required_if:type,link|nullable|url',
        ]);

        if ($request->hasFile('file')) {
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
            $path = $request->file('file')->store('materials/' . $course->id, 'public');
            $validated['file_path'] = $path;
        } elseif ($validated['type'] === 'link') {
            $material->file_path = $validated['file_path'];
        }

        $material->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'file_path' => $validated['file_path'] ?? $material->file_path,
        ]);

        return redirect()->route('dashboard.courses.materials.index', $course)
            ->with('success', 'Material updated successfully.');
    }

    public function destroy(Course $course, CourseMaterial $material)
    {
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->route('dashboard.courses.materials.index', $course)
            ->with('success', 'Material deleted successfully.');
    }

    public function reorder(Request $request, Course $course)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:course_materials,id',
        ]);

        foreach ($request->order as $index => $materialId) {
            CourseMaterial::where('id', $materialId)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
