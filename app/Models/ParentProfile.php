<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentProfile extends Model
{
    protected $fillable = [
        'user_id', 'photo', 'city', 'neighborhood'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'parent_id');
    }

    public function courseRequests()
    {
        return $this->hasMany(CourseRequest::class, 'parent_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'parent_id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'parent_id');
    }

    public function announcements()
    {
        return $this->hasMany(PublicAnnouncement::class, 'parent_id');
    }

    public function favoritedTeachers()
    {
        return $this->belongsToMany(
            TeacherProfile::class,
            'favorites',
            'parent_id',
            'teacher_id'
        )->withTimestamps();
    }
}