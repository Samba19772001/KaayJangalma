@extends('layouts.app')
@section('title', 'Mes demandes')

@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand"><i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma</a>
    <a href="{{ route('parent.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
    <a href="{{ route('parent.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
    <a href="{{ route('search.index') }}"><i class="bi bi-search"></i> Rechercher</a>
    <a href="{{ route('parent.favorites') }}"><i class="bi bi-heart"></i> Favoris</a>
    <a href="{{ route('parent.requests') }}" class="active"><i class="bi bi-send"></i> Mes demandes</a>
    <a href="{{ route('parent.announcements') }}"><i class="bi bi-megaphone"></i> Mes annonces</a>
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
                <a href="{{ route('parent.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
                <a href="{{ route('parent.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
                <a href="{{ route('search.index') }}"><i class="bi bi-search"></i> Rechercher</a>
                <a href="{{ route('parent.favorites') }}"><i class="bi bi-heart"></i> Favoris</a>
                <a href="{{ route('parent.requests') }}" class="active"><i class="bi bi-send"></i> Mes demandes</a>
                <a href="{{ route('parent.announcements') }}"><i class="bi bi-megaphone"></i> Mes annonces</a>
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
                <i class="bi bi-send me-2" style="color:var(--kj-green)"></i>
                Mes demandes de cours
            </h4>

            @if($requests->isEmpty())
                <div class="card text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">Aucune demande pour l'instant</h5>
                    <div class="mt-2">
                        <a href="{{ route('search.index') }}" class="btn btn-kj px-4">Trouver un professeur</a>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Professeur</th>
                                    <th>Matière</th>
                                    <th>Niveau</th>
                                    <th>Heures</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $levels = ['primary'=>'Primaire','middle'=>'Collège','high'=>'Lycée'];
                                    $badges = [
                                        'sent'      => ['bg-info text-dark','Envoyée'],
                                        'accepted'  => ['bg-success','Acceptée'],
                                        'refused'   => ['bg-danger','Refusée'],
                                        'completed' => ['bg-secondary','Terminée'],
                                    ];
                                @endphp
                                @foreach($requests as $req)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($req->teacher->photo)
                                                <img src="{{ asset('storage/'.$req->teacher->photo) }}"
                                                     class="rounded-circle"
                                                     style="width:36px;height:36px;object-fit:cover">
                                            @else
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                                     style="width:36px;height:36px;background:var(--kj-green);font-size:.85rem">
                                                    {{ strtoupper(substr($req->teacher->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <a href="{{ route('teacher.public', $req->teacher->id) }}"
                                               class="fw-semibold small text-decoration-none text-dark">
                                                {{ $req->teacher->user->name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="small">{{ $req->subject->name }}</td>
                                    <td class="small">{{ $levels[$req->level] ?? $req->level }}</td>
                                    <td class="small">{{ $req->hours ?? '—' }}h</td>
                                    <td>
                                        @php [$color, $label] = $badges[$req->status] ?? ['bg-secondary','—']; @endphp
                                        <span class="badge {{ $color }}">{{ $label }}</span>
                                    </td>
                                    <td class="small text-muted">{{ $req->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($req->status === 'accepted')
                                            <div class="d-flex gap-1 flex-wrap">
                                                {{-- Message interne --}}
                                                <form action="{{ route('messages.new', $req->teacher->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="body"
                                                           value="Bonjour {{ $req->teacher->user->name }}, suite à l'acceptation de ma demande de cours en {{ $req->subject->name }}, je souhaite planifier nos séances.">
                                                    <button type="submit" class="btn btn-sm btn-kj">
                                                        <i class="bi bi-chat me-1"></i>Message
                                                    </button>
                                                </form>
                                                {{-- WhatsApp --}}
                                                @if($req->teacher->whatsapp)
                                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $req->teacher->whatsapp) }}"
                                                       target="_blank"
                                                       class="btn btn-sm btn-success">
                                                        <i class="bi bi-whatsapp"></i>
                                                    </a>
                                                @endif
                                            </div>

                                        @elseif($req->status === 'completed' && !$req->review)
                                            <button class="btn btn-sm btn-outline-warning"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#reviewModal{{ $req->id }}">
                                                <i class="bi bi-star me-1"></i>Noter
                                            </button>
                                            <div class="modal fade" id="reviewModal{{ $req->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold">Laisser un avis</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="{{ route('parent.reviews.store', $req->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-semibold">Note</label>
                                                                    <div class="d-flex gap-2">
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio"
                                                                                       name="rating" value="{{ $i }}"
                                                                                       id="star{{ $req->id }}_{{ $i }}" required>
                                                                                <label class="form-check-label stars"
                                                                                       for="star{{ $req->id }}_{{ $i }}">
                                                                                    {{ $i }} <i class="bi bi-star-fill"></i>
                                                                                </label>
                                                                            </div>
                                                                        @endfor
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-semibold">Commentaire</label>
                                                                    <textarea name="comment" class="form-control" rows="3"
                                                                              placeholder="Votre avis..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="btn btn-kj">
                                                                    Publier l'avis
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        @elseif($req->review)
                                            <span class="stars small">
                                                @for($i = 1; $i <= $req->review->rating; $i++)
                                                    <i class="bi bi-star-fill"></i>
                                                @endfor
                                            </span>

                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
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