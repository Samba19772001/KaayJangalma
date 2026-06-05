@extends('layouts.app')
@section('title', 'Administration')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 px-0 d-none d-md-block">
            <div class="sidebar">
                <a href="{{ route('home') }}" class="brand">
                    <i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma
                </a>
                <a href="{{ route('admin.dashboard') }}" class="active">
                    <i class="bi bi-speedometer2"></i> Tableau de bord
                </a>
                <a href="{{ route('admin.teachers') }}">
                    <i class="bi bi-people"></i> Professeurs
                </a>
                <a href="{{ route('admin.subjects') }}">
                    <i class="bi bi-book"></i> Matières
                </a>
                <a href="{{ route('admin.reviews') }}">
                    <i class="bi bi-star"></i> Avis
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
            <h4 class="fw-bold mb-4">
                <i class="bi bi-shield-check me-2" style="color:var(--kj-green)"></i>
                Tableau de bord Administration
            </h4>

            {{-- Stats --}}
            <div class="row g-3 mb-4">
                <div class="col-md-2 col-6">
                    <div class="stat-card" style="background:var(--kj-green)">
                        <div class="stat-number">{{ $stats['parents'] }}</div>
                        <div class="stat-label"><i class="bi bi-people me-1"></i>Parents</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-card" style="background:#2980b9">
                        <div class="stat-number">{{ $stats['teachers'] }}</div>
                        <div class="stat-label"><i class="bi bi-person-workspace me-1"></i>Professeurs</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-card" style="background:#e67e22">
                        <div class="stat-number">{{ $stats['premium'] }}</div>
                        <div class="stat-label"><i class="bi bi-star me-1"></i>Premium</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-card" style="background:#27ae60">
                        <div class="stat-number">{{ $stats['verified'] }}</div>
                        <div class="stat-label"><i class="bi bi-patch-check me-1"></i>Vérifiés</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-card" style="background:#c0392b">
                        <div class="stat-number">{{ $stats['pending'] }}</div>
                        <div class="stat-label"><i class="bi bi-clock me-1"></i>En attente</div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="stat-card" style="background:#8e44ad">
                        <div class="stat-number">{{ $stats['requests'] }}</div>
                        <div class="stat-label"><i class="bi bi-send me-1"></i>Demandes</div>
                    </div>
                </div>
            </div>

            {{-- Professeurs en attente --}}
            <div class="card">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-clock me-2 text-warning"></i>
                        Professeurs en attente de validation
                    </h6>
                    <a href="{{ route('admin.teachers', ['status' => 'pending']) }}"
                       class="btn btn-sm btn-outline-secondary">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    @if($pendingTeachers->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-check-circle fs-2 d-block mb-2 text-success"></i>
                            Aucun professeur en attente.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Professeur</th>
                                        <th>Téléphone</th>
                                        <th>Documents</th>
                                        <th>Inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingTeachers as $teacher)
                                    <tr>
                                        <td class="fw-semibold">{{ $teacher->user->name }}</td>
                                        <td class="small">{{ $teacher->user->phone }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $teacher->documents->count() }} doc(s)
                                            </span>
                                        </td>
                                        <td class="small text-muted">
                                            {{ $teacher->created_at->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <form action="{{ route('admin.teachers.verify', $teacher->id) }}"
                                                      method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="verified">
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="bi bi-check-lg"></i> Valider
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.teachers.verify', $teacher->id) }}"
                                                      method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="refused">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-x-lg"></i> Refuser
                                                    </button>
                                                </form>
                                            </div>
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