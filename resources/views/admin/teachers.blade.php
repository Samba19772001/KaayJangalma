@extends('layouts.app')
@section('title', 'Gestion des professeurs')

@push('styles')
<style>
@media (max-width: 767px) {
    .table-responsive table thead { display: none; }
    .table-responsive table tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: .5rem;
    }
    .table-responsive table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: .5rem;
        border: none;
        border-bottom: 1px solid #f5f5f5;
        font-size: .85rem;
    }
    .table-responsive table td:last-child { border-bottom: none; }
    .table-responsive table td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #555;
        margin-right: .5rem;
        white-space: nowrap;
        min-width: 90px;
    }
}
</style>
@endpush

@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand"><i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma</a>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
    <a href="{{ route('admin.teachers') }}" class="active"><i class="bi bi-people"></i> Professeurs</a>
    <a href="{{ route('admin.subjects') }}"><i class="bi bi-book"></i> Matières</a>
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
                <a href="{{ route('admin.teachers') }}" class="active"><i class="bi bi-people"></i> Professeurs</a>
                <a href="{{ route('admin.subjects') }}"><i class="bi bi-book"></i> Matières</a>
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
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h4 class="fw-bold mb-0">
                    <i class="bi bi-people me-2" style="color:var(--kj-green)"></i>
                    Gestion des professeurs
                </h4>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.teachers') }}"
                       class="btn btn-sm {{ !request('status') ? 'btn-kj' : 'btn-outline-secondary' }}">
                        Tous
                    </a>
                    <a href="{{ route('admin.teachers', ['status' => 'pending']) }}"
                       class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-secondary' }}">
                        En attente
                    </a>
                    <a href="{{ route('admin.teachers', ['status' => 'verified']) }}"
                       class="btn btn-sm {{ request('status') === 'verified' ? 'btn-success' : 'btn-outline-secondary' }}">
                        Vérifiés
                    </a>
                    <a href="{{ route('admin.teachers', ['status' => 'refused']) }}"
                       class="btn btn-sm {{ request('status') === 'refused' ? 'btn-danger' : 'btn-outline-secondary' }}">
                        Refusés
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Professeur</th>
                                <th>Téléphone</th>
                                <th>Matières</th>
                                <th>Documents</th>
                                <th>Statut</th>
                                <th>Premium</th>
                                <th>Inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                            <tr>
                                <td data-label="Professeur">
                                    <a href="{{ route('admin.teachers.show', $teacher->id) }}"
                                        class="fw-semibold small text-decoration-none text-dark">
                                            {{ $teacher->user->name }}
                                    </a>
                                    <div class="d-flex align-items-center gap-2">
                                        
                                        @if($teacher->photo)
                                            <img src="{{ asset('storage/'.$teacher->photo) }}"
                                                 class="rounded-circle"
                                                 style="width:36px;height:36px;object-fit:cover">
                                        @else
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                                 style="width:36px;height:36px;background:var(--kj-green);font-size:.85rem">
                                                {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold small">{{ $teacher->user->name }}</div>
                                            <div class="text-muted" style="font-size:.75rem">{{ $teacher->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Téléphone" class="small">{{ $teacher->user->phone }}</td>
                                <td data-label="Matières">
                                    @foreach($teacher->subjects->take(2) as $subject)
                                        <span class="badge bg-light text-dark border me-1">{{ $subject->name }}</span>
                                    @endforeach
                                </td>
                                <td data-label="Documents">
                                    <span class="badge bg-secondary">{{ $teacher->documents->count() }} doc(s)</span>
                                </td>
                                <td data-label="Statut">
                                    @php
                                        $statusBadge = [
                                            'pending'  => ['bg-warning text-dark', 'En attente'],
                                            'verified' => ['bg-success', 'Vérifié'],
                                            'refused'  => ['bg-danger', 'Refusé'],
                                        ];
                                        [$sc, $sl] = $statusBadge[$teacher->verified_status] ?? ['bg-secondary', '—'];
                                    @endphp
                                    <span class="badge {{ $sc }}">{{ $sl }}</span>
                                </td>
                                <td data-label="Premium">
                                    @if($teacher->is_premium)
                                        <span class="badge-premium"><i class="bi bi-star-fill me-1"></i>Premium</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td data-label="Inscription" class="small text-muted">
                                    {{ $teacher->created_at->format('d/m/Y') }}
                                </td>
                                <td data-label="Actions">
                                    @if($teacher->verified_status === 'pending')
                                        <div class="d-flex gap-1">
                                            <form action="{{ route('admin.teachers.verify', $teacher->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="verified">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.teachers.verify', $teacher->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="refused">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        Aucun professeur trouvé.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $teachers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection