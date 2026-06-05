@extends('layouts.app')
@section('title', 'Mot de passe oublié')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <a href="{{ route('auth.login') }}" class="text-muted small">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>

                    <div class="text-center my-4">
                        <i class="bi bi-shield-lock fs-1" style="color: var(--kj-green)"></i>
                        <h4 class="fw-bold mt-2 mb-0">Mot de passe oublié</h4>
                        <p class="text-muted small mt-1">
                            Entrez votre numéro pour recevoir un code OTP
                        </p>
                    </div>

                    <form action="{{ route('auth.otp.send') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">Numéro de téléphone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input type="tel" name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       placeholder="77 000 00 00"
                                       value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-kj w-100 py-2 fw-semibold">
                            Envoyer le code <i class="bi bi-send ms-1"></i>
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection