@extends('layouts.app')
@section('title', 'Rechercher un professeur')

@section('content')
<div class="container py-5">

    {{-- ── Titre ── --}}
    <div class="mb-4">
        <h3 class="fw-bold mb-1">Trouver un professeur</h3>
        <p class="text-muted small">{{ $teachers->total() }} professeur(s) trouvé(s)</p>
    </div>

    <div class="row g-4">

        {{-- ── Filtres ── --}}
        <div class="col-md-3">
            <div class="card p-3">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-funnel me-2" style="color:var(--kj-green)"></i>Filtres
                </h6>
                <form action="{{ route('search.index') }}" method="GET">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Matière</label>
                        <select name="subject_id" class="form-select form-select-sm">
                            <option value="">Toutes les matières</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Niveau</label>
                        <select name="level" class="form-select form-select-sm">
                            <option value="">Tous les niveaux</option>
                            <option value="primary"  {{ request('level') == 'primary'  ? 'selected' : '' }}>Primaire</option>
                            <option value="middle"   {{ request('level') == 'middle'   ? 'selected' : '' }}>Collège</option>
                            <option value="high"     {{ request('level') == 'high'     ? 'selected' : '' }}>Lycée</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Ville</label>
                        <input type="text" name="city" class="form-control form-control-sm"
                               placeholder="Ex : Dakar"
                               value="{{ request('city') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tarif (FCFA/heure)</label>
                        <div class="row g-1">
                            <div class="col-6">
                                <input type="number" name="min_rate"
                                       class="form-control form-control-sm"
                                       placeholder="Min"
                                       value="{{ request('min_rate') }}">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_rate"
                                       class="form-control form-control-sm"
                                       placeholder="Max"
                                       value="{{ request('max_rate') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Sexe</label>
                        <select name="gender" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            <option value="male"   {{ request('gender') == 'male'   ? 'selected' : '' }}>Homme</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Femme</option>
                        </select>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input"
                               name="premium_only" id="premium_only" value="1"
                               {{ request('premium_only') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="premium_only">
                            Premium uniquement
                        </label>
                    </div>

                    <button type="submit" class="btn btn-kj w-100 btn-sm">
                        <i class="bi bi-search me-1"></i> Rechercher
                    </button>

                    @if(request()->anyFilled(['subject_id','level','city','min_rate','max_rate','gender','premium_only']))
                        <a href="{{ route('search.index') }}"
                           class="btn btn-outline-secondary w-100 btn-sm mt-2">
                            Réinitialiser
                        </a>
                    @endif

                </form>
            </div>
        </div>

        {{-- ── Résultats ── --}}
        <div class="col-md-9">
            @if($teachers->isEmpty())
                <div class="card text-center py-5">
                    <div class="text-muted">
                        <i class="bi bi-search fs-1 d-block mb-3"></i>
                        <h5>Aucun professeur trouvé</h5>
                        <p class="small">Essayez de modifier vos filtres de recherche.</p>
                    </div>
                </div>
            @else
                <div class="row g-3">
                    @foreach($teachers as $teacher)
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('teacher.public', $teacher->id) }}"
                           class="text-decoration-none">
                            <div class="card teacher-card h-100 p-3">

                                {{-- Badges --}}
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="d-flex gap-1">
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
                                </div>

                                {{-- Avatar + Nom --}}
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if($teacher->photo)
                                        <img src="{{ asset('storage/'.$teacher->photo) }}"
                                             class="teacher-avatar">
                                    @else
                                        <div class="teacher-avatar-placeholder">
                                            {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold text-dark">{{ $teacher->user->name }}</div>
                                        <div class="small text-muted">
                                            {{ $teacher->experience_years }} an(s) d'expérience
                                        </div>
                                    </div>
                                </div>

                                {{-- Matières --}}
                                <div class="mb-2">
                                    @foreach($teacher->subjects->take(3) as $subject)
                                        <span class="badge bg-light text-dark border me-1 mb-1">
                                            {{ $subject->name }}
                                        </span>
                                    @endforeach
                                </div>

                                {{-- Ville --}}
                                @if($teacher->zones->first())
                                    <div class="small text-muted mb-2">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        {{ $teacher->zones->first()->city }}
                                    </div>
                                @endif

                                {{-- Note --}}
                                <div class="d-flex align-items-center gap-1 mb-2">
                                    <span class="stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= round($teacher->average_rating) ? '-fill' : '' }}"></i>
                                        @endfor
                                    </span>
                                    <span class="small text-muted">
                                        ({{ $teacher->total_reviews }})
                                    </span>
                                </div>

                                {{-- Tarif --}}
                                <div class="mt-auto pt-2 border-top">
                                    <span class="fw-bold" style="color:var(--kj-green)">
                                        {{ number_format($teacher->hourly_rate, 0, ',', ' ') }} FCFA
                                    </span>
                                    <span class="text-muted small"> / heure</span>
                                </div>

                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-4 d-flex justify-content-center">
                    {{ $teachers->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
