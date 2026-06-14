<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        $teacher = $user->teacherProfile;

        if (!$teacher) {
            return redirect()->route('auth.login')->with('error', 'Profil professeur introuvable.');
        }

        $stats = [
            'views'           => $teacher->profile_views ?? 0,
            'whatsapp_clicks' => $teacher->whatsapp_clicks ?? 0,
            'requests'        => $teacher->courseRequests()->count(),
            'messages'        => $teacher->conversations()->count(),
            'average_rating'  => $teacher->average_rating ?? 0,
            'total_reviews'   => $teacher->total_reviews ?? 0,
        ];

        $recentRequests = $teacher->courseRequests()
                                ->with('parent.user', 'subject')
                                ->latest()
                                ->take(5)
                                ->get();

        return view('teacher.dashboard', compact('stats', 'recentRequests', 'teacher'));
    }

    public function requests()
    {
        $teacher  = Auth::user()->teacherProfile;
        $requests = $teacher->courseRequests()
                            ->with('parent.user', 'subject')
                            ->latest()
                            ->get();

        return view('teacher.requests', compact('requests'));
    }

    public function updateRequest(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:accepted,refused,completed']);

        $teacher       = Auth::user()->teacherProfile;
        $courseRequest = \App\Models\CourseRequest::where('id', $id)
                                                ->where('teacher_id', $teacher->id)
                                                ->firstOrFail();

        $courseRequest->update(['status' => $request->status]);

        // Notification au parent
        $messages = [
            'accepted'  => 'Votre demande de cours a été acceptée par '.$teacher->user->name,
            'refused'   => 'Votre demande de cours a été refusée par '.$teacher->user->name,
            'completed' => 'Votre cours avec '.$teacher->user->name.' est marqué comme terminé',
        ];

        \App\Models\Notification::create([
            'user_id' => $courseRequest->parent->user_id,
            'type'    => 'request_'.$request->status,
            'data'    => json_encode([
                'message'    => $messages[$request->status],
                'teacher'    => $teacher->user->name,
                'request_id' => $courseRequest->id,
            ]),
        ]);

        $labels = [
            'accepted'  => 'Demande acceptée avec succès.',
            'refused'   => 'Demande refusée.',
            'completed' => 'Cours marqué comme terminé.',
        ];

        return back()->with('success', $labels[$request->status]);
    }

    public function stats()
    {
        $teacher = Auth::user()->teacherProfile;
        $stats = [
            'profile_views'   => $teacher->profile_views ?? 0,
            'whatsapp_clicks' => $teacher->whatsapp_clicks ?? 0,
            'total_requests'  => 0,
            'accepted'        => 0,
            'completed'       => 0,
            'average_rating'  => $teacher->average_rating ?? 0,
            'total_reviews'   => $teacher->total_reviews ?? 0,
        ];
        $reviewsByRating = collect();
        return view('teacher.stats', compact('stats', 'reviewsByRating'));
    }

    public function subscription()
    {
        $teacher = Auth::user()->teacherProfile;
        $active  = null;
        $history = collect();
        return view('teacher.subscription', compact('teacher', 'active', 'history'));
    }

    public function subscribe(Request $request)
    {
        return back()->with('success', 'Abonnement activé.');
    }

    public function publicAnnouncements()
    {
        $teacher       = Auth::user()->teacherProfile;
        $announcements = \App\Models\PublicAnnouncement::with('parent.user', 'subject')
                                                    ->where('is_active', true)
                                                    ->latest()
                                                    ->get();

        // Récupérer les annonces auxquelles le professeur a déjà postulé
        $appliedIds = \App\Models\AnnouncementApplication::where('teacher_id', $teacher->id)
                                                        ->pluck('announcement_id')
                                                        ->toArray();

        return view('teacher.announcements', compact('announcements', 'appliedIds'));
    }

    public function applyAnnouncement(Request $request, $id)
    {
        $request->validate(['message' => 'nullable|string|max:500']);

        $teacher      = Auth::user()->teacherProfile;
        $announcement = \App\Models\PublicAnnouncement::findOrFail($id);

        // Vérifier si déjà postulé
        $exists = \App\Models\AnnouncementApplication::where('teacher_id', $teacher->id)
                                                    ->where('announcement_id', $id)
                                                    ->exists();
        if ($exists) {
            return back()->withErrors(['message' => 'Vous avez déjà postulé à cette annonce.']);
        }

        \App\Models\AnnouncementApplication::create([
            'announcement_id' => $id,
            'teacher_id'      => $teacher->id,
            'message'         => $request->message,
            'status'          => 'pending',
        ]);

        // Notification au parent
        \App\Models\Notification::create([
            'user_id' => $announcement->parent->user_id,
            'type'    => 'new_application',
            'data'    => json_encode([
                'message'         => $teacher->user->name.' a postulé à votre annonce',
                'teacher'         => $teacher->user->name,
                'announcement_id' => $id,
            ]),
        ]);

        return back()->with('success', 'Candidature envoyée avec succès !');
    }
}