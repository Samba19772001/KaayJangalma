@extends('layouts.app')
@section('title', 'Revenus')

@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand"><i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma</a>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
    <a href="{{ route('admin.users') }}"><i class="bi bi-people"></i> Utilisateurs</a>
    <a href="{{ route('admin.teachers') }}"><i class="bi bi-person-workspace"></i> Professeurs</a>
    <a href="{{ route('admin.subjects') }}"><i class="bi bi-book"></i> Matières</a>
    <a href="{{ route('admin.payments') }}"><i class="bi bi-credit-card"></i> Paiements</a>
    <a href="{{ route('admin.revenue') }}" class="active"><i class="bi bi-graph-up"></i> Revenus</a>
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
                <a href="{{ route('admin.users') }}"><i class="bi bi-people"></i> Utilisateurs</a>
                <a href="{{ route('admin.teachers') }}"><i class="bi bi-person-workspace"></i> Professeurs</a>
                <a href="{{ route('admin.subjects') }}"><i class="bi bi-book"></i> Matières</a>
                <a href="{{ route('admin.payments') }}"><i class="bi bi-credit-card"></i> Paiements</a>
                <a href="{{ route('admin.revenue') }}" class="active"><i class="bi bi-graph-up"></i> Revenus</a>
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
                <i class="bi bi-graph-up me-2" style="color:var(--kj-green)"></i>
                Revenus
            </h4>

            {{-- Stats globales --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card" style="background:var(--kj-green)">
                        <div class="stat-number">{{ number_format($revenueThisMonth, 0, ',', ' ') }}</div>
                        <div class="stat-label"><i class="bi bi-calendar-month me-1"></i>FCFA ce mois</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card" style="background:#2980b9">
                        <div class="stat-number">{{ number_format($revenueThisYear, 0, ',', ' ') }}</div>
                        <div class="stat-label"><i class="bi bi-calendar me-1"></i>FCFA cette année</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card" style="background:#8e44ad">
                        <div class="stat-number">{{ number_format($revenueTotal, 0, ',', ' ') }}</div>
                        <div class="stat-label"><i class="bi bi-currency-exchange me-1"></i>FCFA total</div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">

                {{-- Revenus par période personnalisée --}}
                <div class="col-md-6">
                    <div class="card p-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-calendar-range me-2" style="color:var(--kj-green)"></i>
                            Période personnalisée
                        </h6>
                        <form action="{{ route('admin.revenue') }}" method="GET" class="mb-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label small fw-semibold">Du</label>
                                    <input type="date" name="start_date" class="form-control form-control-sm"
                                           value="{{ $startDate }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-semibold">Au</label>
                                    <input type="date" name="end_date" class="form-control form-control-sm"
                                           value="{{ $endDate }}">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-kj btn-sm w-100">
                                        <i class="bi bi-search me-1"></i>Calculer
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="text-center p-3 rounded" style="background:rgba(27,122,74,.1)">
                            <div class="fs-2 fw-bold" style="color:var(--kj-green)">
                                {{ number_format($revenueCustom, 0, ',', ' ') }} FCFA
                            </div>
                            <div class="text-muted small">
                                {{ $countCustom }} paiement(s) du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                                au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Revenus par plan --}}
                <div class="col-md-6">
                    <div class="card p-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-pie-chart me-2" style="color:var(--kj-green)"></i>
                            Revenus par plan
                        </h6>
                        @php
                            $planLabels = ['quarterly'=>'Trimestriel','biannual'=>'Semestriel','annual'=>'Annuel'];
                            $planColors = ['quarterly'=>'var(--kj-green)','biannual'=>'#e67e22','annual'=>'#8e44ad'];
                        @endphp
                        @forelse($revenueByPlan as $plan)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded" style="width:12px;height:12px;background:{{ $planColors[$plan->plan] ?? '#ccc' }}"></div>
                                    <span class="small fw-semibold">{{ $planLabels[$plan->plan] ?? $plan->plan }}</span>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold small" style="color:var(--kj-green)">
                                        {{ number_format($plan->total, 0, ',', ' ') }} FCFA
                                    </div>
                                    <div class="text-muted" style="font-size:.75rem">
                                        {{ $plan->count }} abonnement(s)
                                    </div>
                                </div>
                            </div>
                            <div class="progress mb-3" style="height:6px">
                                @php $percent = $revenueTotal > 0 ? round(($plan->total / $revenueTotal) * 100) : 0; @endphp
                                <div class="progress-bar" style="width:{{ $percent }}%;background:{{ $planColors[$plan->plan] ?? '#ccc' }}"></div>
                            </div>
                        @empty
                            <p class="text-muted small text-center py-3">Aucun revenu pour l'instant.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Revenus par mois --}}
            <div class="card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-bar-chart me-2" style="color:var(--kj-green)"></i>
                        Revenus mensuels
                    </h6>
                    <form action="{{ route('admin.revenue') }}" method="GET" class="d-flex gap-2">
                        <select name="year" class="form-select form-select-sm" style="width:auto">
                            @for($y = now()->year; $y >= now()->year - 3; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-kj btn-sm">Afficher</button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mois</th>
                                <th>Nombre d'abonnements</th>
                                <th>Revenus</th>
                                <th>Progression</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maxRevenue = $revenueByMonth->max('total') ?: 1; @endphp
                            @foreach($months as $num => $name)
                                @php $data = $revenueByMonth[$num] ?? null; @endphp
                                <tr>
                                    <td class="fw-semibold small">{{ $name }} {{ $year }}</td>
                                    <td class="small">{{ $data?->count ?? 0 }}</td>
                                    <td class="small fw-bold {{ $data ? '' : 'text-muted' }}" style="color:var(--kj-green)">
                                        {{ $data ? number_format($data->total, 0, ',', ' ').' FCFA' : '—' }}
                                    </td>
                                    <td style="width:30%">
                                        @if($data)
                                            <div class="progress" style="height:8px">
                                                <div class="progress-bar"
                                                     style="width:{{ round(($data->total / $maxRevenue) * 100) }}%;
                                                            background:var(--kj-green)"></div>
                                            </div>
                                        @else
                                            <div class="progress" style="height:8px">
                                                <div class="progress-bar" style="width:0%"></div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td class="fw-bold">Total {{ $year }}</td>
                                <td class="fw-bold">{{ $revenueByMonth->sum('count') }}</td>
                                <td class="fw-bold" style="color:var(--kj-green)">
                                    {{ number_format($revenueByMonth->sum('total'), 0, ',', ' ') }} FCFA
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Derniers paiements --}}
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-clock-history me-2" style="color:var(--kj-green)"></i>
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
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lastPayments as $sub)
                            <tr>
                                <td class="fw-semibold small">{{ $sub->teacher->user->name }}</td>
                                <td class="small">
                                    {{ ['quarterly'=>'Trimestriel','biannual'=>'Semestriel','annual'=>'Annuel'][$sub->plan] ?? $sub->plan }}
                                </td>
                                <td class="fw-bold small" style="color:var(--kj-green)">
                                    {{ number_format($sub->amount, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="small">
                                    {{ ['wave'=>'Wave','orange_money'=>'Orange Money','virement'=>'Virement','paytech'=>'PayTech'][$sub->payment_method] ?? '—' }}
                                </td>
                                <td class="small text-muted">
                                    {{ $sub->payment_confirmed_at?->format('d/m/Y H:i') ?? '—' }}
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
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