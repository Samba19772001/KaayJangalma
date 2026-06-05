@extends('layouts.app')
@section('title', 'Mes favoris')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 px-0 d-none d-md-block">
            <div class="sidebar">
                <a href="{{ route('home') }}" class="brand">
                    <i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma
                </a>
                <a href="{{ route('parent.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
                <a href="{{ route('parent.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
                <a href="{{ route('search.index') }}"><i class="bi bi-search"></i> Rechercher</a>
                <a href="{{ route('parent.favorites') }}" class="active"><i class="bi bi-heart"></i> Favoris</a>
                <a href="{{ route('parent.requests') }}"><i class="bi bi-send"></i> Mes demandes</a>
                <a href="{{ route('parent.announcements') }}"><i class="bi bi-megaphone"></i> Mes annonces</a>
                <hr class="sidebar-divider">
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn p-0 w-100 text-start"
                            style="color:rgba(255,255,255,.8)">
                        <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-10 p-4">
            <h4 class="fw-bold mb-4">
                <i class="bi bi-heart-fill me-2" style="color:var(--kj-green)"></i>
                Mes favoris ({{ $favorites->count() }})
            </h4>

            @if($favorites->isEmpty())
                <div class="card text-center py-5">
                    <i class="bi bi-heart fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">Aucun favori pour l'instant</h5>
                    <p class="text-muted small">
                        Ajoutez des professeurs à vos favoris pour les retrouver facilement.
                    </p>
                    <div>
                        <a href="{{ route('search.index') }}" class="btn btn-kj px-4">
                            <i class="bi bi-search me-2"></i>Rechercher un professeur
                        </a>
                    </div>
                </div>
            @else
                <div class="row g-3">
                    @foreach($favorites as $teacher)
                        <div class="col-md-6 col-lg-4">
                            <div class="card teacher-card h-100 p-3">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if($teacher->photo)
                                        <img src="{{ asset('storage/'.$teacher->photo) }}"
                                             class="teacher-avatar">
                                    @else
                                        <div class="teacher-avatar-placeholder">
                                            {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $teacher->user->name }}</div>
                                        <div class="small text-muted">
                                            {{ $teacher->experience_years }} an(s) d'expérience
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    @foreach($teacher->subjects->take(3) as $subject)
                                        <span class="badge bg-light text-dark border me-1">
                                            {{ $subject->name }}
                                        </span>
                                    @endforeach
                                </div>

                                @if($teacher->zones->first())
                                    <div class="small text-muted mb-2">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        {{ $teacher->zones->first()->city }}
                                    </div>
                                @endif

                                <div class="stars small mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= round($teacher->average_rating) ? '-fill' : '' }}"></i>
                                    @endfor
                                    <span class="text-muted">({{ $teacher->total_reviews }})</span>
                                </div>

                                <div class="d-flex gap-2 mt-auto">
                                    <a href="{{ route('teacher.public', $teacher->id) }}"
                                       class="btn btn-sm btn-kj flex-fill">
                                        Voir le profil
                                    </a>
                                    <form action="{{ route('parent.favorites.toggle', $teacher->id) }}"
                                          method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-heart-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection