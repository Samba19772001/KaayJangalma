<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login');
        }

        $user = Auth::user();

        // Vérifier si l'utilisateur est bloqué
        if ($user->is_blocked) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('auth.login')
                             ->withErrors(['login' => 'Votre compte a été suspendu. Raison : ' . ($user->block_reason ?? 'Violation des CGU') . '. Contactez le support.']);
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}