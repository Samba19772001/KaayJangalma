<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherZone extends Model
{
    protected $fillable = ['teacher_id', 'region', 'department', 'city', 'neighborhood'];

    public function teacher()
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_id');
    }
}