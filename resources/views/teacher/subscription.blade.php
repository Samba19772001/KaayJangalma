@extends('layouts.app')
@section('title', 'Abonnement Premium')

@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand"><i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma</a>
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
    <a href="{{ route('teacher.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
    <a href="{{ route('teacher.requests') }}"><i class="bi bi-inbox"></i> Demandes</a>
    <a href="{{ route('messages.index') }}"><i class="bi bi-chat"></i> Messages</a>
    <a href="{{ route('teacher.announcements') }}"><i class="bi bi-megaphone"></i> Annonces</a>
    <a href="{{ route('teacher.stats') }}"><i class="bi bi-bar-chart"></i> Statistiques</a>
    <a href="{{ route('teacher.subscription') }}" class="active"><i class="bi bi-star"></i> Abonnement Premium</a>
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
                <a href="{{ route('messages.index') }}"><i class="bi bi-chat"></i> Messages</a>
                <a href="{{ route('teacher.announcements') }}"><i class="bi bi-megaphone"></i> Annonces</a>
                <a href="{{ route('teacher.stats') }}"><i class="bi bi-bar-chart"></i> Statistiques</a>
                <a href="{{ route('teacher.subscription') }}" class="active"><i class="bi bi-star"></i> Abonnement Premium</a>
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
            <h4 class="fw-bold mb-1">Abonnement Premium</h4>
            <p class="text-muted mb-4">Boostez votre visibilité et attirez plus d'élèves</p>

            {{-- Abonnement actif --}}
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

            {{-- En attente de confirmation --}}
            @php
                $pending = $teacher->subscriptions()->where('status', 'pending_payment')->first();
            @endphp
            @if($pending)
                <div class="alert alert-warning d-flex align-items-center gap-3 mb-4">
                    <i class="bi bi-clock-history fs-3"></i>
                    <div>
                        <div class="fw-bold">Paiement en attente de confirmation</div>
                        <div class="small">
                            Plan {{ ['quarterly'=>'Trimestriel','biannual'=>'Semestriel','annual'=>'Annuel'][$pending->plan] }}
                            — {{ number_format($pending->amount, 0, ',', ' ') }} FCFA
                            — Référence : <strong>{{ $pending->payment_reference }}</strong>
                        </div>
                        <div class="small text-muted">L'admin confirmera votre paiement sous 24h.</div>
                    </div>
                </div>
            @endif

             {{-- Coordonnées bancaires --}}
            <div class="card p-4 mb-4" style="border-left: 4px solid var(--kj-green)">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-bank me-2" style="color:var(--kj-green)"></i>
                    Coordonnées de paiement
                </h6>
                <div class="row g-3 small">
                    <div class="col-md-4">
                        <div class="p-3 rounded" style="background:#f8f9fa">
                            <div class="fw-bold mb-1">
                                <i class="bi bi-phone me-1" style="color:#1DA462"></i>Wave
                            </div>
                            <div class="text-muted">Numéro : <strong>78 292 10 01</strong></div>
                            <div class="text-muted">Nom : <strong>KaayJangalma</strong></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded" style="background:#f8f9fa">
                            <div class="fw-bold mb-1">
                                <i class="bi bi-phone me-1" style="color:#FF6600"></i>Orange Money
                            </div>
                            <div class="text-muted">Numéro : <strong>78 292 10 01</strong></div>
                            <div class="text-muted">Nom : <strong>KaayJangalma</strong></div>
                        </div>
                    </div>
                    
                </div>
                <div class="alert alert-info small mt-3 mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Après avoir effectué le virement, souscrivez ci-dessous en indiquant
                    votre référence de paiement. L'admin activera votre abonnement sous 24h.
                </div>
            </div>

            {{-- Plans --}}
            @if(!$active && !$pending)
                <div class="row g-4 mb-4">

                    {{-- Trimestriel --}}
                    <div class="col-md-4">
                        <div class="card p-4 h-100 text-center">
                            <div class="mb-3">
                                <span class="badge bg-light text-dark border px-3 py-2">Offre Trimestriel</span>
                            </div>
                            <div class="fs-2 fw-bold mb-1" style="color:var(--kj-green)">
                                5 900 <span class="fs-6 fw-normal text-muted">FCFA</span>
                            </div>
                            <div class="text-muted small mb-3">pour 3 mois</div>
                            <ul class="list-unstyled text-start small mb-4">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Profil prioritaire</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Badge Premium</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Mise en avant accueil</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Statistiques avancées</li>
                            </ul>
                            <button class="btn btn-kj w-100 fw-semibold"
                                    onclick="openPayModal('quarterly', '5 900 FCFA', '3 mois')">
                                Souscrire
                            </button>
                        </div>
                    </div>

                    {{-- Semestriel --}}
                    <div class="col-md-4">
                        <div class="card p-4 h-100 text-center border-2"
                             style="border-color:var(--kj-yellow) !important">
                            <div class="mb-3">
                                <span class="badge px-3 py-2" style="background:var(--kj-yellow);color:#333">
                                    Offre Semestrielle
                                </span>
                            </div>
                            <div class="fs-2 fw-bold mb-1" style="color:var(--kj-green)">
                                9 900 <span class="fs-6 fw-normal text-muted">FCFA</span>
                            </div>
                            <div class="text-muted small mb-3">pour 6 mois</div>
                            <ul class="list-unstyled text-start small mb-4">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Profil prioritaire</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Badge Premium</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Mise en avant accueil</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Statistiques avancées</li>
                                
                            </ul>
                            <button class="btn btn-warning w-100 fw-semibold text-dark"
                                    onclick="openPayModal('biannual', '9 900 FCFA', '6 mois')">
                                Souscrire
                            </button>
                        </div>
                    </div>

                    {{-- Annuel --}}
                    <div class="col-md-4">
                        <div class="card p-4 h-100 text-center border-2"
                             style="border-color:var(--kj-green) !important;
                                    background:linear-gradient(135deg,rgba(27,122,74,.05),rgba(27,122,74,.1))">
                            <div class="mb-3">
                                <span class="badge px-3 py-2" style="background:var(--kj-green);color:#fff">
                                    Offre Annuelle
                                </span>
                            </div>
                            <div class="fs-2 fw-bold mb-1" style="color:var(--kj-green)">
                                14 900 <span class="fs-6 fw-normal text-muted">FCFA</span>
                            </div>
                            <div class="text-muted small mb-3">pour 12 mois</div>
                            <ul class="list-unstyled text-start small mb-4">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Profil prioritaire</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Badge Premium</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Mise en avant accueil</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Statistiques avancées</li>
                               
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Support prioritaire</li>
                            </ul>
                            <button class="btn w-100 fw-semibold text-white"
                                    style="background:var(--kj-green)"
                                    onclick="openPayModal('annual', '14 900 FCFA', '12 mois')">
                                Souscrire
                            </button>
                        </div>
                    </div>
                </div>
            @endif

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
                                    <th>Méthode</th>
                                    <th>Référence</th>
                                    <th>Début</th>
                                    <th>Fin</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $sub)
                                <tr>
                                    <td>{{ ['quarterly'=>'Trimestriel','biannual'=>'Semestriel','annual'=>'Annuel'][$sub->plan] ?? $sub->plan }}</td>
                                    <td>{{ number_format($sub->amount, 0, ',', ' ') }} FCFA</td>
                                    <td class="small">{{ ['wave'=>'Wave','orange_money'=>'Orange Money','virement'=>'Virement'][$sub->payment_method] ?? '—' }}</td>
                                    <td class="small">{{ $sub->payment_reference ?? '—' }}</td>
                                    <td class="small">{{ $sub->starts_at->format('d/m/Y') }}</td>
                                    <td class="small">{{ $sub->ends_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge {{
                                            $sub->status === 'active'          ? 'bg-success' :
                                            ($sub->status === 'pending_payment' ? 'bg-warning text-dark' :
                                            ($sub->status === 'expired'         ? 'bg-secondary' : 'bg-danger'))
                                        }}">
                                            {{ ['active'=>'Actif','pending_payment'=>'En attente','expired'=>'Expiré','cancelled'=>'Annulé'][$sub->status] ?? $sub->status }}
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

{{-- Modal paiement --}}
<div class="modal fade" id="payModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-credit-card me-2"></i>Confirmer le paiement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('teacher.subscription.store') }}" method="POST">
                @csrf
                <input type="hidden" name="plan" id="modalPlan">
                <div class="modal-body">

                    <div class="alert alert-light border mb-3" id="modalSummary"></div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mode de paiement</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">Choisir</option>
                            <option value="wave">Wave</option>
                            <option value="orange_money">Orange Money</option>
                            <option value="virement">Virement bancaire</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Référence du paiement</label>
                        <input type="text" name="payment_reference" class="form-control"
                               placeholder="Ex : TXN123456789" required>
                        <div class="form-text">
                            Entrez le numéro de transaction ou la référence de votre virement.
                        </div>
                    </div>

                    <div class="alert alert-warning small">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Assurez-vous d'avoir effectué le virement avant de soumettre.
                        Votre abonnement sera activé après vérification par l'admin.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-kj fw-semibold">
                        <i class="bi bi-send me-1"></i>Soumettre ma demande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openPayModal(plan, amount, duration) {
    document.getElementById('modalPlan').value = plan;
    document.getElementById('modalSummary').innerHTML =
        '<strong>Plan sélectionné :</strong> ' + duration +
        ' — <strong>' + amount + '</strong>';
    new bootstrap.Modal(document.getElementById('payModal')).show();
}
</script>
@endpush