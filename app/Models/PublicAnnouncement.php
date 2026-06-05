<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicAnnouncement extends Model
{
    protected $fillable = [
        'parent_id', 'subject_id', 'level',
        'city', 'neighborhood', 'budget', 'description', 'is_active'
    ];

    public function parent()       { return $this->belongsTo(ParentProfile::class, 'parent_id'); }
    public function subject()      { return $this->belongsTo(Subject::class); }
    public function applications() { return $this->hasMany(AnnouncementApplication::class, 'announcement_id'); }
}