@extends('layouts.app')
@section('title', 'Connexion')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <i class="bi bi-mortarboard-fill fs-1" style="color: var(--kj-green)"></i>
                        <h3 class="fw-bold mt-2 mb-0">Connexion</h3>
                        <p class="text-muted small">Accédez à votre espace</p>
                    </div>

                    <form action="{{ route('auth.login') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email ou téléphone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="login"
                                       class="form-control @error('login') is-invalid @enderror"
                                       value="{{ old('login') }}"
                                       placeholder="Email ou numéro de téléphone" required>
                                @error('login')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label mb-0">Mot de passe</label>
                                <a href="{{ route('auth.forgot') }}" class="small"
                                   style="color: var(--kj-green)">
                                    Mot de passe oublié ?
                                </a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password"
                                       class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input"
                                   name="remember" id="remember">
                            <label class="form-check-label text-muted small" for="remember">
                                Se souvenir de moi
                            </label>
                        </div>

                        <button type="submit" class="btn btn-kj w-100 py-2 fw-semibold">
                            Se connecter <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center text-muted mb-0 small">
                        Pas encore de compte ?
                        <a href="{{ route('auth.register') }}" style="color: var(--kj-green)" class="fw-semibold">
                            S'inscrire gratuitement
                        </a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection