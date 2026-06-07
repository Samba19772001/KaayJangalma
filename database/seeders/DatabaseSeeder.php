<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Subject;
use App\Models\ParentProfile;
use App\Models\TeacherProfile;
use App\Models\TeacherLevel;
use App\Models\TeacherZone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Matières ──────────────────────────────────────────────
        $subjects = [
            'Mathématiques', 'Physique', 'Chimie', 'SVT',
            'Français', 'Anglais', 'Arabe',
            'Histoire-Géographie', 'Philosophie', 'Informatique',
        ];

        foreach ($subjects as $name) {
            Subject::firstOrCreate(['name' => $name]);
        }

        // ── Admin ─────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@kaay.sn'],
            [
                'name'     => 'Administrateur',
                'phone'    => '700000000',
                'password' => Hash::make('admin1234'),
                'role'     => 'admin',
            ]
        );

        // ── Parent de test ────────────────────────────────────────
        $parentUser = User::firstOrCreate(
            ['phone' => '771111111'],
            [
                'name'     => 'Fatou Ndiaye',
                'email'    => 'fatou@test.sn',
                'password' => Hash::make('password'),
                'role'     => 'parent',
            ]
        );

        ParentProfile::firstOrCreate(
            ['user_id' => $parentUser->id],
            [
                'city'         => 'Touba',
                'neighborhood' => 'Darou Khoudoss',
            ]
        );

        // ── Professeur de test 1 ──────────────────────────────────
        $teacherUser1 = User::firstOrCreate(
            ['phone' => '772222222'],
            [
                'name'     => 'Moussa Diallo',
                'email'    => 'moussa@test.sn',
                'password' => Hash::make('password'),
                'role'     => 'teacher',
            ]
        );

        $teacher1 = TeacherProfile::firstOrCreate(
            ['user_id' => $teacherUser1->id],
            [
                'whatsapp'        => '772222222',
                'gender'          => 'male',
                'education_level' => 'Master',
                'university'      => 'UCAD',
                'bio'             => 'Professeur de Mathématiques et Physique avec 5 ans d\'expérience. Spécialisé dans la préparation au Baccalauréat.',
                'experience_years'=> 5,
                'hourly_rate'     => 3000,
                'monthly_rate'    => 50000,
                'verified_status' => 'verified',
                'is_premium'      => true,
                'average_rating'  => 4.5,
                'total_reviews'   => 12,
            ]
        );

        // Matières
        $math    = Subject::where('name', 'Mathématiques')->first();
        $physique = Subject::where('name', 'Physique')->first();
        if ($math)     $teacher1->subjects()->syncWithoutDetaching([$math->id]);
        if ($physique) $teacher1->subjects()->syncWithoutDetaching([$physique->id]);

        // Niveaux
        TeacherLevel::firstOrCreate(['teacher_id' => $teacher1->id, 'level' => 'middle']);
        TeacherLevel::firstOrCreate(['teacher_id' => $teacher1->id, 'level' => 'high']);

        // Zone
        TeacherZone::firstOrCreate(
            ['teacher_id' => $teacher1->id],
            [
                'region'       => 'Dakar',
                'department'   => 'Dakar',
                'city'         => 'Dakar',
                'neighborhood' => 'Plateau',
            ]
        );

        // ── Professeur de test 2 ──────────────────────────────────
        $teacherUser2 = User::firstOrCreate(
            ['phone' => '773333333'],
            [
                'name'     => 'Aissatou Sow',
                'email'    => 'aissatou@test.sn',
                'password' => Hash::make('password'),
                'role'     => 'teacher',
            ]
        );

        $teacher2 = TeacherProfile::firstOrCreate(
            ['user_id' => $teacherUser2->id],
            [
                'whatsapp'        => '773333333',
                'gender'          => 'female',
                'education_level' => 'Licence',
                'university'      => 'UGB',
                'bio'             => 'Professeure de Français et Anglais. Méthode pédagogique adaptée à chaque élève.',
                'experience_years'=> 3,
                'hourly_rate'     => 2500,
                'verified_status' => 'verified',
                'is_premium'      => false,
                'average_rating'  => 4.0,
                'total_reviews'   => 7,
            ]
        );

        $francais = Subject::where('name', 'Français')->first();
        $anglais  = Subject::where('name', 'Anglais')->first();
        if ($francais) $teacher2->subjects()->syncWithoutDetaching([$francais->id]);
        if ($anglais)  $teacher2->subjects()->syncWithoutDetaching([$anglais->id]);

        TeacherLevel::firstOrCreate(['teacher_id' => $teacher2->id, 'level' => 'primary']);
        TeacherLevel::firstOrCreate(['teacher_id' => $teacher2->id, 'level' => 'middle']);

        TeacherZone::firstOrCreate(
            ['teacher_id' => $teacher2->id],
            [
                'region'       => 'Diourbel',
                'department'   => 'Mbacké',
                'city'         => 'Touba',
                'neighborhood' => 'Ndamatou',
            ]
        );
    }
}