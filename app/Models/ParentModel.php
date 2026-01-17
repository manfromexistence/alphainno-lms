<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ParentModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'parents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'notification_preferences',
        'email_verified_at',
        'phone_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'notification_preferences' => 'array',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the students associated with this parent.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'parent_student', 'parent_id', 'student_id')
            ->withPivot('relationship_type', 'status', 'approved_by', 'approved_at')
            ->withTimestamps();
    }

    /**
     * Get only approved students.
     */
    public function approvedStudents(): BelongsToMany
    {
        return $this->students()->wherePivot('status', 'approved');
    }

    /**
     * Check if parent has access to a specific student.
     */
    public function hasAccessToStudent(int $studentId): bool
    {
        return $this->approvedStudents()->where('students.id', $studentId)->exists();
    }
}
