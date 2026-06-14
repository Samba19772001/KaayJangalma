@extends('layouts.app')
@section('title', 'Annonces publiques')

@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand"><i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma</a>
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
    <a href="{{ route('teacher.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
    <a href="{{ route('teacher.requests') }}"><i class="bi bi-inbox"></i> Demandes</a>
    <a href="{{ route('teacher.announcements') }}" class="active"><i class="bi bi-megaphone"></i> Annonces</a>
    <a href="{{ route('messages.index') }}"><i class="bi bi-chat"></i> Messages</a>
    <a href="{{ route('teacher.stats') }}"><i class="bi bi-bar-chart"></i> Statistiques</a>
    <a href="{{ route('teacher.subscription') }}"><i class="bi bi-star"></i> Abonnement Premium</a>
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
                <a href="{{ route('teacher.announcements') }}" class="active"><i class="bi bi-megaphone"></i> Annonces</a>
                <a href="{{ route('messages.index') }}"><i class="bi bi-chat"></i> Messages</a>
                <a href="{{ route('teacher.stats') }}"><i class="bi bi-bar-chart"></i> Statistiques</a>
                <a href="{{ route('teacher.subscription') }}"><i class="bi bi-star"></i> Abonnement Premium</a>
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
                <i class="bi bi-megaphone me-2" style="color:var(--kj-green)"></i>
                Annonces publiques des parents
            </h4>

            @if($announcements->isEmpty())
                <div class="card text-center py-5">
                    <i class="bi bi-megaphone fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">Aucune annonce pour l'instant</h5>
                    <p class="text-muted small">
                        Les parents publient des annonces quand ils cherchent un professeur.
                        Revenez plus tard !
                    </p>
                </div>
            @else
                <div class="row g-3">
                    @php
                        $levels = ['primary'=>'Primaire','middle'=>'Collège','high'=>'Lycée'];
                    @endphp
                    @foreach($announcements as $ann)
                        <div class="col-md-6">
                            <div class="card p-4 h-100">
                                {{-- Header --}}
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                             style="width:40px;height:40px;background:var(--kj-green);font-size:.9rem">
                                            {{ strtoupper(substr($ann->parent->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold small">{{ $ann->parent->user->name }}</div>
                                            <div class="text-muted" style="font-size:.75rem">
                                                {{ $ann->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    <span class="badge" style="background:var(--kj-green)">
                                        {{ $ann->subject->name }}
                                    </span>
                                </div>

                                {{-- Description --}}
                                <p class="small mb-3">{{ $ann->description }}</p>

                                {{-- Infos --}}
                                <div class="d-flex gap-3 flex-wrap mb-3 small text-muted">
                                    <span>
                                        <i class="bi bi-mortarboard me-1"></i>
                                        {{ $levels[$ann->level] ?? $ann->level }}
                                    </span>
                                    @if($ann->city)
                                        <span>
                                            <i class="bi bi-geo-alt me-1"></i>
                                            {{ $ann->city }}
                                            @if($ann->neighborhood)
                                                — {{ $ann->neighborhood }}
                                            @endif
                                        </span>
                                    @endif
                                    @if($ann->budget)
                                        <span>
                                            <i class="bi bi-cash me-1"></i>
                                            {{ number_format($ann->budget, 0, ',', ' ') }} FCFA
                                        </span>
                                    @endif
                                    <span>
                                        <i class="bi bi-people me-1"></i>
                                        {{ $ann->applications->count() }} candidature(s)
                                    </span>
                                </div>

                                {{-- Action --}}
                                @if(in_array($ann->id, $appliedIds))
                                    <div class="alert alert-success py-2 mb-0 small">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Vous avez déjà postulé à cette annonce.
                                    </div>
                                @else
                                    <button class="btn btn-kj btn-sm fw-semibold mt-auto"
                                            data-bs-toggle="modal"
                                            data-bs-target="#applyModal{{ $ann->id }}">
                                        <i class="bi bi-send me-1"></i>Postuler
                                    </button>

                                    {{-- Modal postuler --}}
                                    <div class="modal fade" id="applyModal{{ $ann->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">
                                                        Postuler à cette annonce
                                                    </h5>
                                                    <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('teacher.announcements.apply', $ann->id) }}"
                                                      method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="alert alert-light border mb-3 small">
                                                            <strong>{{ $ann->subject->name }}</strong>
                                                            — {{ $levels[$ann->level] ?? $ann->level }}
                                                            @if($ann->city) — {{ $ann->city }} @endif
                                                        </div>
                                                        <label class="form-label fw-semibold">
                                                            Message au parent
                                                            <span class="text-muted fw-normal">(optionnel)</span>
                                                        </label>
                                                        <textarea name="message" class="form-control" rows="4"
                                                                  placeholder="Présentez-vous et expliquez pourquoi vous êtes le bon professeur pour ce poste..."></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-kj fw-semibold">
                                                            <i class="bi bi-send me-1"></i>Envoyer ma candidature
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection