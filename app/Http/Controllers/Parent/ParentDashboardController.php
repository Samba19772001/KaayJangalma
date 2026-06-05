<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\CourseRequest;
use App\Models\PublicAnnouncement;
use App\Models\Review;
use App\Models\Subject;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    private function parentProfile()
    {
        return Auth::user()->parentProfile;
    }

    // ─── Tableau de bord ──────────────────────────────────────────
    public function index()
    {
        $parent = $this->parentProfile();
        $stats  = [
            'requests'  => $parent->courseRequests()->count(),
            'favorites' => $parent->favorites()->count(),
            'messages'  => $parent->conversations()->withCount(['messages' => fn($q) => $q->where('is_read', false)->where('sender_id', '!=', Auth::id())])->get()->sum('messages_count'),
        ];
        $recentRequests = $parent->courseRequests()->with('teacher.user', 'subject')->latest()->take(5)->get();

        return view('parent.dashboard', compact('stats', 'recentRequests'));
    }

    // ─── Profil ───────────────────────────────────────────────────
    public function editProfile()
    {
        $parent = $this->parentProfile();
        return view('parent.profile', compact('parent'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'city'         => 'nullable|string|max:100',
            'neighborhood' => 'nullable|string|max:100',
            'photo'        => 'nullable|image|max:2048',
        ]);

        $parent = $this->parentProfile();
        $data   = $request->only('city', 'neighborhood');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos/parents', 'public');
        }

        $parent->update($data);

        // Mettre à jour le nom dans users
        Auth::user()->update(['name' => $request->name]);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    // ─── Favoris ──────────────────────────────────────────────────
    public function favorites()
    {
        $parent    = $this->parentProfile();
        $favorites = $parent->favoritedTeachers()->with('user', 'subjects', 'zones')->get();

        return view('parent.favorites', compact('favorites'));
    }

    public function toggleFavorite(Request $request, $teacherId)
    {
        $parent  = $this->parentProfile();
        $teacher = TeacherProfile::findOrFail($teacherId);

        $existing = Favorite::where('parent_id', $parent->id)
                            ->where('teacher_id', $teacher->id)
                            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Professeur retiré des favoris.';
        } else {
            Favorite::create(['parent_id' => $parent->id, 'teacher_id' => $teacher->id]);
            $message = 'Professeur ajouté aux favoris.';
        }

        return back()->with('success', $message);
    }

    // ─── Demandes de cours ────────────────────────────────────────
    public function requests()
    {
        $parent   = $this->parentProfile();
        $requests = $parent->courseRequests()->with('teacher.user', 'subject')->latest()->get();

        return view('parent.requests', compact('requests'));
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teacher_profiles,id',
            'subject_id' => 'required|exists:subjects,id',
            'level'      => 'required|in:primary,middle,high',
            'hours'      => 'nullable|integer|min:1',
            'address'    => 'nullable|string|max:255',
            'message'    => 'nullable|string|max:1000',
        ]);

        $parent = $this->parentProfile();

        CourseRequest::create([
            'parent_id'  => $parent->id,
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'level'      => $request->level,
            'hours'      => $request->hours,
            'address'    => $request->address,
            'message'    => $request->message,
            'status'     => 'sent',
        ]);

        return back()->with('success', 'Demande de cours envoyée avec succès.');
    }

    // ─── Annonces publiques ───────────────────────────────────────
    public function announcements()
    {
        $parent      = $this->parentProfile();
        $subjects    = Subject::where('is_active', true)->get();
        $announcements = $parent->announcements()->with('subject')->latest()->get();

        return view('parent.announcements', compact('announcements', 'subjects'));
    }

    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'subject_id'  => 'required|exists:subjects,id',
            'level'       => 'required|in:primary,middle,high',
            'city'        => 'nullable|string|max:100',
            'neighborhood'=> 'nullable|string|max:100',
            'budget'      => 'nullable|numeric|min:0',
            'description' => 'required|string|max:1000',
        ]);

        $parent = $this->parentProfile();

        PublicAnnouncement::create([
            'parent_id'    => $parent->id,
            'subject_id'   => $request->subject_id,
            'level'        => $request->level,
            'city'         => $request->city,
            'neighborhood' => $request->neighborhood,
            'budget'       => $request->budget,
            'description'  => $request->description,
        ]);

        return back()->with('success', 'Annonce publiée avec succès.');
    }

    // ─── Avis ─────────────────────────────────────────────────────
    public function storeReview(Request $request, $courseRequestId)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $parent        = $this->parentProfile();
        $courseRequest = CourseRequest::where('id', $courseRequestId)
                                      ->where('parent_id', $parent->id)
                                      ->where('status', 'completed')
                                      ->firstOrFail();

        // Vérifier qu'un avis n'existe pas déjà
        if ($courseRequest->review) {
            return back()->withErrors(['rating' => 'Vous avez déjà donné un avis pour ce cours.']);
        }

        Review::create([
            'parent_id'         => $parent->id,
            'teacher_id'        => $courseRequest->teacher_id,
            'course_request_id' => $courseRequest->id,
            'rating'            => $request->rating,
            'comment'           => $request->comment,
        ]);

        return back()->with('success', 'Avis publié avec succès.');
    }
}