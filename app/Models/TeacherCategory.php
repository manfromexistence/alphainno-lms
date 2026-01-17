<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color'
    ];

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'category_id');
    }
}
