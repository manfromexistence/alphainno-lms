<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class BatchService
{
    /**
     * Create a new batch.
     */
    public function create(array $data): Batch
    {
        return DB::transaction(function () use ($data) {
            $batch = Batch::create($data);

            // Assign teachers if provided
            if (!empty($data['teacher_ids'])) {
                $batch->teachers()->sync($data['teacher_ids']);
            }

            return $batch;
        });
    }

    /**
     * Update an existing batch.
     */
    public function update(Batch $batch, array $data): Batch
    {
        return DB::transaction(function () use ($batch, $data) {
            $batch->update($data);

            // Update teacher assignments if provided
            if (isset($data['teacher_ids'])) {
                $batch->teachers()->sync($data['teacher_ids']);
            }

            return $batch->fresh();
        });
    }

    /**
     * Delete a batch.
     */
    public function delete(Batch $batch): bool
    {
        return DB::transaction(function () use ($batch) {
            // Check if batch has students
            if ($batch->students()->count() > 0) {
                throw new \RuntimeException('Cannot delete batch with enrolled students');
            }

            // Detach teachers
            $batch->teachers()->detach();

            // Delete schedules
            $batch->schedules()->delete();

            // Delete attendances
            $batch->attendances()->delete();

            return $batch->delete();
        });
    }

    /**
     * Assign students to a batch.
     */
    public function assignStudents(Batch $batch, array $studentIds): Batch
    {
        // Check capacity
        $currentCount = $batch->students()->count();
        $newCount = count($studentIds);

        if ($batch->max_students && ($currentCount + $newCount) > $batch->max_students) {
            throw new \RuntimeException('Adding these students would exceed batch capacity');
        }

        Student::whereIn('id', $studentIds)->update(['batch_id' => $batch->id]);

        return $batch->fresh();
    }

    /**
     * Remove students from a batch.
     */
    public function removeStudents(Batch $batch, array $studentIds): Batch
    {
        Student::whereIn('id', $studentIds)
            ->where('batch_id', $batch->id)
            ->update(['batch_id' => null]);

        return $batch->fresh();
    }

    /**
     * Assign teachers to a batch.
     */
    public function assignTeachers(Batch $batch, array $teacherIds): Batch
    {
        $batch->teachers()->sync($teacherIds);
        return $batch->fresh();
    }

    /**
     * Get student count for a batch.
     */
    public function getStudentCount(Batch $batch): int
    {
        return $batch->students()->count();
    }

    /**
     * Check if batch has available capacity.
     */
    public function checkCapacity(Batch $batch): bool
    {
        if (!$batch->max_students) {
            return true;
        }

        return $this->getStudentCount($batch) < $batch->max_students;
    }

    /**
     * Get remaining capacity.
     */
    public function getRemainingCapacity(Batch $batch): ?int
    {
        if (!$batch->max_students) {
            return null;
        }

        return max(0, $batch->max_students - $this->getStudentCount($batch));
    }

    /**
     * Get batch with all relations.
     */
    public function getWithRelations(int $id): Batch
    {
        return Batch::with([
            'course',
            'students.user',
            'teachers.user',
            'schedules',
            'attendances' => function ($query) {
                $query->orderBy('date', 'desc')->limit(30);
            },
            'exams',
        ])->findOrFail($id);
    }

    /**
     * Get paginated batches with optional filters.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Batch::with(['course', 'teachers'])
            ->withCount('students');

        // Filter by course
        if (!empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by search term
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter by available capacity
        if (!empty($filters['has_capacity'])) {
            $query->withAvailableCapacity();
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($perPage);
    }

    /**
     * Get batches by course.
     */
    public function getByCourse(int $courseId): Collection
    {
        return Batch::where('course_id', $courseId)
            ->with(['teachers'])
            ->withCount('students')
            ->get();
    }

    /**
     * Get active batches.
     */
    public function getActive(): Collection
    {
        return Batch::active()
            ->with(['course', 'teachers'])
            ->withCount('students')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get batches with available capacity.
     */
    public function getWithAvailableCapacity(): Collection
    {
        return Batch::active()
            ->withAvailableCapacity()
            ->with(['course'])
            ->withCount('students')
            ->get();
    }

    /**
     * Get batch statistics.
     */
    public function getStatistics(): array
    {
        return [
            'total' => Batch::count(),
            'active' => Batch::active()->count(),
            'inactive' => Batch::inactive()->count(),
            'completed' => Batch::completed()->count(),
            'total_students' => Student::whereNotNull('batch_id')->count(),
            'by_course' => Batch::select('course_id', DB::raw('count(*) as count'))
                ->groupBy('course_id')
                ->with('course')
                ->get(),
        ];
    }

    /**
     * Transfer students between batches.
     */
    public function transferStudents(Batch $fromBatch, Batch $toBatch, array $studentIds): void
    {
        // Check capacity
        $currentCount = $toBatch->students()->count();
        $transferCount = count($studentIds);

        if ($toBatch->max_students && ($currentCount + $transferCount) > $toBatch->max_students) {
            throw new \RuntimeException('Transfer would exceed destination batch capacity');
        }

        Student::whereIn('id', $studentIds)
            ->where('batch_id', $fromBatch->id)
            ->update(['batch_id' => $toBatch->id]);
    }

    /**
     * Get students not assigned to any batch.
     */
    public function getUnassignedStudents(): Collection
    {
        return Student::whereNull('batch_id')
            ->with(['user'])
            ->get();
    }

    /**
     * Search batches.
     */
    public function search(string $term, int $limit = 10): Collection
    {
        return Batch::where('name', 'like', "%{$term}%")
            ->orWhere('code', 'like', "%{$term}%")
            ->with(['course'])
            ->limit($limit)
            ->get();
    }
}
