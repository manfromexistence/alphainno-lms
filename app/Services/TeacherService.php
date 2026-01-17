<?php

namespace App\Services;

use App\Models\Teacher;
use App\Models\TeacherCategory;
use App\Models\TeacherSalary;
use App\Models\Batch;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class TeacherService
{
    /**
     * Create a new teacher.
     */
    public function create(array $data): Teacher
    {
        return DB::transaction(function () use ($data) {
            $teacher = Teacher::create($data);

            // Assign to batches if provided
            if (!empty($data['batch_ids'])) {
                $teacher->batches()->sync($data['batch_ids']);
            }

            return $teacher;
        });
    }

    /**
     * Update an existing teacher.
     */
    public function update(Teacher $teacher, array $data): Teacher
    {
        return DB::transaction(function () use ($teacher, $data) {
            $teacher->update($data);

            // Update batch assignments if provided
            if (isset($data['batch_ids'])) {
                $teacher->batches()->sync($data['batch_ids']);
            }

            return $teacher->fresh();
        });
    }

    /**
     * Delete a teacher.
     */
    public function delete(Teacher $teacher): bool
    {
        return DB::transaction(function () use ($teacher) {
            // Detach from batches
            $teacher->batches()->detach();

            // Delete salary records
            $teacher->salaries()->delete();

            return $teacher->delete();
        });
    }

    /**
     * Assign teacher to a category.
     */
    public function assignToCategory(Teacher $teacher, int $categoryId): Teacher
    {
        $category = TeacherCategory::findOrFail($categoryId);
        $teacher->update(['category_id' => $categoryId]);

        return $teacher->fresh();
    }

    /**
     * Record a salary payment.
     */
    public function recordSalaryPayment(Teacher $teacher, array $paymentData): TeacherSalary
    {
        return TeacherSalary::create([
            'teacher_id' => $teacher->id,
            'amount' => $paymentData['amount'],
            'month' => $paymentData['month'],
            'payment_date' => $paymentData['payment_date'] ?? now(),
            'status' => $paymentData['status'] ?? 'paid',
            'notes' => $paymentData['notes'] ?? null,
        ]);
    }

    /**
     * Get salary history for a teacher.
     */
    public function getSalaryHistory(Teacher $teacher): Collection
    {
        return $teacher->salaries()
            ->orderBy('payment_date', 'desc')
            ->get();
    }

    /**
     * Get teacher with all relations.
     */
    public function getWithRelations(int $id): Teacher
    {
        return Teacher::with([
            'user',
            'category',
            'batches',
            'batches.course',
            'salaries' => function ($query) {
                $query->orderBy('payment_date', 'desc');
            },
        ])->findOrFail($id);
    }

    /**
     * Get paginated teachers with optional filters.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Teacher::with(['user', 'category', 'batches']);

        // Filter by category
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by search term
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('department', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($perPage);
    }

    /**
     * Assign teacher to batches.
     */
    public function assignToBatches(Teacher $teacher, array $batchIds): Teacher
    {
        $teacher->batches()->sync($batchIds);

        return $teacher->fresh();
    }

    /**
     * Get teachers by category.
     */
    public function getByCategory(int $categoryId): Collection
    {
        return Teacher::with(['user'])
            ->where('category_id', $categoryId)
            ->get();
    }

    /**
     * Get all categories.
     */
    public function getCategories(): Collection
    {
        return TeacherCategory::withCount('teachers')->get();
    }

    /**
     * Create a new category.
     */
    public function createCategory(array $data): TeacherCategory
    {
        return TeacherCategory::create($data);
    }

    /**
     * Update a category.
     */
    public function updateCategory(TeacherCategory $category, array $data): TeacherCategory
    {
        $category->update($data);
        return $category->fresh();
    }

    /**
     * Delete a category.
     */
    public function deleteCategory(TeacherCategory $category): bool
    {
        // Unassign teachers from this category
        Teacher::where('category_id', $category->id)->update(['category_id' => null]);

        return $category->delete();
    }

    /**
     * Get salary summary for a teacher.
     */
    public function getSalarySummary(Teacher $teacher): array
    {
        $salaries = $teacher->salaries;

        return [
            'total_paid' => $salaries->where('status', 'paid')->sum('amount'),
            'total_pending' => $salaries->where('status', 'pending')->sum('amount'),
            'payments_count' => $salaries->count(),
            'last_payment' => $salaries->where('status', 'paid')->sortByDesc('payment_date')->first(),
        ];
    }

    /**
     * Get teachers with pending salary.
     */
    public function getWithPendingSalary(): Collection
    {
        return Teacher::with(['user', 'salaries'])
            ->whereHas('salaries', function ($query) {
                $query->where('status', 'pending');
            })
            ->get();
    }

    /**
     * Get teacher statistics.
     */
    public function getStatistics(): array
    {
        return [
            'total' => Teacher::count(),
            'active' => Teacher::where('status', 'active')->count(),
            'inactive' => Teacher::where('status', 'inactive')->count(),
            'by_category' => Teacher::select('category_id', DB::raw('count(*) as count'))
                ->groupBy('category_id')
                ->with('category')
                ->get(),
        ];
    }

    /**
     * Search teachers.
     */
    public function search(string $term, int $limit = 10): Collection
    {
        return Teacher::with(['user'])
            ->whereHas('user', function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                      ->orWhere('email', 'like', "%{$term}%");
            })
            ->orWhere('phone', 'like', "%{$term}%")
            ->limit($limit)
            ->get();
    }
}
