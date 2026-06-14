@extends('layouts.app')
@section('title', 'Statistiques')

@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand"><i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma</a>
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
    <a href="{{ route('teacher.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
    <a href="{{ route('teacher.requests') }}"><i class="bi bi-inbox"></i> Demandes</a>
    <a href="{{ route('teacher.announcements') }}"><i class="bi bi-megaphone"></i> Annonces</a>
    <a href="{{ route('teacher.stats') }}" class="active"><i class="bi bi-bar-chart"></i> Statistiques</a>
    <a href="{{ route('teacher.subscription') }}"><i class="bi bi-star"></i> Abonnement Premium</a>
    <hr class="sidebar-divider">
    <form action="{{ route('auth.logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn p-0 w-100 text-start" style="color:rgba(255,255,255,.8)">
            <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
        </button>
    </form>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 px-0 d-none d-md-block">
            <div class="sidebar">
                <a href="{{ route('home') }}" class="brand"><i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma</a>
                <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
                <a href="{{ route('teacher.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
                <a href="{{ route('teacher.requests') }}"><i class="bi bi-inbox"></i> Demandes</a>
                <a href="{{ route('teacher.announcements') }}"><i class="bi bi-megaphone"></i> Annonces</a>
                <a href="{{ route('teacher.stats') }}" class="active"><i class="bi bi-bar-chart"></i> Statistiques</a>
                <a href="{{ route('teacher.subscription') }}"><i class="bi bi-star"></i> Abonnement Premium</a>
                <hr class="sidebar-divider">
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn p-0 w-100 text-start" style="color:rgba(255,255,255,.8)">
                        <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-10 p-4">
            <h4 class="fw-bold mb-4">
                <i class="bi bi-bar-chart me-2" style="color:var(--kj-green)"></i>
                Mes statistiques
            </h4>

            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4">
                    <div class="stat-card" style="background:var(--kj-green)">
                        <div class="stat-number">{{ $stats['profile_views'] }}</div>
                        <div class="stat-label"><i class="bi bi-eye me-1"></i>Vues du profil</div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stat-card" style="background:#25D366">
                        <div class="stat-number">{{ $stats['whatsapp_clicks'] }}</div>
                        <div class="stat-label"><i class="bi bi-whatsapp me-1"></i>Clics WhatsApp</div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stat-card" style="background:#2980b9">
                        <div class="stat-number">{{ $stats['total_requests'] }}</div>
                        <div class="stat-label"><i class="bi bi-inbox me-1"></i>Total demandes</div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stat-card" style="background:#27ae60">
                        <div class="stat-number">{{ $stats['accepted'] }}</div>
                        <div class="stat-label"><i class="bi bi-check-circle me-1"></i>Acceptées</div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stat-card" style="background:#7f8c8d">
                        <div class="stat-number">{{ $stats['completed'] }}</div>
                        <div class="stat-label"><i class="bi bi-check2-all me-1"></i>Terminées</div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stat-card" style="background:#e67e22">
                        <div class="stat-number">{{ number_format($stats['average_rating'], 1) }}</div>
                        <div class="stat-label"><i class="bi bi-star me-1"></i>Note moyenne</div>
                    </div>
                </div>
            </div>

            <div class="card p-4" style="max-width:500px">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-star me-2" style="color:var(--kj-green)"></i>
                    Répartition des avis ({{ $stats['total_reviews'] }} au total)
                </h6>
                @for($i = 5; $i >= 1; $i--)
                    @php
                        $count = $reviewsByRating[$i] ?? 0;
                        $percent = $stats['total_reviews'] > 0
                            ? round(($count / $stats['total_reviews']) * 100) : 0;
                    @endphp
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="stars small" style="width:80px">
                            @for($s = 1; $s <= $i; $s++)
                                <i class="bi bi-star-fill"></i>
                            @endfor
                        </span>
                        <div class="progress flex-grow-1" style="height:10px">
                            <div class="progress-bar" style="width:{{ $percent }}%;background:var(--kj-green)"></div>
                        </div>
                        <span class="small text-muted" style="width:40px">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection