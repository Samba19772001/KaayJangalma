@extends('layouts.app')
@section('title', 'Accueil')

@section('content')

{{-- ── Hero ── --}}
<section style="background: linear-gradient(135deg, #1B7A4A 0%, #145c37 100%); min-height: 520px;"
         class="d-flex align-items-center">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white">
                <h1 class="display-4 fw-bold mb-3">
                    Trouvez le meilleur professeur à domicile
                </h1>
                <p class="lead mb-4 opacity-90">
                    KaayJangalma met en relation les parents et les enseignants
                    qualifiés partout au Sénégal. Rapide, fiable et sécurisé.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('search.index') }}"
                       class="btn btn-warning btn-lg fw-semibold px-4">
                        <i class="bi bi-search me-2"></i>Trouver un professeur
                    </a>
                    <a href="{{ route('auth.register') }}"
                       class="btn btn-outline-light btn-lg px-4">
                        Je suis professeur
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center d-none d-lg-block">
                <i class="bi bi-mortarboard-fill text-white opacity-25"
                   style="font-size: 16rem; line-height: 1;"></i>
            </div>
        </div>
    </div>
</section>

{{-- ── Statistiques ── --}}
<section class="py-4 bg-white shadow-sm">
    <div class="container">
        <div class="row text-center g-3">
            <div class="col-6 col-md-3">
                <div class="fw-bold fs-3" style="color: var(--kj-green)">500+</div>
                <div class="text-muted small">Professeurs vérifiés</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold fs-3" style="color: var(--kj-green)">1200+</div>
                <div class="text-muted small">Familles satisfaites</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold fs-3" style="color: var(--kj-green)">10+</div>
                <div class="text-muted small">Matières disponibles</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold fs-3" style="color: var(--kj-green)">15+</div>
                <div class="text-muted small">Villes couvertes</div>
            </div>
        </div>
    </div>
</section>

{{-- ── Comment ça marche ── --}}
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-2">Comment ça marche ?</h2>
        <p class="text-center text-muted mb-5">En 3 étapes simples</p>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width:64px;height:64px;background:var(--kj-green)">
                            <i class="bi bi-search text-white fs-4"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold">1. Recherchez</h5>
                    <p class="text-muted small">
                        Filtrez par matière, niveau, ville et budget pour trouver
                        le professeur idéal près de chez vous.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width:64px;height:64px;background:var(--kj-green)">
                            <i class="bi bi-chat-dots text-white fs-4"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold">2. Contactez</h5>
                    <p class="text-muted small">
                        Envoyez un message ou contactez directement via WhatsApp.
                        Discutez des détails et planifiez les cours.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width:64px;height:64px;background:var(--kj-green)">
                            <i class="bi bi-star text-white fs-4"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold">3. Évaluez</h5>
                    <p class="text-muted small">
                        Après les cours, laissez un avis pour aider
                        la communauté à choisir les meilleurs enseignants.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Matières populaires ── --}}
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center fw-bold mb-2">Matières disponibles</h2>
        <p class="text-center text-muted mb-5">Des professeurs qualifiés pour chaque matière</p>

        <div class="row g-3 justify-content-center">
            @foreach([
                ['icon' => 'bi-calculator',       'name' => 'Mathématiques'],
                ['icon' => 'bi-lightning-charge',  'name' => 'Physique'],
                ['icon' => 'bi-droplet',           'name' => 'Chimie'],
                ['icon' => 'bi-tree',              'name' => 'SVT'],
                ['icon' => 'bi-book',              'name' => 'Français'],
                ['icon' => 'bi-translate',         'name' => 'Anglais'],
                ['icon' => 'bi-moon-stars',        'name' => 'Arabe'],
                ['icon' => 'bi-globe',             'name' => 'Histoire-Géo'],
                ['icon' => 'bi-lightbulb',         'name' => 'Philosophie'],
                ['icon' => 'bi-laptop',            'name' => 'Informatique'],
            ] as $subject)
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('search.index') }}"
                   class="card text-center p-3 text-decoration-none h-100
                          d-flex align-items-center justify-content-center"
                   style="transition: all .2s"
                   onmouseover="this.style.borderColor='var(--kj-green)';this.style.color='var(--kj-green)'"
                   onmouseout="this.style.borderColor='';this.style.color=''">
                    <i class="bi {{ $subject['icon'] }} fs-2 mb-2"
                       style="color: var(--kj-green)"></i>
                    <small class="fw-semibold text-dark">{{ $subject['name'] }}</small>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA ── --}}
<section class="py-5" style="background: var(--kj-green)">
    <div class="container text-center text-white py-3">
        <h2 class="fw-bold mb-3">Vous êtes professeur ?</h2>
        <p class="lead mb-4 opacity-90">
            Rejoignez KaayJangalma et développez votre clientèle.
            Créez votre profil gratuitement dès aujourd'hui.
        </p>
        <a href="{{ route('auth.register') }}"
           class="btn btn-warning btn-lg fw-semibold px-5">
            Créer mon profil <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</section>

@endsection