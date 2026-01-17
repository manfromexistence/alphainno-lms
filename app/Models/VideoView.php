<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoView extends Model
{
    protected $fillable = [
        'course_video_id',
        'student_id',
        'watched_seconds',
        'completed',
        'last_watched_at',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'watched_seconds' => 'integer',
        'last_watched_at' => 'datetime',
    ];

    public function video()
    {
        return $this->belongsTo(CourseVideo::class, 'course_video_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
