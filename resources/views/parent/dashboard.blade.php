@extends('layouts.app')
@section('title', 'Tableau de bord')

@section('content')
<div class="container-fluid">
    <div class="row">

        {{-- ── Sidebar ── --}}
        <div class="col-md-2 px-0 d-none d-md-block">
            <div class="sidebar">
                <a href="{{ route('home') }}" class="brand">
                    <i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma
                </a>
                <a href="{{ route('parent.dashboard') }}" class="active">
                    <i class="bi bi-speedometer2"></i> Tableau de bord
                </a>
                <a href="{{ route('parent.profile') }}">
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
                    <button type="submit"
                            class="btn p-0 w-100 text-start"
                            style="color:rgba(255,255,255,.8);padding:.65rem 1rem !important">
                        <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        {{-- ── Contenu ── --}}
        <div class="col-md-10 p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-0">Bonjour, {{ Auth::user()->name }} 👋</h4>
                    <p class="text-muted small mb-0">Bienvenue sur votre espace parent</p>
                </div>
                <a href="{{ route('search.index') }}" class="btn btn-kj">
                    <i class="bi bi-search me-1"></i> Trouver un professeur
                </a>
            </div>

            {{-- ── Stats ── --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card" style="background: var(--kj-green)">
                        <div class="stat-number">{{ $stats['requests'] }}</div>
                        <div class="stat-label">
                            <i class="bi bi-send me-1"></i> Demandes envoyées
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card" style="background: #2980b9">
                        <div class="stat-number">{{ $stats['favorites'] }}</div>
                        <div class="stat-label">
                            <i class="bi bi-heart me-1"></i> Professeurs favoris
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card" style="background: #8e44ad">
                        <div class="stat-number">{{ $stats['messages'] }}</div>
                        <div class="stat-label">
                            <i class="bi bi-chat me-1"></i> Messages non lus
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Dernières demandes ── --}}
            <div class="card">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-send me-2" style="color: var(--kj-green)"></i>
                        Dernières demandes de cours
                    </h6>
                    <a href="{{ route('parent.requests') }}"
                       class="btn btn-sm btn-outline-secondary">
                        Voir tout
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentRequests->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Aucune demande pour l'instant.
                            <br>
                            <a href="{{ route('search.index') }}"
                               style="color: var(--kj-green)">
                                Trouvez un professeur
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Professeur</th>
                                        <th>Matière</th>
                                        <th>Niveau</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRequests as $req)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($req->teacher->photo)
                                                    <img src="{{ asset('storage/'.$req->teacher->photo) }}"
                                                         class="rounded-circle"
                                                         style="width:36px;height:36px;object-fit:cover">
                                                @else
                                                    <div class="rounded-circle d-flex align-items-center
                                                                justify-content-center text-white fw-bold"
                                                         style="width:36px;height:36px;
                                                                background:var(--kj-green);font-size:.85rem">
                                                        {{ strtoupper(substr($req->teacher->user->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <span class="fw-semibold small">
                                                    {{ $req->teacher->user->name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="small">{{ $req->subject->name }}</td>
                                        <td class="small">
                                            @php
                                                $levels = ['primary'=>'Primaire','middle'=>'Collège','high'=>'Lycée'];
                                            @endphp
                                            {{ $levels[$req->level] ?? $req->level }}
                                        </td>
                                        <td>
                                            @php
                                                $badges = [
                                                    'sent'      => ['bg-info',    'Envoyée'],
                                                    'accepted'  => ['bg-success', 'Acceptée'],
                                                    'refused'   => ['bg-danger',  'Refusée'],
                                                    'completed' => ['bg-secondary','Terminée'],
                                                ];
                                                [$color, $label] = $badges[$req->status] ?? ['bg-secondary','—'];
                                            @endphp
                                            <span class="badge {{ $color }}">{{ $label }}</span>
                                        </td>
                                        <td class="small text-muted">
                                            {{ $req->created_at->format('d/m/Y') }}
                                        </td>
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