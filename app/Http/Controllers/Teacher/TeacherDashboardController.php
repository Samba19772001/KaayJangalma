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
            'requests'        => 0,
            'messages'        => 0,
            'average_rating'  => $teacher->average_rating ?? 0,
            'total_reviews'   => $teacher->total_reviews ?? 0,
        ];

        $recentRequests = collect();

        return view('teacher.dashboard', compact('stats', 'recentRequests', 'teacher'));
    }

    public function requests()
    {
        $teacher  = Auth::user()->teacherProfile;
        $requests = collect();
        return view('teacher.requests', compact('requests'));
    }

    public function updateRequest(Request $request, $id)
    {
        return back()->with('success', 'Statut mis à jour.');
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
}