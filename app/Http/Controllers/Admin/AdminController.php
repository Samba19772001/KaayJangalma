<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\Document;
use App\Models\Subject;
use App\Models\CourseRequest;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ─── Tableau de bord ──────────────────────────────────────────
    public function index()
    {
        $stats = [
            'parents'   => User::where('role', 'parent')->count(),
            'teachers'  => User::where('role', 'teacher')->count(),
            'premium'   => TeacherProfile::where('is_premium', true)->count(),
            'verified'  => TeacherProfile::where('verified_status', 'verified')->count(),
            'pending'   => TeacherProfile::where('verified_status', 'pending')->count(),
            'requests'  => CourseRequest::count(),
        ];

        $pendingTeachers = TeacherProfile::with('user', 'documents')
                                         ->where('verified_status', 'pending')
                                         ->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'pendingTeachers'));
    }

    // ─── Professeurs ──────────────────────────────────────────────
    public function teachers(Request $request)
    {
        $teachers = TeacherProfile::with('user')
                                  ->when($request->status, fn($q) => $q->where('verified_status', $request->status))
                                  ->latest()->paginate(20);

        return view('admin.teachers', compact('teachers'));
    }

    public function verifyTeacher(Request $request, $id)
    {
        $request->validate([
            'status'     => 'required|in:verified,refused,pending',
            'admin_note' => 'nullable|string|max:500',
        ]);

        $teacher = TeacherProfile::findOrFail($id);
        $teacher->update(['verified_status' => $request->status]);

        $teacher->documents()->update([
            'status'     => $request->status === 'pending' ? 'pending' : $request->status,
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Statut mis à jour avec succès.');
    }

    // ─── Matières ─────────────────────────────────────────────────
    public function subjects()
    {
        $subjects = Subject::latest()->get();
        return view('admin.subjects', compact('subjects'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:subjects,name|max:100']);
        Subject::create(['name' => $request->name]);
        return back()->with('success', 'Matière ajoutée.');
    }

    public function toggleSubject($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->update(['is_active' => !$subject->is_active]);
        return back()->with('success', 'Statut de la matière mis à jour.');
    }

    // ─── Avis ─────────────────────────────────────────────────────
    public function reviews()
    {
        $reviews = Review::with('parent.user', 'teacher.user')->latest()->paginate(20);
        return view('admin.reviews', compact('reviews'));
    }

    public function deleteReview($id)
    {
        Review::findOrFail($id)->delete();
        return back()->with('success', 'Avis supprimé.');
    }

    public function showTeacher($id)
    {
        $teacher = TeacherProfile::with('user', 'subjects', 'levels', 'zones', 'documents', 'reviews')
                                ->findOrFail($id);
        return view('admin.teacher-detail', compact('teacher'));
    }

    public function payments()
    {
        $pending   = \App\Models\Subscription::with('teacher.user')
                                            ->where('status', 'pending_payment')
                                            ->latest()
                                            ->get();

        $confirmed = \App\Models\Subscription::with('teacher.user', 'confirmedBy')
                                            ->where('status', 'active')
                                            ->latest()
                                            ->take(20)
                                            ->get();

        return view('admin.payments', compact('pending', 'confirmed'));
    }

    public function confirmPayment(Request $request, $id)
    {
        $request->validate([
            'action'       => 'required|in:confirm,reject',
            'payment_note' => 'nullable|string|max:500',
        ]);

        $subscription = \App\Models\Subscription::with('teacher.user')->findOrFail($id);

        if ($request->action === 'confirm') {
            $subscription->update([
                'status'                => 'active',
                'payment_confirmed_at'  => now(),
                'confirmed_by'          => Auth::id(),
                'payment_note'          => $request->payment_note,
            ]);

            // Activer le premium du professeur
            $subscription->teacher->update(['is_premium' => true]);

            // Notification au professeur
            \App\Models\Notification::create([
                'user_id' => $subscription->teacher->user_id,
                'type'    => 'payment_confirmed',
                'data'    => json_encode([
                    'message' => 'Votre paiement a été confirmé ! Abonnement Premium activé.',
                    'plan'    => $subscription->plan,
                    'ends_at' => $subscription->ends_at->format('d/m/Y'),
                ]),
            ]);

            $msg = 'Paiement confirmé et abonnement activé !';
        } else {
            $subscription->update([
                'status'       => 'cancelled',
                'payment_note' => $request->payment_note,
            ]);

            // Notification au professeur
            \App\Models\Notification::create([
                'user_id' => $subscription->teacher->user_id,
                'type'    => 'payment_rejected',
                'data'    => json_encode([
                    'message' => 'Votre paiement n\'a pas pu être confirmé. Contactez l\'admin.',
                    'note'    => $request->payment_note,
                ]),
            ]);

            $msg = 'Paiement rejeté.';
        }

        return back()->with('success', $msg);
    }
}