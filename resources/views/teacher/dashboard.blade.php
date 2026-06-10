@extends('layouts.app')
@section('title', 'Tableau de bord professeur')

@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand">
        <i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma
    </a>
    <a href="{{ route('teacher.dashboard') }}" class="active">
        <i class="bi bi-speedometer2"></i> Tableau de bord
    </a>
    <a href="{{ route('teacher.profile') }}">
        <i class="bi bi-person-circle"></i> Mon profil
    </a>
    <a href="{{ route('teacher.requests') }}">
        <i class="bi bi-inbox"></i> Demandes
    </a>
    <a href="{{ route('teacher.stats') }}">
        <i class="bi bi-bar-chart"></i> Statistiques
    </a>
    <a href="{{ route('teacher.subscription') }}">
        <i class="bi bi-star"></i> Abonnement Premium
    </a>
    <hr class="sidebar-divider">
    <form action="{{ route('auth.logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn p-0 w-100 text-start"
                style="color:rgba(255,255,255,.8)">
            <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
        </button>
    </form>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 px-0 d-none d-md-block">
            <div class="sidebar">
                <a href="{{ route('home') }}" class="brand">
                    <i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma
                </a>
                <a href="{{ route('teacher.dashboard') }}" class="active">
                    <i class="bi bi-speedometer2"></i> Tableau de bord
                </a>
                <a href="{{ route('teacher.profile') }}">
                    <i class="bi bi-person-circle"></i> Mon profil
                </a>
                <a href="{{ route('teacher.requests') }}">
                    <i class="bi bi-inbox"></i> Demandes
                </a>
                <a href="{{ route('teacher.stats') }}">
                    <i class="bi bi-bar-chart"></i> Statistiques
                </a>
                <a href="{{ route('teacher.subscription') }}">
                    <i class="bi bi-star"></i> Abonnement Premium
                </a>
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-0">Bonjour, {{ Auth::user()->name }}</h4>
                    <p class="text-muted small mb-0">
                        Statut :
                        @if($teacher->verified_status === 'verified')
                            <span class="badge-verified"><i class="bi bi-patch-check-fill me-1"></i>Vérifié</span>
                        @elseif($teacher->verified_status === 'pending')
                            <span class="badge bg-warning text-dark">En attente de validation</span>
                        @else
                            <span class="badge bg-danger">Refusé</span>
                        @endif
                        @if($teacher->is_premium)
                            <span class="badge-premium ms-1"><i class="bi bi-star-fill me-1"></i>Premium</span>
                        @endif
                    </p>
                </div>
                <a href="{{ route('teacher.profile') }}" class="btn btn-kj">
                    <i class="bi bi-pencil me-1"></i> Modifier mon profil
                </a>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-md-2">
                    <div class="stat-card" style="background:var(--kj-green)">
                        <div class="stat-number">{{ $stats['views'] }}</div>
                        <div class="stat-label"><i class="bi bi-eye me-1"></i>Vues</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="stat-card" style="background:#25D366">
                        <div class="stat-number">{{ $stats['whatsapp_clicks'] }}</div>
                        <div class="stat-label"><i class="bi bi-whatsapp me-1"></i>WhatsApp</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="stat-card" style="background:#2980b9">
                        <div class="stat-number">{{ $stats['requests'] }}</div>
                        <div class="stat-label"><i class="bi bi-inbox me-1"></i>Demandes</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="stat-card" style="background:#8e44ad">
                        <div class="stat-number">{{ $stats['messages'] }}</div>
                        <div class="stat-label"><i class="bi bi-chat me-1"></i>Messages</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="stat-card" style="background:#e67e22">
                        <div class="stat-number">{{ number_format($stats['average_rating'], 1) }}</div>
                        <div class="stat-label"><i class="bi bi-star me-1"></i>Note moy.</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="stat-card" style="background:#c0392b">
                        <div class="stat-number">{{ $stats['total_reviews'] }}</div>
                        <div class="stat-label"><i class="bi bi-chat-square-text me-1"></i>Avis</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-inbox me-2" style="color:var(--kj-green)"></i>
                        Dernières demandes reçues
                    </h6>
                    <a href="{{ route('teacher.requests') }}" class="btn btn-sm btn-outline-secondary">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    @if($recentRequests->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Aucune demande reçue pour l'instant.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Parent</th>
                                        <th>Matière</th>
                                        <th>Niveau</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $levels = ['primary'=>'Primaire','middle'=>'Collège','high'=>'Lycée'];
                                        $badges = [
                                            'sent'      => ['bg-info text-dark','Envoyée'],
                                            'accepted'  => ['bg-success','Acceptée'],
                                            'refused'   => ['bg-danger','Refusée'],
                                            'completed' => ['bg-secondary','Terminée'],
                                        ];
                                    @endphp
                                    @foreach($recentRequests as $req)
                                    <tr>
                                        <td class="fw-semibold small">{{ $req->parent->user->name }}</td>
                                        <td class="small">{{ $req->subject->name }}</td>
                                        <td class="small">{{ $levels[$req->level] ?? $req->level }}</td>
                                        <td>
                                            @php [$color, $label] = $badges[$req->status] ?? ['bg-secondary','—']; @endphp
                                            <span class="badge {{ $color }}">{{ $label }}</span>
                                        </td>
                                        <td class="small text-muted">{{ $req->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection