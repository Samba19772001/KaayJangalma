<?php

namespace App\Console\Commands;

use App\Models\TeacherProfile;
use Illuminate\Console\Command;

class RecalculateTopTeachers extends Command
{
    protected $signature   = 'teachers:recalculate-top';
    protected $description = 'Recalcule le badge Top Professeur pour tous les enseignants';

    public function handle(): void
    {
        $teachers = TeacherProfile::where('verified_status', 'verified')->get();
        $count    = 0;

        foreach ($teachers as $teacher) {
            $wasTop = $teacher->is_top;
            $teacher->recalculateRating();
            $teacher->recalculateTopStatus();

            if (!$wasTop && $teacher->fresh()->is_top) {
                // Notification au professeur qui vient d'obtenir le badge
                \App\Models\Notification::create([
                    'user_id' => $teacher->user_id,
                    'type'    => 'top_badge',
                    'data'    => json_encode([
                        'message' => '🏆 Félicitations ! Vous avez obtenu le badge Top Professeur !',
                    ]),
                ]);
                $count++;
            }
        }

        $this->info("Recalcul terminé. {$count} nouveau(x) Top Professeur(s) attribué(s).");
    }
}