@extends('layouts.app')
@section('title', 'Gestion des paiements')

@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand"><i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma</a>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
    <a href="{{ route('admin.teachers') }}"><i class="bi bi-people"></i> Professeurs</a>
    <a href="{{ route('admin.subjects') }}"><i class="bi bi-book"></i> Matières</a>
    <a href="{{ route('admin.payments') }}" class="active"><i class="bi bi-credit-card"></i> Paiements</a>
    <a href="{{ route('admin.reviews') }}"><i class="bi bi-star"></i> Avis</a>
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
                <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
                <a href="{{ route('admin.teachers') }}"><i class="bi bi-people"></i> Professeurs</a>
                <a href="{{ route('admin.subjects') }}"><i class="bi bi-book"></i> Matières</a>
                <a href="{{ route('admin.payments') }}" class="active"><i class="bi bi-credit-card"></i> Paiements</a>
                <a href="{{ route('admin.reviews') }}"><i class="bi bi-star"></i> Avis</a>
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
                <i class="bi bi-credit-card me-2" style="color:var(--kj-green)"></i>
                Gestion des paiements
            </h4>

            {{-- Paiements en attente --}}
            <div class="card mb-4">
                <div class="card-header bg-white py-3 d-flex align-items-center gap-2">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-clock text-warning me-2"></i>
                        Paiements en attente de confirmation
                    </h6>
                    @if($pending->count() > 0)
                        <span class="badge bg-warning text-dark">{{ $pending->count() }}</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if($pending->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-check-circle fs-2 text-success d-block mb-2"></i>
                            Aucun paiement en attente.
                        </div>
                    @else
                        @foreach($pending as $sub)
                            <div class="p-4 border-bottom">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center
                                                        text-white fw-bold flex-shrink-0"
                                                 style="width:45px;height:45px;background:var(--kj-green)">
                                                {{ strtoupper(substr($sub->teacher->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $sub->teacher->user->name }}</div>
                                                <div class="small text-muted">{{ $sub->teacher->user->phone }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="small">
                                            <div class="mb-1">
                                                <span class="text-muted">Plan :</span>
                                                <strong class="ms-1">
                                                    {{ ['quarterly'=>'Trimestriel','biannual'=>'Semestriel','annual'=>'Annuel'][$sub->plan] }}
                                                </strong>
                                            </div>
                                            <div class="mb-1">
                                                <span class="text-muted">Montant :</span>
                                                <strong class="ms-1">{{ number_format($sub->amount, 0, ',', ' ') }} FCFA</strong>
                                            </div>
                                            <div class="mb-1">
                                                <span class="text-muted">Méthode :</span>
                                                <strong class="ms-1">
                                                    {{ ['wave'=>'Wave','orange_money'=>'Orange Money','virement'=>'Virement'][$sub->payment_method] ?? '—' }}
                                                </strong>
                                            </div>
                                            <div>
                                                <span class="text-muted">Référence :</span>
                                                <strong class="ms-1 text-primary">{{ $sub->payment_reference }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="small text-muted mb-2">
                                            Soumis {{ $sub->created_at->diffForHumans() }}
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-success fw-semibold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#confirmModal{{ $sub->id }}">
                                                <i class="bi bi-check-lg me-1"></i>Confirmer
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal{{ $sub->id }}">
                                                <i class="bi bi-x-lg me-1"></i>Rejeter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal confirmation --}}
                            <div class="modal fade" id="confirmModal{{ $sub->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold text-success">
                                                <i class="bi bi-check-circle me-2"></i>Confirmer le paiement
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.payments.confirm', $sub->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="confirm">
                                            <div class="modal-body">
                                                <div class="alert alert-light border mb-3 small">
                                                    <strong>{{ $sub->teacher->user->name }}</strong> —
                                                    {{ ['quarterly'=>'Trimestriel','biannual'=>'Semestriel','annual'=>'Annuel'][$sub->plan] }} —
                                                    {{ number_format($sub->amount, 0, ',', ' ') }} FCFA<br>
                                                    Référence : <strong>{{ $sub->payment_reference }}</strong>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Note (optionnelle)</label>
                                                    <textarea name="payment_note" class="form-control" rows="2"
                                                              placeholder="Ex : Paiement reçu et vérifié"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-success fw-semibold">
                                                    <i class="bi bi-check-lg me-1"></i>Confirmer et activer
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal rejet --}}
                            <div class="modal fade" id="rejectModal{{ $sub->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold text-danger">
                                                <i class="bi bi-x-circle me-2"></i>Rejeter le paiement
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.payments.confirm', $sub->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="reject">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Raison du rejet</label>
                                                    <textarea name="payment_note" class="form-control" rows="3"
                                                              placeholder="Ex : Référence introuvable, montant incorrect..."
                                                              required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-danger fw-semibold">
                                                    <i class="bi bi-x-lg me-1"></i>Rejeter
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Paiements confirmés --}}
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Derniers paiements confirmés
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Professeur</th>
                                <th>Plan</th>
                                <th>Montant</th>
                                <th>Méthode</th>
                                <th>Référence</th>
                                <th>Confirmé le</th>
                                <th>Expire le</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($confirmed as $sub)
                            <tr>
                                <td class="fw-semibold small">{{ $sub->teacher->user->name }}</td>
                                <td class="small">
                                    {{ ['quarterly'=>'Trimestriel','biannual'=>'Semestriel','annual'=>'Annuel'][$sub->plan] ?? $sub->plan }}
                                </td>
                                <td class="small fw-bold" style="color:var(--kj-green)">
                                    {{ number_format($sub->amount, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="small">
                                    {{ ['wave'=>'Wave','orange_money'=>'Orange Money','virement'=>'Virement'][$sub->payment_method] ?? '—' }}
                                </td>
                                <td class="small text-primary">{{ $sub->payment_reference ?? '—' }}</td>
                                <td class="small text-muted">
                                    {{ $sub->payment_confirmed_at?->format('d/m/Y') ?? '—' }}
                                </td>
                                <td class="small">{{ $sub->ends_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        Aucun paiement confirmé.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection