<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'department',
        'subjects',
        'status',
        'profile_image',
        'salary',
        'category_id'
    ];

    protected $casts = [
        'subjects' => 'array',
        'salary' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'teacher_batch');
    }

    public function category()
    {
        return $this->belongsTo(TeacherCategory::class, 'category_id');
    }

    public function salaries()
    {
        return $this->hasMany(TeacherSalary::class);
    }
}
