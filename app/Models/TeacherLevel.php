<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherLevel extends Model
{
    protected $fillable = ['teacher_id', 'level'];

    public static array $labels = [
        'primary' => 'Primaire',
        'middle'  => 'Collège',
        'high'    => 'Lycée',
    ];

    public function teacher()
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_id');
    }
}