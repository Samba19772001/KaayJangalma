@extends('layouts.app')
@section('title', $teacher->user->name)
@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand"><i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma</a>
    @auth
        @if(Auth::user()->isParent())
            <a href="{{ route('parent.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
            <a href="{{ route('parent.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
            <a href="{{ route('search.index') }}" class="active"><i class="bi bi-search"></i> Rechercher</a>
            <a href="{{ route('parent.favorites') }}"><i class="bi bi-heart"></i> Favoris</a>
            <a href="{{ route('parent.requests') }}"><i class="bi bi-send"></i> Mes demandes</a>
            <a href="{{ route('parent.announcements') }}"><i class="bi bi-megaphone"></i> Mes annonces</a>
        @elseif(Auth::user()->isTeacher())
            <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
            <a href="{{ route('teacher.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
            <a href="{{ route('search.index') }}" class="active"><i class="bi bi-search"></i> Rechercher</a>
        @endif
        <hr class="sidebar-divider">
        <form action="{{ route('auth.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn p-0 w-100 text-start" style="color:rgba(255,255,255,.8)">
                <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
            </button>
        </form>
    @endauth
@endsection
@section('content')
<div class="container py-5">
    <div class="row g-4">

        {{-- ── Colonne gauche ── --}}
        <div class="col-md-4">

            {{-- Carte profil --}}
            <div class="card p-4 text-center mb-3">
                @if($teacher->photo)
                    <img src="{{ asset('storage/'.$teacher->photo) }}"
                         class="rounded-circle mx-auto mb-3"
                         style="width:120px;height:120px;object-fit:cover;
                                border:4px solid var(--kj-green)">
                @else
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center
                                justify-content-center text-white fw-bold"
                         style="width:120px;height:120px;background:var(--kj-green);
                                font-size:3rem;border:4px solid var(--kj-green)">
                        {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                    </div>
                @endif

                <h4 class="fw-bold mb-1">{{ $teacher->user->name }}</h4>

                <div class="d-flex justify-content-center gap-2 mb-2">
                    @if($teacher->is_premium)
                        <span class="badge-premium">
                            <i class="bi bi-star-fill me-1"></i>Premium
                        </span>
                    @endif
                    @if($teacher->isVerified())
                        <span class="badge-verified">
                            <i class="bi bi-patch-check-fill me-1"></i>Vérifié
                        </span>
                    @endif
                </div>

                {{-- Note --}}
                <div class="d-flex justify-content-center align-items-center gap-1 mb-3">
                    <span class="stars fs-5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= round($teacher->average_rating) ? '-fill' : '' }}"></i>
                        @endfor
                    </span>
                    <span class="text-muted small">({{ $teacher->total_reviews }} avis)</span>
                </div>

                {{-- Tarif --}}
                <div class="mb-3">
                    <span class="fs-4 fw-bold" style="color:var(--kj-green)">
                        {{ number_format($teacher->hourly_rate, 0, ',', ' ') }} FCFA
                    </span>
                    <span class="text-muted small">/heure</span>
                    @if($teacher->monthly_rate)
                        <div class="text-muted small">
                            {{ number_format($teacher->monthly_rate, 0, ',', ' ') }} FCFA/mois
                        </div>
                    @endif
                </div>

                {{-- Boutons --}}
                @if($teacher->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $teacher->whatsapp) }}"
                       target="_blank"
                       class="btn btn-success w-100 mb-2 fw-semibold">
                        <i class="bi bi-whatsapp me-2"></i>Contacter sur WhatsApp
                    </a>
                @endif

                @auth
                    @if(Auth::user()->isParent())
                        <form action="{{ route('parent.favorites.toggle', $teacher->id) }}"
                              method="POST" class="mb-2">
                            @csrf
                            <button type="submit"
                                    class="btn w-100 fw-semibold
                                           {{ $isFavorite ? 'btn-danger' : 'btn-outline-danger' }}">
                                <i class="bi bi-heart{{ $isFavorite ? '-fill' : '' }} me-2"></i>
                                {{ $isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}
                            </button>
                        </form>
                    @endif
                @endauth
            </div>

            {{-- Infos ── --}}
            <div class="card p-3 mb-3">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-info-circle me-2" style="color:var(--kj-green)"></i>
                    Informations
                </h6>
                <ul class="list-unstyled small mb-0">
                    @if($teacher->experience_years)
                        <li class="mb-2">
                            <i class="bi bi-briefcase me-2 text-muted"></i>
                            {{ $teacher->experience_years }} an(s) d'expérience
                        </li>
                    @endif
                    @if($teacher->education_level)
                        <li class="mb-2">
                            <i class="bi bi-mortarboard me-2 text-muted"></i>
                            {{ $teacher->education_level }}
                            @if($teacher->university)
                                — {{ $teacher->university }}
                            @endif
                        </li>
                    @endif
                    @if($teacher->gender)
                        <li class="mb-2">
                            <i class="bi bi-person me-2 text-muted"></i>
                            {{ $teacher->gender === 'male' ? 'Homme' : 'Femme' }}
                        </li>
                    @endif
                    @if($teacher->zones->first())
                        <li class="mb-2">
                            <i class="bi bi-geo-alt me-2 text-muted"></i>
                            {{ $teacher->zones->first()->city }}
                            @if($teacher->zones->first()->neighborhood)
                                — {{ $teacher->zones->first()->neighborhood }}
                            @endif
                        </li>
                    @endif
                </ul>
            </div>

            {{-- Disponibilités --}}
            @if($teacher->availabilities->count())
                <div class="card p-3">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-calendar3 me-2" style="color:var(--kj-green)"></i>
                        Disponibilités
                    </h6>
                    @php
                        $days  = \App\Models\TeacherAvailability::$days;
                        $slots = \App\Models\TeacherAvailability::$slots;
                        $avail = $teacher->availabilities->groupBy('day');
                    @endphp
                    @foreach($avail as $day => $items)
                        <div class="mb-2">
                            <div class="small fw-semibold">{{ $days[$day] ?? $day }}</div>
                            <div class="d-flex gap-1 flex-wrap">
                                @foreach($items as $item)
                                    <span class="badge bg-light text-dark border">
                                        {{ $slots[$item->slot] ?? $item->slot }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        {{-- ── Colonne droite ── --}}
        <div class="col-md-8">

            {{-- Présentation --}}
            @if($teacher->bio)
                <div class="card p-4 mb-3">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-person-lines-fill me-2" style="color:var(--kj-green)"></i>
                        Présentation
                    </h5>
                    <p class="text-muted mb-0">{{ $teacher->bio }}</p>
                </div>
            @endif

            {{-- Matières et niveaux --}}
            <div class="card p-4 mb-3">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-book me-2" style="color:var(--kj-green)"></i>
                    Matières enseignées
                </h5>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @foreach($teacher->subjects as $subject)
                        <span class="badge fs-6 fw-normal px-3 py-2"
                              style="background:rgba(27,122,74,.1);color:var(--kj-green)">
                            {{ $subject->name }}
                        </span>
                    @endforeach
                </div>

                <h6 class="fw-bold mb-2">Niveaux</h6>
                @php $levelLabels = \App\Models\TeacherLevel::$labels; @endphp
                <div class="d-flex flex-wrap gap-2">
                    @foreach($teacher->levels as $level)
                        <span class="badge bg-light text-dark border px-3 py-2">
                            {{ $levelLabels[$level->level] ?? $level->level }}
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- Demande de cours --}}
            @auth
                @if(Auth::user()->isParent())
                    <div class="card p-4 mb-3">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-send me-2" style="color:var(--kj-green)"></i>
                            Envoyer une demande de cours
                        </h5>
                        <form action="{{ route('parent.requests.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Matière</label>
                                    <select name="subject_id" class="form-select" required>
                                        <option value="">Choisir une matière</option>
                                        @foreach($teacher->subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Niveau</label>
                                    <select name="level" class="form-select" required>
                                        <option value="">Choisir un niveau</option>
                                        @foreach($teacher->levels as $level)
                                            <option value="{{ $level->level }}">
                                                {{ $levelLabels[$level->level] ?? $level->level }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Nombre d'heures</label>
                                    <input type="number" name="hours" class="form-control"
                                           min="1" placeholder="Ex : 10">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Adresse approximative</label>
                                    <input type="text" name="address" class="form-control"
                                           placeholder="Ex : Médina, Dakar">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Message</label>
                                    <textarea name="message" class="form-control" rows="3"
                                              placeholder="Décrivez vos besoins..."></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-kj fw-semibold px-4">
                                        <i class="bi bi-send me-2"></i>Envoyer la demande
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            @else
                <div class="card p-4 mb-3 text-center">
                    <i class="bi bi-lock fs-2 mb-2 text-muted"></i>
                    <p class="mb-2">Connectez-vous pour envoyer une demande de cours</p>
                    <a href="{{ route('auth.login') }}" class="btn btn-kj px-4">Se connecter</a>
                </div>
            @endauth

            {{-- Avis --}}
            <div class="card p-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-star me-2" style="color:var(--kj-green)"></i>
                    Avis ({{ $teacher->total_reviews }})
                </h5>

                @if($teacher->reviews->isEmpty())
                    <p class="text-muted small">Aucun avis pour l'instant.</p>
                @else
                    @foreach($teacher->reviews->take(5) as $review)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold small">
                                    {{ $review->parent->user->name }}
                                </span>
                                <span class="text-muted small">
                                    {{ $review->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                            <div class="stars small mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                            @if($review->comment)
                                <p class="small text-muted mb-0">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>
</div>
@endsection