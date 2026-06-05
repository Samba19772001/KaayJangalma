@extends('layouts.app')
@section('title', 'Vérification OTP')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <i class="bi bi-phone-vibrate fs-1" style="color: var(--kj-green)"></i>
                        <h4 class="fw-bold mt-2 mb-0">Vérification</h4>
                        <p class="text-muted small mt-1">
                            Entrez le code reçu par SMS
                        </p>
                    </div>

                    <form action="{{ route('auth.otp.verify') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Code OTP</label>
                            <input type="text" name="otp" maxlength="6"
                                   class="form-control form-control-lg text-center fw-bold
                                          @error('otp') is-invalid @enderror"
                                   placeholder="· · · · · ·"
                                   style="letter-spacing: .5rem; font-size: 1.5rem;"
                                   required>
                            @error('otp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nouveau mot de passe</label>
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
                            <label class="form-label">Confirmer le mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password_confirmation"
                                       class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-kj w-100 py-2 fw-semibold">
                            Réinitialiser le mot de passe
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection