<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'teacher_id', 'plan', 'amount',
        'starts_at', 'ends_at', 'status'
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at'   => 'date',
    ];

    public function teacher() { return $this->belongsTo(TeacherProfile::class, 'teacher_id'); }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at->isFuture();
    }
}