@extends('layouts.app')
@section('title', 'Inscription')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <i class="bi bi-mortarboard-fill fs-1" style="color: var(--kj-green)"></i>
                        <h3 class="fw-bold mt-2 mb-0">Créer un compte</h3>
                        <p class="text-muted small">Rejoignez KaayJangalma gratuitement</p>
                    </div>

                    <form action="{{ route('auth.register') }}" method="POST">
                        @csrf

                        {{-- Choix du rôle --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Je suis :</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="role"
                                           id="role-parent" value="parent"
                                           {{ old('role', 'parent') === 'parent' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary w-100 py-3" for="role-parent">
                                        <i class="bi bi-people-fill d-block fs-2 mb-1"></i>
                                        <span class="fw-semibold">Parent</span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="role"
                                           id="role-teacher" value="teacher"
                                           {{ old('role') === 'teacher' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary w-100 py-3" for="role-teacher">
                                        <i class="bi bi-person-workspace d-block fs-2 mb-1"></i>
                                        <span class="fw-semibold">Professeur</span>
                                    </label>
                                </div>
                            </div>
                            @error('role')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="Ex : Ibrahima Diallo" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input type="tel" name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}"
                                       placeholder="77 000 00 00" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Email <span class="text-muted small">(optionnel)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       placeholder="exemple@mail.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Minimum 8 caractères" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password_confirmation"
                                       class="form-control"
                                       placeholder="Répétez le mot de passe" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-kj w-100 py-2 fw-semibold">
                            Créer mon compte <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center text-muted mb-0 small">
                        Déjà inscrit ?
                        <a href="{{ route('auth.login') }}" style="color: var(--kj-green)" class="fw-semibold">
                            Se connecter
                        </a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection