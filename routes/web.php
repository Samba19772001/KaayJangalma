<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Parent\ParentDashboardController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;

// ─── Accueil ──────────────────────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('home');

// ─── Test ─────────────────────────────────────────────────────────
Route::get('/test', function () { return 'Laravel fonctionne !'; });
Route::get('/test-teacher', function () { return 'Route teacher fonctionne !'; })->middleware('auth');

// ─── Authentification ─────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/inscription',         [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/inscription',        [AuthController::class, 'register']);
    Route::get('/connexion',           [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/connexion',          [AuthController::class, 'login']);
    Route::get('/mot-de-passe-oublie', [AuthController::class, 'showForgotPassword'])->name('auth.forgot');
    Route::post('/envoyer-otp',        [AuthController::class, 'sendOtp'])->name('auth.otp.send');
    Route::get('/verification-otp',    [AuthController::class, 'showOtpForm'])->name('auth.otp.form');
    Route::post('/verification-otp',   [AuthController::class, 'verifyOtp'])->name('auth.otp.verify');
});

Route::post('/deconnexion', [AuthController::class, 'logout'])
     ->name('auth.logout')->middleware('auth');

// ─── Recherche publique ───────────────────────────────────────────
Route::get('/recherche', [SearchController::class, 'index'])->name('search.index');

// ─── Espace Parent ────────────────────────────────────────────────
Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/tableau-de-bord',         [ParentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil',                  [ParentDashboardController::class, 'editProfile'])->name('profile');
    Route::put('/profil',                  [ParentDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/favoris',                 [ParentDashboardController::class, 'favorites'])->name('favorites');
    Route::post('/favoris/{id}',           [ParentDashboardController::class, 'toggleFavorite'])->name('favorites.toggle');
    Route::get('/demandes',                [ParentDashboardController::class, 'requests'])->name('requests');
    Route::post('/demandes',               [ParentDashboardController::class, 'storeRequest'])->name('requests.store');
    Route::get('/annonces',                [ParentDashboardController::class, 'announcements'])->name('announcements');
    Route::post('/annonces',               [ParentDashboardController::class, 'storeAnnouncement'])->name('announcements.store');
    Route::post('/avis/{courseRequestId}', [ParentDashboardController::class, 'storeReview'])->name('reviews.store');
});

// ─── Espace Professeur ────────────────────────────────────────────
Route::middleware(['auth', 'role:teacher'])->prefix('professeur')->name('teacher.')->group(function () {
    Route::get('/tableau-de-bord',  [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil',           [TeacherProfileController::class, 'edit'])->name('profile');
    Route::put('/profil',           [TeacherProfileController::class, 'update'])->name('profile.update');
    Route::post('/documents',       [TeacherProfileController::class, 'uploadDocuments'])->name('documents.upload');
    Route::get('/demandes',         [TeacherDashboardController::class, 'requests'])->name('requests');
    Route::patch('/demandes/{id}',  [TeacherDashboardController::class, 'updateRequest'])->name('requests.update');
    Route::get('/statistiques',     [TeacherDashboardController::class, 'stats'])->name('stats');
    Route::get('/abonnement',       [TeacherDashboardController::class, 'subscription'])->name('subscription');
    Route::post('/abonnement',      [TeacherDashboardController::class, 'subscribe'])->name('subscription.store');
    Route::get('/annonces-publiques', [TeacherDashboardController::class, 'publicAnnouncements'])->name('announcements');
    Route::post('/annonces-publiques/{id}/postuler', [TeacherDashboardController::class, 'applyAnnouncement'])->name('announcements.apply');
});

// ─── Messagerie (parent + professeur) ────────────────────────────
Route::middleware(['auth', 'role:parent,teacher'])->prefix('messages')->name('messages.')->group(function () {
    Route::get('/',                        [MessageController::class, 'index'])->name('index');
    Route::get('/{conversationId}',        [MessageController::class, 'show'])->name('show');
    Route::post('/{conversationId}',       [MessageController::class, 'send'])->name('send');
    Route::post('/nouvelle/{teacherId}',   [MessageController::class, 'newConversation'])->name('new');
});

// ─── Notifications ────────────────────────────────────────────────
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/',           [NotificationController::class, 'index'])->name('index');
    Route::patch('/{id}/lu',  [NotificationController::class, 'markAsRead'])->name('read');
    Route::patch('/tout-lu',  [NotificationController::class, 'markAllAsRead'])->name('readAll');
});

// ─── Espace Professeur public ─────────────────────────────────────
Route::middleware(['auth', 'role:teacher'])->prefix('professeur')->name('teacher.')->group(function () {
    // déjà défini au-dessus
});

// ─── Administration ───────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/tableau-de-bord',    [AdminController::class, 'index'])->name('dashboard');
    Route::get('/professeurs',        [AdminController::class, 'teachers'])->name('teachers');
    Route::get('/professeurs/{id}',   [AdminController::class, 'showTeacher'])->name('teachers.show');
    Route::patch('/professeurs/{id}', [AdminController::class, 'verifyTeacher'])->name('teachers.verify');
    Route::get('/matieres',           [AdminController::class, 'subjects'])->name('subjects');
    Route::post('/matieres',          [AdminController::class, 'storeSubject'])->name('subjects.store');
    Route::patch('/matieres/{id}',    [AdminController::class, 'toggleSubject'])->name('subjects.toggle');
    Route::get('/avis',               [AdminController::class, 'reviews'])->name('reviews');
    Route::delete('/avis/{id}',       [AdminController::class, 'deleteReview'])->name('reviews.delete');
});

// ─── Profil public professeur (après les routes authentifiées) ────
Route::get('/professeur/{id}', [SearchController::class, 'show'])->name('teacher.public');