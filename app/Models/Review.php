<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'parent_id', 'teacher_id', 'course_request_id',
        'rating', 'comment', 'is_moderated'
    ];

    public function parent()        { return $this->belongsTo(ParentProfile::class, 'parent_id'); }
    public function teacher()       { return $this->belongsTo(TeacherProfile::class, 'teacher_id'); }
    public function courseRequest() { return $this->belongsTo(CourseRequest::class); }

    protected static function booted(): void
    {
        static::saved(function (Review $review) {
            $review->teacher->recalculateRating();
        });
        static::deleted(function (Review $review) {
            $review->teacher->recalculateRating();
        });
    }
}