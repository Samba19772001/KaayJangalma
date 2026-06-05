<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseRequest extends Model
{
    protected $fillable = [
        'parent_id', 'teacher_id', 'subject_id',
        'level', 'hours', 'address', 'message', 'status'
    ];

    public function parent()  { return $this->belongsTo(ParentProfile::class, 'parent_id'); }
    public function teacher() { return $this->belongsTo(TeacherProfile::class, 'teacher_id'); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function review()  { return $this->hasOne(Review::class); }
}