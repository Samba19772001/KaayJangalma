@extends('layouts.app')
@section('title', 'Gestion des matières')

@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand"><i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma</a>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
    <a href="{{ route('admin.teachers') }}"><i class="bi bi-people"></i> Professeurs</a>
    <a href="{{ route('admin.subjects') }}" class="active"><i class="bi bi-book"></i> Matières</a>
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
                <a href="{{ route('admin.subjects') }}" class="active"><i class="bi bi-book"></i> Matières</a>
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
                <i class="bi bi-book me-2" style="color:var(--kj-green)"></i>
                Gestion des matières
            </h4>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card p-4">
                        <h6 class="fw-bold mb-3">Ajouter une matière</h6>
                        <form action="{{ route('admin.subjects.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Nom de la matière</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       placeholder="Ex : Philosophie" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-kj w-100 fw-semibold">
                                <i class="bi bi-plus-lg me-1"></i> Ajouter
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h6 class="fw-bold mb-0">
                                Liste des matières ({{ $subjects->count() }})
                            </h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Matière</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjects as $subject)
                                    <tr>
                                        <td class="fw-semibold">{{ $subject->name }}</td>
                                        <td>
                                            @if($subject->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.subjects.toggle', $subject->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                        class="btn btn-sm {{ $subject->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                                    {{ $subject->is_active ? 'Désactiver' : 'Activer' }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection