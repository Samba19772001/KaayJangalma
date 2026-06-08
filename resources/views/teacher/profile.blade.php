@extends('layouts.app')
@section('title', 'Mon profil professeur')

@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand">
        <i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma
    </a>
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
    <a href="{{ route('teacher.profile') }}" class="active"><i class="bi bi-person-circle"></i> Mon profil</a>
    <a href="{{ route('teacher.requests') }}"><i class="bi bi-inbox"></i> Demandes</a>
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
                <a href="{{ route('teacher.profile') }}" class="active"><i class="bi bi-person-circle"></i> Mon profil</a>
                <a href="{{ route('teacher.requests') }}"><i class="bi bi-inbox"></i> Demandes</a>
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
            <h4 class="fw-bold mb-4">Mon profil professeur</h4>

            <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-4">

                        {{-- Photo --}}
                        <div class="card p-4 text-center mb-3">
                            @if($teacher->photo)
                                <img src="{{ asset('storage/'.$teacher->photo) }}"
                                     class="rounded-circle mx-auto mb-3"
                                     style="width:120px;height:120px;object-fit:cover;border:4px solid var(--kj-green)">
                            @else
                                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="width:120px;height:120px;background:var(--kj-green);font-size:3rem">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <label class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-camera me-1"></i> Changer la photo
                                <input type="file" name="photo" class="d-none" accept="image/*">
                            </label>
                        </div>

                        {{-- Documents --}}
                        <div class="card p-4">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-file-earmark me-2" style="color:var(--kj-green)"></i>
                                Documents justificatifs
                            </h6>
                            @foreach($teacher->documents as $doc)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small">
                                        {{ ['cni'=>'CNI','diploma'=>'Diplôme','certificate'=>'Certificat'][$doc->type] ?? $doc->type }}
                                    </span>
                                    <span class="badge {{
                                        $doc->status === 'verified' ? 'bg-success' :
                                        ($doc->status === 'refused' ? 'bg-danger' : 'bg-warning text-dark')
                                    }}">
                                        {{ ['pending'=>'En attente','verified'=>'Vérifié','refused'=>'Refusé'][$doc->status] ?? $doc->status }}
                                    </span>
                                </div>
                            @endforeach
                            <hr>
                            <p class="small text-muted mb-2">Ajouter un document :</p>
                            <form action="{{ route('teacher.documents.upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-2">
                                    <select name="type" class="form-select form-select-sm" required>
                                        <option value="">Type de document</option>
                                        <option value="cni">CNI</option>
                                        <option value="diploma">Diplôme</option>
                                        <option value="certificate">Certificat</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <input type="file" name="file" class="form-control form-control-sm"
                                           accept=".pdf,.jpg,.jpeg,.png" required>
                                </div>
                                <button type="submit" class="btn btn-sm btn-kj w-100">
                                    <i class="bi bi-upload me-1"></i> Envoyer
                                </button>
                            </form>
                        </div>

                    </div>

                    <div class="col-md-8">

                        {{-- Infos personnelles --}}
                        <div class="card p-4 mb-3">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-person me-2" style="color:var(--kj-green)"></i>
                                Informations personnelles
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Nom complet</label>
                                    <input type="text" name="name" class="form-control"
                                           value="{{ Auth::user()->name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Sexe</label>
                                    <select name="gender" class="form-select">
                                        <option value="">Choisir</option>
                                        <option value="male"   {{ $teacher->gender === 'male'   ? 'selected' : '' }}>Homme</option>
                                        <option value="female" {{ $teacher->gender === 'female' ? 'selected' : '' }}>Femme</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Date de naissance</label>
                                    <input type="date" name="birth_date" class="form-control"
                                           value="{{ $teacher->birth_date?->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">WhatsApp</label>
                                    <input type="tel" name="whatsapp" class="form-control"
                                           value="{{ $teacher->whatsapp }}" placeholder="Ex : 77 000 00 00">
                                </div>
                            </div>
                        </div>

                        {{-- Infos professionnelles --}}
                        <div class="card p-4 mb-3">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-briefcase me-2" style="color:var(--kj-green)"></i>
                                Informations professionnelles
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Niveau d'études</label>
                                    <input type="text" name="education_level" class="form-control"
                                           value="{{ $teacher->education_level }}" placeholder="Ex : Master, Licence...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Université / Établissement</label>
                                    <input type="text" name="university" class="form-control"
                                           value="{{ $teacher->university }}" placeholder="Ex : UCAD, UGB...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Années d'expérience</label>
                                    <input type="number" name="experience_years" class="form-control"
                                           value="{{ $teacher->experience_years }}" min="0">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Tarif / heure (FCFA)</label>
                                    <input type="number" name="hourly_rate" class="form-control"
                                           value="{{ $teacher->hourly_rate }}" placeholder="Ex : 3000">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Tarif / mois (FCFA)</label>
                                    <input type="number" name="monthly_rate" class="form-control"
                                           value="{{ $teacher->monthly_rate }}" placeholder="Ex : 50000">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Présentation</label>
                                    <textarea name="bio" class="form-control" rows="4"
                                              placeholder="Décrivez votre expérience...">{{ $teacher->bio }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Matières --}}
                        <div class="card p-4 mb-3">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-book me-2" style="color:var(--kj-green)"></i>
                                Matières enseignées
                            </h6>
                            <div class="row g-2">
                                @foreach($subjects as $subject)
                                    <div class="col-md-4 col-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                   name="subjects[]" value="{{ $subject->id }}"
                                                   id="subject_{{ $subject->id }}"
                                                   {{ $teacher->subjects->contains($subject->id) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="subject_{{ $subject->id }}">
                                                {{ $subject->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Niveaux --}}
                        <div class="card p-4 mb-3">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-mortarboard me-2" style="color:var(--kj-green)"></i>
                                Niveaux enseignés
                            </h6>
                            <div class="d-flex gap-4 flex-wrap">
                                @foreach($levels as $key => $label)
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input"
                                               name="levels[]" value="{{ $key }}"
                                               id="level_{{ $key }}"
                                               {{ $teacher->levels->contains('level', $key) ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-semibold" for="level_{{ $key }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Disponibilités --}}
                        <div class="card p-4 mb-3">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-calendar3 me-2" style="color:var(--kj-green)"></i>
                                Disponibilités
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center small">
                                    <thead class="table-light">
                                        <tr>
                                            <th></th>
                                            @foreach($slots as $slotKey => $slotLabel)
                                                <th>{{ $slotLabel }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($days as $dayKey => $dayLabel)
                                            <tr>
                                                <td class="fw-semibold">{{ $dayLabel }}</td>
                                                @foreach($slots as $slotKey => $slotLabel)
                                                    <td>
                                                        <input type="checkbox"
                                                               name="availabilities[{{ $dayKey }}][]"
                                                               value="{{ $slotKey }}"
                                                               {{ $teacher->availabilities->where('day', $dayKey)->where('slot', $slotKey)->count() ? 'checked' : '' }}>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Zone d'intervention --}}
                        <div class="card p-4 mb-3">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-geo-alt me-2" style="color:var(--kj-green)"></i>
                                Zone d'intervention
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Région</label>
                                    <input type="text" name="region" class="form-control"
                                           value="{{ $teacher->zones->first()?->region }}" placeholder="Ex : Diourbel">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Département</label>
                                    <input type="text" name="department" class="form-control"
                                           value="{{ $teacher->zones->first()?->department }}" placeholder="Ex : Mbacké">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Ville</label>
                                    <input type="text" name="city" class="form-control"
                                           value="{{ $teacher->zones->first()?->city }}" placeholder="Ex : Touba">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Quartier</label>
                                    <input type="text" name="neighborhood" class="form-control"
                                           value="{{ $teacher->zones->first()?->neighborhood }}" placeholder="Ex : Ndamatou">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-kj fw-semibold px-5 py-2">
                            <i class="bi bi-check2 me-2"></i> Enregistrer le profil
                        </button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection