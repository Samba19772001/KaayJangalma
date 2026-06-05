@extends('layouts.app')
@section('title', 'Abonnement Premium')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 px-0 d-none d-md-block">
            <div class="sidebar">
                <a href="{{ route('home') }}" class="brand">
                    <i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma
                </a>
                <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
                <a href="{{ route('teacher.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
                <a href="{{ route('teacher.requests') }}"><i class="bi bi-inbox"></i> Demandes</a>
                <a href="{{ route('teacher.stats') }}"><i class="bi bi-bar-chart"></i> Statistiques</a>
                <a href="{{ route('teacher.subscription') }}" class="active"><i class="bi bi-star"></i> Abonnement Premium</a>
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
            <h4 class="fw-bold mb-1">Abonnement Premium</h4>
            <p class="text-muted mb-4">Boostez votre visibilité et attirez plus d'élèves</p>

            @if($active)
                <div class="card p-4 mb-4 border-0"
                     style="background:linear-gradient(135deg,#f5c518,#e6a800);border-radius:16px">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-star-fill fs-1 text-white"></i>
                        <div class="text-white">
                            <h5 class="fw-bold mb-1">Abonnement Premium actif ✓</h5>
                            <p class="mb-0 opacity-90">
                                Expire le {{ $active->ends_at->format('d/m/Y') }}
                                ({{ $active->ends_at->diffForHumans() }})
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Offres --}}
            <div class="row g-4 mb-4" style="max-width:700px">
                <div class="col-md-6">
                    <div class="card p-4 h-100 text-center">
                        <div class="mb-3">
                            <span class="badge bg-light text-dark border px-3 py-2">Trimestriel</span>
                        </div>
                        <div class="fs-2 fw-bold mb-1" style="color:var(--kj-green)">
                            15 000 <span class="fs-6 fw-normal text-muted">FCFA</span>
                        </div>
                        <div class="text-muted small mb-3">pour 3 mois</div>
                        <ul class="list-unstyled text-start small mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Profil prioritaire</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Badge Premium</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Mise en avant accueil</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Statistiques avancées</li>
                        </ul>
                        @if(!$active)
                            <form action="{{ route('teacher.subscription.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan" value="quarterly">
                                <button type="submit" class="btn btn-kj w-100 fw-semibold">
                                    Souscrire
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card p-4 h-100 text-center border-2"
                         style="border-color:var(--kj-yellow) !important">
                        <div class="mb-3">
                            <span class="badge px-3 py-2" style="background:var(--kj-yellow);color:#333">
                                ⭐ Meilleure offre
                            </span>
                        </div>
                        <div class="fs-2 fw-bold mb-1" style="color:var(--kj-green)">
                            25 000 <span class="fs-6 fw-normal text-muted">FCFA</span>
                        </div>
                        <div class="text-muted small mb-3">pour 6 mois</div>
                        <ul class="list-unstyled text-start small mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Profil prioritaire</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Badge Premium</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Mise en avant accueil</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Statistiques avancées</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Économisez 5 000 FCFA</li>
                        </ul>
                        @if(!$active)
                            <form action="{{ route('teacher.subscription.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan" value="biannual">
                                <button type="submit" class="btn btn-warning w-100 fw-semibold text-dark">
                                    Souscrire
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Historique --}}
            @if($history->count())
                <div class="card">
                    <div class="card-header bg-white fw-bold py-3">
                        <i class="bi bi-clock-history me-2" style="color:var(--kj-green)"></i>
                        Historique des abonnements
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Plan</th>
                                    <th>Montant</th>
                                    <th>Début</th>
                                    <th>Fin</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $sub)
                                <tr>
                                    <td>{{ $sub->plan === 'quarterly' ? 'Trimestriel' : 'Semestriel' }}</td>
                                    <td>{{ number_format($sub->amount, 0, ',', ' ') }} FCFA</td>
                                    <td class="small">{{ $sub->starts_at->format('d/m/Y') }}</td>
                                    <td class="small">{{ $sub->ends_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge {{
                                            $sub->status === 'active'   ? 'bg-success'   :
                                            ($sub->status === 'expired' ? 'bg-secondary'  : 'bg-danger')
                                        }}">
                                            {{ ['active'=>'Actif','expired'=>'Expiré','cancelled'=>'Annulé'][$sub->status] ?? $sub->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection