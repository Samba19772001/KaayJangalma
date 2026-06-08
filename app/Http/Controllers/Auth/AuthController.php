<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ParentProfile;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ─── Inscription ──────────────────────────────────────────────

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'nullable|email|unique:users,email',
            'phone'    => 'required|string|max:20|unique:users,phone',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => 'required|in:parent,teacher',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        if ($user->isParent()) {
            ParentProfile::create(['user_id' => $user->id]);
        } else {
            TeacherProfile::create(['user_id' => $user->id]);
        }

        Auth::login($user);

        return redirect()->route($user->isParent() ? 'parent.dashboard' : 'teacher.dashboard')
                         ->with('success', 'Bienvenue sur KaayJangalma !');
    }

    // ─── Connexion ────────────────────────────────────────────────

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->login;
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (!Auth::attempt([$field => $login, 'password' => $request->password], $request->boolean('remember'))) {
            return back()->withErrors(['login' => 'Identifiants incorrects.'])->withInput();
        }

        $request->session()->regenerate();

        return $this->redirectAfterLogin(Auth::user());
    }

    // ─── Mot de passe oublié / OTP ────────────────────────────────

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['phone' => 'required|string|exists:users,phone']);

        $user = User::where('phone', $request->phone)->firstOrFail();
        $otp  = $user->generateOtp();

        // TODO: Intégrer un service SMS
        // En développement : l'OTP s'affiche dans la session
        session(['otp_user_id' => $user->id]);

        return redirect()->route('auth.otp.form')
                         ->with('info', "Code OTP envoyé (dev: $otp)");
    }

    public function showOtpForm()
    {
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp'      => 'required|string|size:6',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::findOrFail(session('otp_user_id'));

        if (!$user->isOtpValid($request->otp)) {
            return back()->withErrors(['otp' => 'Code invalide ou expiré.']);
        }

        $user->update([
            'password'       => Hash::make($request->password),
            'otp_code'       => null,
            'otp_expires_at' => null,
        ]);

        Auth::login($user);
        session()->forget('otp_user_id');

        return redirect()->route($user->isParent() ? 'parent.dashboard' : 'teacher.dashboard')
                         ->with('success', 'Mot de passe réinitialisé avec succès.');
    }

    // ─── Déconnexion ──────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.login');
    }

    // ─── Helper ───────────────────────────────────────────────────

    private function redirectAfterLogin(User $user)
    {
        if ($user->role === 'teacher') {
            return redirect('/professeur/tableau-de-bord');
        }
        if ($user->role === 'parent') {
            return redirect('/parent/tableau-de-bord');
        }
        return redirect('/admin/tableau-de-bord');
    }
}