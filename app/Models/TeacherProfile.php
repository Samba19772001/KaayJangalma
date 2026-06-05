<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    protected $fillable = [
        'user_id', 'photo', 'gender', 'birth_date', 'whatsapp',
        'education_level', 'university', 'bio', 'experience_years',
        'hourly_rate', 'monthly_rate', 'verified_status', 'is_premium',
        'average_rating', 'total_reviews', 'profile_views', 'whatsapp_clicks',
    ];

    protected $casts = [
        'birth_date'     => 'date',
        'is_premium'     => 'boolean',
        'hourly_rate'    => 'decimal:2',
        'monthly_rate'   => 'decimal:2',
        'average_rating' => 'float',
    ];

    public function user()           { return $this->belongsTo(User::class); }
    public function subjects()       { return $this->belongsToMany(Subject::class, 'teacher_subjects', 'teacher_id', 'subject_id'); }
    public function levels()         { return $this->hasMany(TeacherLevel::class, 'teacher_id'); }
    public function availabilities() { return $this->hasMany(TeacherAvailability::class, 'teacher_id'); }
    public function zones()          { return $this->hasMany(TeacherZone::class, 'teacher_id'); }
    public function documents()      { return $this->hasMany(Document::class, 'teacher_id'); }
    public function reviews()        { return $this->hasMany(Review::class, 'teacher_id'); }
    public function courseRequests() { return $this->hasMany(CourseRequest::class, 'teacher_id'); }
    public function subscriptions()  { return $this->hasMany(Subscription::class, 'teacher_id'); }
    public function conversations()  { return $this->hasMany(Conversation::class, 'teacher_id'); }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class, 'teacher_id')
                    ->where('status', 'active')
                    ->where('ends_at', '>=', now());
    }

    // Scopes
    public function scopeVerified($q)           { return $q->where('verified_status', 'verified'); }
    public function scopePremium($q)            { return $q->where('is_premium', true); }
    public function scopeBySubject($q, $id)     { return $q->whereHas('subjects', fn($s) => $s->where('subjects.id', $id)); }
    public function scopeByLevel($q, $level)    { return $q->whereHas('levels', fn($l) => $l->where('level', $level)); }
    public function scopeByCity($q, $city)      { return $q->whereHas('zones', fn($z) => $z->where('city', $city)); }

    // Helpers
    public function isVerified(): bool { return $this->verified_status === 'verified'; }

    public function recalculateRating(): void
    {
        $this->update([
            'average_rating' => round($this->reviews()->avg('rating') ?? 0, 2),
            'total_reviews'  => $this->reviews()->count(),
        ]);
    }
}