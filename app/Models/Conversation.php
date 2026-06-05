<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['parent_id', 'teacher_id', 'last_message_at'];

    protected $casts = ['last_message_at' => 'datetime'];

    public function parent()   { return $this->belongsTo(ParentProfile::class, 'parent_id'); }
    public function teacher()  { return $this->belongsTo(TeacherProfile::class, 'teacher_id'); }
    public function messages() { return $this->hasMany(Message::class); }

    public function unreadCount(int $userId): int
    {
        return $this->messages()
                    ->where('is_read', false)
                    ->where('sender_id', '!=', $userId)
                    ->count();
    }
}