<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = ['parent_id', 'teacher_id'];

    public function parent()  { return $this->belongsTo(ParentProfile::class, 'parent_id'); }
    public function teacher() { return $this->belongsTo(TeacherProfile::class, 'teacher_id'); }
}