<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementApplication extends Model
{
    protected $fillable = ['announcement_id', 'teacher_id', 'message', 'status'];

    public function announcement() { return $this->belongsTo(PublicAnnouncement::class); }
    public function teacher()      { return $this->belongsTo(TeacherProfile::class, 'teacher_id'); }
}