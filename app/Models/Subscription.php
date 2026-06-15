<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'teacher_id', 'plan', 'amount', 'starts_at', 'ends_at',
        'status', 'payment_method', 'payment_reference',
        'payment_confirmed_at', 'confirmed_by', 'payment_note',
    ];

    protected $casts = [
        'starts_at'             => 'date',
        'ends_at'               => 'date',
        'payment_confirmed_at'  => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_id');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at->isFuture();
    }

    public function isPendingPayment(): bool
    {
        return $this->status === 'pending_payment';
    }
}