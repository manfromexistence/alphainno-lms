<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSalary extends Model
{
    protected $fillable = [
        'teacher_id',
        'amount',
        'month',
        'payment_date',
        'payment_method',
        'status',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
