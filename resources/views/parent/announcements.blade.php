@extends('layouts.app')
@section('title', 'Mes annonces')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 px-0 d-none d-md-block">
            <div class="sidebar">
                <a href="{{ route('home') }}" class="brand">
                    <i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma
                </a>
                <a href="{{ route('parent.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
                <a href="{{ route('parent.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
                <a href="{{ route('search.index') }}"><i class="bi bi-search"></i> Rechercher</a>
                <a href="{{ route('parent.favorites') }}"><i class="bi bi-heart"></i> Favoris</a>
                <a href="{{ route('parent.requests') }}"><i class="bi bi-send"></i> Mes demandes</a>
                <a href="{{ route('parent.announcements') }}" class="active"><i class="bi bi-megaphone"></i> Mes annonces</a>
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
                <h4 class="fw-bold mb-0">
                    <i class="bi bi-megaphone me-2" style="color:var(--kj-green)"></i>
                    Mes annonces publiques
                </h4>
                <button class="btn btn-kj" data-bs-toggle="modal" data-bs-target="#newAnnouncement">
                    <i class="bi bi-plus-lg me-1"></i> Nouvelle annonce
                </button>
            </div>

            @if($announcements->isEmpty())
                <div class="card text-center py-5">
                    <i class="bi bi-megaphone fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">Aucune annonce publiée</h5>
                    <p class="text-muted small">
                        Publiez une annonce pour recevoir des propositions de professeurs.
                    </p>
                </div>
            @else
                <div class="row g-3">
                    @foreach($announcements as $ann)
                        <div class="col-md-6">
                            <div class="card p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge" style="background:var(--kj-green)">
                                        {{ $ann->subject->name }}
                                    </span>
                                    <span class="badge {{ $ann->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $ann->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <p class="mb-2 small">{{ $ann->description }}</p>
                                <div class="small text-muted d-flex gap-3">
                                    @if($ann->city)
                                        <span><i class="bi bi-geo-alt me-1"></i>{{ $ann->city }}</span>
                                    @endif
                                    @if($ann->budget)
                                        <span><i class="bi bi-cash me-1"></i>{{ number_format($ann->budget, 0, ',', ' ') }} FCFA</span>
                                    @endif
                                    <span><i class="bi bi-people me-1"></i>{{ $ann->applications->count() }} candidature(s)</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal nouvelle annonce --}}
<div class="modal fade" id="newAnnouncement" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Publier une annonce</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('parent.announcements.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Matière</label>
                            <select name="subject_id" class="form-select" required>
                                <option value="">Choisir</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Niveau</label>
                            <select name="level" class="form-select" required>
                                <option value="">Choisir</option>
                                <option value="primary">Primaire</option>
                                <option value="middle">Collège</option>
                                <option value="high">Lycée</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ville</label>
                            <input type="text" name="city" class="form-control" placeholder="Ex : Touba">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Budget (FCFA)</label>
                            <input type="number" name="budget" class="form-control" placeholder="Ex : 5000">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3" required
                                      placeholder="Ex : Recherche professeur de Maths pour élève en Terminale S..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-kj fw-semibold">
                        <i class="bi bi-megaphone me-1"></i> Publier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection