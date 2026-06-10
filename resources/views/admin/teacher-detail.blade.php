@extends('layouts.app')
@section('title', 'Détail professeur')

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

            {{-- Retour --}}
            <a href="{{ route('admin.teachers') }}" class="btn btn-sm btn-outline-secondary mb-4">
                <i class="bi bi-arrow-left me-1"></i> Retour à la liste
            </a>

            <div class="row g-4">

                {{-- Colonne gauche --}}
                <div class="col-md-4">

                    {{-- Carte profil --}}
                    <div class="card p-4 text-center mb-3">
                        @if($teacher->photo)
                            <img src="{{ asset('storage/'.$teacher->photo) }}"
                                 class="rounded-circle mx-auto mb-3"
                                 style="width:100px;height:100px;object-fit:cover;border:4px solid var(--kj-green)">
                        @else
                            <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center text-white fw-bold"
                                 style="width:100px;height:100px;background:var(--kj-green);font-size:2.5rem">
                                {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                            </div>
                        @endif

                        <h5 class="fw-bold mb-1">{{ $teacher->user->name }}</h5>
                        <p class="text-muted small mb-2">{{ $teacher->user->phone }}</p>
                        @if($teacher->user->email)
                            <p class="text-muted small mb-2">{{ $teacher->user->email }}</p>
                        @endif

                        {{-- Statut actuel --}}
                        <div class="mb-3">
                            @if($teacher->verified_status === 'verified')
                                <span class="badge bg-success px-3 py-2">
                                    <i class="bi bi-patch-check-fill me-1"></i>Vérifié
                                </span>
                            @elseif($teacher->verified_status === 'pending')
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="bi bi-clock me-1"></i>En attente
                                </span>
                            @else
                                <span class="badge bg-danger px-3 py-2">
                                    <i class="bi bi-x-circle me-1"></i>Refusé
                                </span>
                            @endif
                            @if($teacher->is_premium)
                                <span class="badge-premium ms-1">
                                    <i class="bi bi-star-fill me-1"></i>Premium
                                </span>
                            @endif
                        </div>

                        {{-- Boutons de décision --}}
                        <div class="d-grid gap-2">
                            @if($teacher->verified_status !== 'verified')
                                <form action="{{ route('admin.teachers.verify', $teacher->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="verified">
                                    <button type="submit" class="btn btn-success w-100 fw-semibold">
                                        <i class="bi bi-check-circle me-2"></i>Valider ce professeur
                                    </button>
                                </form>
                            @endif
                            @if($teacher->verified_status !== 'refused')
                                <form action="{{ route('admin.teachers.verify', $teacher->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="refused">
                                    <button type="submit" class="btn btn-outline-danger w-100 fw-semibold">
                                        <i class="bi bi-x-circle me-2"></i>Refuser ce professeur
                                    </button>
                                </form>
                            @endif
                            @if($teacher->verified_status !== 'pending')
                                <form action="{{ route('admin.teachers.verify', $teacher->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="btn btn-outline-warning w-100 fw-semibold">
                                        <i class="bi bi-clock me-2"></i>Remettre en attente
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    {{-- Note avec note admin --}}
                    <div class="card p-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-chat-text me-2" style="color:var(--kj-green)"></i>
                            Note administrative
                        </h6>
                        <form action="{{ route('admin.teachers.verify', $teacher->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $teacher->verified_status }}">
                            <textarea name="admin_note" class="form-control mb-2" rows="3"
                                      placeholder="Ajoutez une note pour le professeur...">{{ $teacher->documents->first()?->admin_note }}</textarea>
                            <button type="submit" class="btn btn-sm btn-kj w-100">
                                <i class="bi bi-save me-1"></i> Enregistrer la note
                            </button>
                        </form>
                    </div>

                </div>

                {{-- Colonne droite --}}
                <div class="col-md-8">

                    {{-- Infos --}}
                    <div class="card p-4 mb-3">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-info-circle me-2" style="color:var(--kj-green)"></i>
                            Informations
                        </h6>
                        <div class="row g-2 small">
                            <div class="col-md-6">
                                <span class="text-muted">Niveau d'études :</span>
                                <span class="fw-semibold ms-1">{{ $teacher->education_level ?? '—' }}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted">Université :</span>
                                <span class="fw-semibold ms-1">{{ $teacher->university ?? '—' }}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted">Expérience :</span>
                                <span class="fw-semibold ms-1">{{ $teacher->experience_years }} an(s)</span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted">Tarif/heure :</span>
                                <span class="fw-semibold ms-1">
                                    {{ $teacher->hourly_rate ? number_format($teacher->hourly_rate, 0, ',', ' ').' FCFA' : '—' }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted">WhatsApp :</span>
                                <span class="fw-semibold ms-1">{{ $teacher->whatsapp ?? '—' }}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted">Inscription :</span>
                                <span class="fw-semibold ms-1">{{ $teacher->created_at->format('d/m/Y') }}</span>
                            </div>
                            @if($teacher->zones->first())
                            <div class="col-md-6">
                                <span class="text-muted">Ville :</span>
                                <span class="fw-semibold ms-1">{{ $teacher->zones->first()->city }}</span>
                            </div>
                            @endif
                        </div>

                        @if($teacher->bio)
                            <hr>
                            <p class="small text-muted mb-0">{{ $teacher->bio }}</p>
                        @endif
                    </div>

                    {{-- Matières et niveaux --}}
                    <div class="card p-4 mb-3">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-book me-2" style="color:var(--kj-green)"></i>
                            Matières et niveaux
                        </h6>
                        <div class="mb-2">
                            @forelse($teacher->subjects as $subject)
                                <span class="badge me-1 mb-1 px-3 py-2"
                                      style="background:rgba(27,122,74,.1);color:var(--kj-green)">
                                    {{ $subject->name }}
                                </span>
                            @empty
                                <span class="text-muted small">Aucune matière renseignée</span>
                            @endforelse
                        </div>
                        @php $levelLabels = ['primary'=>'Primaire','middle'=>'Collège','high'=>'Lycée']; @endphp
                        <div class="d-flex gap-2 flex-wrap mt-2">
                            @forelse($teacher->levels as $level)
                                <span class="badge bg-light text-dark border px-3 py-2">
                                    {{ $levelLabels[$level->level] ?? $level->level }}
                                </span>
                            @empty
                                <span class="text-muted small">Aucun niveau renseigné</span>
                            @endforelse
                        </div>
                    </div>

                    {{-- Documents --}}
                    <div class="card p-4 mb-3">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-file-earmark me-2" style="color:var(--kj-green)"></i>
                            Documents soumis ({{ $teacher->documents->count() }})
                        </h6>

                        @forelse($teacher->documents as $doc)
                            <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded"
                                 style="background:#f8f9fa">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="bi bi-file-earmark-text fs-3 text-muted"></i>
                                    <div>
                                        <div class="fw-semibold small">
                                            {{ ['cni'=>'CNI','diploma'=>'Diplôme','certificate'=>'Certificat'][$doc->type] ?? $doc->type }}
                                        </div>
                                        <div class="text-muted" style="font-size:.75rem">
                                            Soumis le {{ $doc->created_at->format('d/m/Y') }}
                                        </div>
                                        @if($doc->admin_note)
                                            <div class="text-danger small mt-1">
                                                Note : {{ $doc->admin_note }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge {{
                                        $doc->status === 'verified' ? 'bg-success' :
                                        ($doc->status === 'refused' ? 'bg-danger' : 'bg-warning text-dark')
                                    }}">
                                        {{ ['pending'=>'En attente','verified'=>'Vérifié','refused'=>'Refusé'][$doc->status] }}
                                    </span>
                                    <a href="{{ asset('storage/'.$doc->file_path) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye me-1"></i>Voir
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-3 text-muted">
                                <i class="bi bi-file-earmark-x fs-2 d-block mb-2"></i>
                                Aucun document soumis.
                            </div>
                        @endforelse
                    </div>

                    {{-- Avis --}}
                    @if($teacher->reviews->count())
                        <div class="card p-4">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-star me-2" style="color:var(--kj-green)"></i>
                                Avis reçus ({{ $teacher->reviews->count() }})
                                — Moyenne :
                                <span style="color:var(--kj-yellow)">
                                    {{ number_format($teacher->average_rating, 1) }}/5
                                </span>
                            </h6>
                            @foreach($teacher->reviews->take(5) as $review)
                                <div class="border-bottom pb-2 mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold small">{{ $review->parent->user->name }}</span>
                                        <span class="stars small">
                                            @for($i = 1; $i <= $review->rating; $i++)
                                                <i class="bi bi-star-fill"></i>
                                            @endfor
                                        </span>
                                    </div>
                                    @if($review->comment)
                                        <p class="small text-muted mb-0">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection