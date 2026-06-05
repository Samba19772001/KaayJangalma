@extends('layouts.app')
@section('title', 'Mon profil')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 px-0 d-none d-md-block">
            <div class="sidebar">
                <a href="{{ route('home') }}" class="brand">
                    <i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma
                </a>
                <a href="{{ route('parent.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Tableau de bord
                </a>
                <a href="{{ route('parent.profile') }}" class="active">
                    <i class="bi bi-person-circle"></i> Mon profil
                </a>
                <a href="{{ route('search.index') }}">
                    <i class="bi bi-search"></i> Rechercher
                </a>
                <a href="{{ route('parent.favorites') }}">
                    <i class="bi bi-heart"></i> Favoris
                </a>
                <a href="{{ route('parent.requests') }}">
                    <i class="bi bi-send"></i> Mes demandes
                </a>
                <a href="{{ route('parent.announcements') }}">
                    <i class="bi bi-megaphone"></i> Mes annonces
                </a>
                <hr class="sidebar-divider">
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn p-0 w-100 text-start"
                            style="color:rgba(255,255,255,.8);padding:.65rem 1rem !important">
                        <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-10 p-4">
            <h4 class="fw-bold mb-4">Mon profil</h4>

            <div class="card p-4" style="max-width:600px">
                <form action="{{ route('parent.profile.update') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="text-center mb-4">
                        @if($parent->photo)
                            <img src="{{ asset('storage/'.$parent->photo) }}"
                                 class="rounded-circle mb-2"
                                 style="width:100px;height:100px;object-fit:cover;
                                        border:3px solid var(--kj-green)">
                        @else
                            <div class="rounded-circle mx-auto mb-2 d-flex align-items-center
                                        justify-content-center text-white fw-bold"
                                 style="width:100px;height:100px;background:var(--kj-green);font-size:2.5rem">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <label class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-camera me-1"></i> Changer la photo
                                <input type="file" name="photo" class="d-none" accept="image/*">
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nom complet</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ Auth::user()->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Téléphone</label>
                        <input type="text" class="form-control"
                               value="{{ Auth::user()->phone }}" disabled>
                        <div class="form-text">Le téléphone ne peut pas être modifié.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control"
                               value="{{ Auth::user()->email ?? '—' }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ville</label>
                        <input type="text" name="city" class="form-control"
                               value="{{ $parent->city }}" placeholder="Ex : Dakar">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Quartier</label>
                        <input type="text" name="neighborhood" class="form-control"
                               value="{{ $parent->neighborhood }}" placeholder="Ex : Médina">
                    </div>

                    <button type="submit" class="btn btn-kj fw-semibold px-4">
                        <i class="bi bi-check2 me-1"></i> Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection