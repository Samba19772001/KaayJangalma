<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\TeacherLevel;
use App\Models\TeacherAvailability;
use App\Models\TeacherZone;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherProfileController extends Controller
{
    private function teacherProfile()
    {
        return Auth::user()->teacherProfile;
    }

    // ─── Édition du profil ────────────────────────────────────────
    public function edit()
    {
        $teacher  = $this->teacherProfile()->load('subjects', 'levels', 'availabilities', 'zones', 'documents');
        $subjects = Subject::where('is_active', true)->get();
        $levels   = TeacherLevel::$labels;
        $days     = TeacherAvailability::$days;
        $slots    = TeacherAvailability::$slots;

        return view('teacher.profile', compact('teacher', 'subjects', 'levels', 'days', 'slots'));
    }

    // ─── Mise à jour du profil ────────────────────────────────────
    public function update(Request $request)
    {
        $request->validate([
            'gender'          => 'nullable|in:male,female',
            'birth_date'      => 'nullable|date',
            'whatsapp'        => 'nullable|string|max:20',
            'education_level' => 'nullable|string|max:100',
            'university'      => 'nullable|string|max:150',
            'bio'             => 'nullable|string|max:1000',
            'experience_years'=> 'nullable|integer|min:0|max:50',
            'hourly_rate'     => 'nullable|numeric|min:0',
            'monthly_rate'    => 'nullable|numeric|min:0',
            'photo'           => 'nullable|image|max:2048',
            'subjects'        => 'nullable|array',
            'subjects.*'      => 'exists:subjects,id',
            'levels'          => 'nullable|array',
            'levels.*'        => 'in:primary,middle,high',
            'days'            => 'nullable|array',
            'slots'           => 'nullable|array',
            'region'          => 'nullable|string|max:100',
            'department'      => 'nullable|string|max:100',
            'city'            => 'nullable|string|max:100',
            'neighborhood'    => 'nullable|string|max:100',
        ]);

        $teacher = $this->teacherProfile();
        $data    = $request->only([
            'gender', 'birth_date', 'whatsapp', 'education_level',
            'university', 'bio', 'experience_years', 'hourly_rate', 'monthly_rate',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos/teachers', 'public');
        }

        $teacher->update($data);

        // Mettre à jour le nom
        Auth::user()->update(['name' => $request->name]);

        // Matières
        if ($request->has('subjects')) {
            $teacher->subjects()->sync($request->subjects);
        }

        // Niveaux
        $teacher->levels()->delete();
        foreach ($request->levels ?? [] as $level) {
            $teacher->levels()->create(['level' => $level]);
        }

        // Disponibilités
        $teacher->availabilities()->delete();
        foreach ($request->days ?? [] as $day) {
            foreach ($request->slots ?? [] as $slot) {
                $teacher->availabilities()->create(['day' => $day, 'slot' => $slot]);
            }
        }

        // Zone d'intervention
        $teacher->zones()->delete();
        $teacher->zones()->create([
            'region'       => $request->region,
            'department'   => $request->department,
            'city'         => $request->city,
            'neighborhood' => $request->neighborhood,
        ]);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    // ─── Upload documents ─────────────────────────────────────────
    public function uploadDocuments(Request $request)
    {
        $request->validate([
            'type' => 'required|in:cni,diploma,certificate',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $teacher  = $this->teacherProfile();
        $filePath = $request->file('file')->store('documents/teachers', 'public');

        Document::create([
            'teacher_id' => $teacher->id,
            'type'       => $request->type,
            'file_path'  => $filePath,
            'status'     => 'pending',
        ]);

        return back()->with('success', 'Document envoyé. En attente de validation.');
    }
}