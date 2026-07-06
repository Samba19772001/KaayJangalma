<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\CourseRequest;
use App\Models\Subscription;
use App\Models\PublicAnnouncement;
use App\Models\AnnouncementApplication;
use App\Models\Notification;
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
        $request->validate([
            'plan'              => 'required|in:quarterly,biannual,annual',
            'payment_method'    => 'required|in:wave,orange_money,virement',
            'payment_reference' => 'required|string|max:100',
        ]);

        $teacher = Auth::user()->teacherProfile;

        // Vérifier qu'il n'y a pas déjà un abonnement actif ou en attente
        $existing = Subscription::where('teacher_id', $teacher->id)
                                ->whereIn('status', ['active', 'pending_payment'])
                                ->first();

        if ($existing) {
            if ($existing->status === 'active') {
                return back()->withErrors(['plan' => 'Vous avez déjà un abonnement actif.']);
            }
            return back()->withErrors(['plan' => 'Vous avez déjà une demande en attente de confirmation.']);
        }

        $plans = [
            'quarterly' => ['months' => 3,  'amount' => 5900],
            'biannual'  => ['months' => 6,  'amount' => 9900],
            'annual'    => ['months' => 12, 'amount' => 14900],
        ];

        $plan = $plans[$request->plan];

        Subscription::create([
            'teacher_id'        => $teacher->id,
            'plan'              => $request->plan,
            'amount'            => $plan['amount'],
            'starts_at'         => now(),
            'ends_at'           => now()->addMonths($plan['months']),
            'status'            => 'pending_payment',
            'payment_method'    => $request->payment_method,
            'payment_reference' => $request->payment_reference,
        ]);

        // Notification à l'admin
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'type'    => 'new_payment',
                'data'    => json_encode([
                    'message'   => $teacher->user->name.' a soumis un paiement Premium ('.$request->payment_method.')',
                    'teacher'   => $teacher->user->name,
                    'plan'      => $request->plan,
                    'amount'    => $plan['amount'],
                    'reference' => $request->payment_reference,
                ]),
            ]);
        }

        return back()->with('success', 'Votre demande d\'abonnement a été soumise. L\'admin confirmera votre paiement sous 24h.');
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