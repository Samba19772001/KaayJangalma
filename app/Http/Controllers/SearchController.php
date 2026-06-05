<?php

namespace App\Http\Controllers;

use App\Models\TeacherProfile;
use App\Models\Subject;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::where('is_active', true)->get();

        $query = TeacherProfile::with('user', 'subjects', 'zones', 'levels')
                               ->where('verified_status', 'verified');

        if ($request->filled('subject_id')) {
            $query->bySubject($request->subject_id);
        }

        if ($request->filled('level')) {
            $query->byLevel($request->level);
        }

        if ($request->filled('city')) {
            $query->byCity($request->city);
        }

        if ($request->filled('min_rate')) {
            $query->where('hourly_rate', '>=', $request->min_rate);
        }

        if ($request->filled('max_rate')) {
            $query->where('hourly_rate', '<=', $request->max_rate);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->boolean('premium_only')) {
            $query->premium();
        }

        // Priorité aux profils Premium
        $query->orderByDesc('is_premium')->orderByDesc('average_rating');

        $teachers = $query->paginate(12)->withQueryString();

        return view('search.index', compact('teachers', 'subjects'));
    }

    public function show($id)
    {
        $teacher = TeacherProfile::with('user', 'subjects', 'levels', 'availabilities', 'zones', 'reviews.parent.user')
                                 ->where('verified_status', 'verified')
                                 ->findOrFail($id);

        // Incrémenter les vues
        $teacher->increment('profile_views');

        $isFavorite = false;
        if (Auth::check() && Auth::user()->isParent()) {
            $isFavorite = Favorite::where('parent_id', Auth::user()->parentProfile->id)
                                  ->where('teacher_id', $teacher->id)
                                  ->exists();
        }

        $subjects = \App\Models\Subject::where('is_active', true)->get();

        return view('search.show', compact('teacher', 'isFavorite', 'subjects'));
    }
}