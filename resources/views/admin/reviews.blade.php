@extends('layouts.app')
@section('title', 'Gestion des avis')

@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand"><i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma</a>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
    <a href="{{ route('admin.teachers') }}"><i class="bi bi-people"></i> Professeurs</a>
    <a href="{{ route('admin.subjects') }}"><i class="bi bi-book"></i> Matières</a>
    <a href="{{ route('admin.reviews') }}" class="active"><i class="bi bi-star"></i> Avis</a>
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
                <a href="{{ route('admin.reviews') }}" class="active"><i class="bi bi-star"></i> Avis</a>
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
                <i class="bi bi-star me-2" style="color:var(--kj-green)"></i>
                Modération des avis
            </h4>

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Parent</th>
                                <th>Professeur</th>
                                <th>Note</th>
                                <th>Commentaire</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                            <tr>
                                <td class="small fw-semibold">{{ $review->parent->user->name }}</td>
                                <td class="small">{{ $review->teacher->user->name }}</td>
                                <td>
                                    <span class="stars small">
                                        @for($i = 1; $i <= $review->rating; $i++)
                                            <i class="bi bi-star-fill"></i>
                                        @endfor
                                    </span>
                                </td>
                                <td class="small text-muted" style="max-width:250px">
                                    {{ $review->comment ?? '—' }}
                                </td>
                                <td class="small text-muted">{{ $review->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST"
                                          onsubmit="return confirm('Supprimer cet avis ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Aucun avis pour l'instant.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection