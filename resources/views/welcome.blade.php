@extends('layouts.app')
@section('title', 'Accueil')

@push('styles')
<style>
    /* ── Hero ── */
    .hero-section {
        background: linear-gradient(135deg, #1B7A4A 0%, #0f4d2e 100%);
        min-height: 580px;
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 600px;
        height: 600px;
        background: rgba(255,255,255,.04);
        border-radius: 50%;
    }
    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 400px;
        height: 400px;
        background: rgba(255,255,255,.03);
        border-radius: 50%;
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: rgba(255,255,255,.15);
        color: #fff;
        padding: .4rem 1rem;
        border-radius: 20px;
        font-size: .85rem;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,.2);
        margin-bottom: 1.5rem;
    }
    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.15;
        margin-bottom: 1.2rem;
    }
    .hero-title span { color: var(--kj-yellow); }
    .hero-subtitle {
        font-size: 1.1rem;
        color: rgba(255,255,255,.85);
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    .hero-search {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
    }
    .hero-stats {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
    }
    .hero-stat-number {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--kj-yellow);
    }
    .hero-stat-label {
        font-size: .8rem;
        color: rgba(255,255,255,.7);
    }

    /* ── Sections ── */
    .section-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1a1a1a;
    }
    .section-subtitle { color: #6c757d; }

    /* ── How it works ── */
    .step-card {
        text-align: center;
        padding: 2rem 1.5rem;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 20px rgba(0,0,0,.06);
        transition: transform .2s;
        position: relative;
    }
    .step-card:hover { transform: translateY(-6px); }
    .step-number {
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 32px;
        height: 32px;
        background: var(--kj-yellow);
        color: #333;
        border-radius: 50%;
        font-weight: 800;
        font-size: .9rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .step-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--kj-green), #27ae60);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 1.2rem;
    }

    /* ── Subjects ── */
    .subject-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.2rem;
        text-align: center;
        text-decoration: none;
        transition: all .2s;
        border: 2px solid transparent;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        display: block;
    }
    .subject-card:hover {
        border-color: var(--kj-green);
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(27,122,74,.15);
    }
    .subject-icon {
        font-size: 2rem;
        margin-bottom: .5rem;
        display: block;
        color: var(--kj-green);
    }
    .subject-name {
        font-size: .85rem;
        font-weight: 600;
        color: #333;
    }

    /* ── Teachers ── */
    .teacher-feature-card {
        border-radius: 16px;
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,.07);
    }
    .teacher-feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0,0,0,.12);
    }

    /* ── Testimonials ── */
    .testimonial-card {
        background: #fff;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,.06);
        position: relative;
    }
    .testimonial-card::before {
        content: '"';
        position: absolute;
        top: 1rem;
        left: 1.5rem;
        font-size: 4rem;
        color: var(--kj-green);
        opacity: .2;
        font-family: Georgia, serif;
        line-height: 1;
    }

    /* ── CTA ── */
    .cta-section {
        background: linear-gradient(135deg, #1B7A4A, #0f4d2e);
        border-radius: 24px;
        padding: 4rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .cta-section::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255,255,255,.05);
        border-radius: 50%;
    }

    @media (max-width: 767px) {
        .hero-title { font-size: 2rem; }
        .hero-stats { gap: 1rem; }
        .hero-stat-number { font-size: 1.4rem; }
    }
</style>
@endpush

@section('content')

{{-- ── HERO ── --}}
<section class="hero-section d-flex align-items-center">
    <div class="container py-5 position-relative" style="z-index:1">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-badge">
                    <i class="bi bi-patch-check-fill text-warning"></i>
                    Plateforme #1 de cours à domicile au Sénégal
                </div>
                <h1 class="hero-title">
                    Trouvez le professeur
                    <span>idéal</span>
                    pour votre enfant
                </h1>
                <p class="hero-subtitle">
                    KaayJangalma connecte les parents avec les meilleurs enseignants
                    qualifiés partout au Sénégal. Simple, rapide et fiable.
                </p>
                <div class="hero-stats">
                    <div>
                        <div class="hero-stat-number">500+</div>
                        <div class="hero-stat-label">Professeurs vérifiés</div>
                    </div>
                    <div>
                        <div class="hero-stat-number">1200+</div>
                        <div class="hero-stat-label">Familles satisfaites</div>
                    </div>
                    <div>
                        <div class="hero-stat-number">15+</div>
                        <div class="hero-stat-label">Villes couvertes</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-search">
                    <h5 class="fw-bold mb-3" style="color:var(--kj-green)">
                        <i class="bi bi-search me-2"></i>Trouver un professeur
                    </h5>
                    <form action="{{ route('search.index') }}" method="GET">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <select name="subject_id" class="form-select">
                                    <option value="">Toutes les matières</option>
                                    @foreach(\App\Models\Subject::where('is_active', true)->get() as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="level" class="form-select">
                                    <option value="">Tous les niveaux</option>
                                    <option value="primary">Primaire</option>
                                    <option value="middle">Collège</option>
                                    <option value="high">Lycée</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <input type="text" name="city" class="form-control"
                                       placeholder="Votre ville (ex : Dakar, Touba...)">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-kj w-100 py-2 fw-semibold">
                                    <i class="bi bi-search me-2"></i>Rechercher un professeur
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── COMMENT ÇA MARCHE ── --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Comment ça marche ?</h2>
            <p class="section-subtitle">Trouvez un professeur en 3 étapes simples</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Recherchez</h5>
                    <p class="text-muted small mb-0">
                        Filtrez par matière, niveau, ville et budget pour trouver
                        le professeur idéal près de chez vous.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Contactez</h5>
                    <p class="text-muted small mb-0">
                        Envoyez un message ou contactez directement via WhatsApp.
                        Discutez des détails et planifiez les cours.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon">
                        <i class="bi bi-star"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Évaluez</h5>
                    <p class="text-muted small mb-0">
                        Après les cours, laissez un avis pour aider
                        la communauté à choisir les meilleurs enseignants.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── MATIÈRES ── --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Matières disponibles</h2>
            <p class="section-subtitle">Des professeurs qualifiés pour chaque matière</p>
        </div>
        <div class="row g-3 justify-content-center">
            @foreach([
                ['icon' => 'bi-calculator',      'name' => 'Mathématiques',   'subject' => 1],
                ['icon' => 'bi-lightning-charge', 'name' => 'Physique',        'subject' => 2],
                ['icon' => 'bi-droplet',          'name' => 'Chimie',          'subject' => 3],
                ['icon' => 'bi-tree',             'name' => 'SVT',             'subject' => 4],
                ['icon' => 'bi-book',             'name' => 'Français',        'subject' => 5],
                ['icon' => 'bi-translate',        'name' => 'Anglais',         'subject' => 6],
                ['icon' => 'bi-moon-stars',       'name' => 'Arabe',           'subject' => 7],
                ['icon' => 'bi-globe',            'name' => 'Histoire-Géo',    'subject' => 8],
                ['icon' => 'bi-lightbulb',        'name' => 'Philosophie',     'subject' => 9],
                ['icon' => 'bi-laptop',           'name' => 'Informatique',    'subject' => 10],
            ] as $item)
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('search.index', ['subject_id' => $item['subject']]) }}"
                   class="subject-card">
                    <i class="bi {{ $item['icon'] }} subject-icon"></i>
                    <div class="subject-name">{{ $item['name'] }}</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── AVANTAGES ── --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="section-title mb-4">
                    Pourquoi choisir<br>
                    <span style="color:var(--kj-green)">KaayJangalma ?</span>
                </h2>
                <div class="d-flex flex-column gap-3">
                    @foreach([
                        ['icon' => 'bi-patch-check-fill', 'color' => '#1B7A4A', 'title' => 'Professeurs vérifiés', 'desc' => 'Tous nos enseignants sont vérifiés et leurs documents validés par notre équipe.'],
                        ['icon' => 'bi-shield-check',     'color' => '#2980b9', 'title' => 'Sécurisé et fiable',   'desc' => 'Vos données sont protégées et les échanges se font en toute sécurité.'],
                        ['icon' => 'bi-star-fill',        'color' => '#e67e22', 'title' => 'Avis authentiques',    'desc' => 'Les avis sont laissés uniquement par des parents ayant suivi des cours.'],
                        ['icon' => 'bi-whatsapp',         'color' => '#25D366', 'title' => 'Contact facile',       'desc' => 'Contactez les professeurs par messagerie interne ou directement sur WhatsApp.'],
                    ] as $item)
                    <div class="d-flex gap-3 align-items-start">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:50px;height:50px;background:{{ $item['color'] }}20">
                            <i class="bi {{ $item['icon'] }} fs-5" style="color:{{ $item['color'] }}"></i>
                        </div>
                        <div>
                            <div class="fw-bold">{{ $item['title'] }}</div>
                            <div class="text-muted small">{{ $item['desc'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="card p-3 text-center h-100">
                            <div class="fw-bold fs-2" style="color:var(--kj-green)">500+</div>
                            <div class="text-muted small">Professeurs vérifiés</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-3 text-center h-100">
                            <div class="fw-bold fs-2" style="color:#2980b9">1200+</div>
                            <div class="text-muted small">Familles satisfaites</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-3 text-center h-100">
                            <div class="fw-bold fs-2" style="color:#e67e22">4.8/5</div>
                            <div class="text-muted small">Note moyenne</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-3 text-center h-100">
                            <div class="fw-bold fs-2" style="color:#8e44ad">15+</div>
                            <div class="text-muted small">Villes couvertes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── TÉMOIGNAGES ── --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Ce que disent nos utilisateurs</h2>
            <p class="section-subtitle">Des milliers de familles nous font confiance</p>
        </div>
        <div class="row g-4">
            @foreach([
                ['name' => 'Aminata Diallo',  'role' => 'Parent, Dakar',   'stars' => 5, 'text' => 'Grâce à KaayJangalma, j\'ai trouvé un excellent professeur de Mathématiques pour mon fils en Terminale. Il a eu son BAC avec mention !'],
                ['name' => 'Moussa Sarr',     'role' => 'Parent, Touba',   'stars' => 5, 'text' => 'Service rapide et efficace. Le professeur que j\'ai trouvé est très sérieux et ponctuel. Je recommande vivement.'],
                ['name' => 'Fatou Ndiaye',    'role' => 'Parent, Thiès',   'stars' => 5, 'text' => 'Très bonne plateforme ! J\'ai contacté 3 professeurs et j\'ai trouvé celui qui correspond parfaitement à mes besoins.'],
            ] as $t)
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="stars mb-3 ps-3">
                        @for($i = 0; $i < $t['stars']; $i++)
                            <i class="bi bi-star-fill" style="color:var(--kj-yellow)"></i>
                        @endfor
                    </div>
                    <p class="text-muted ps-3" style="font-size:.95rem;line-height:1.6">
                        {{ $t['text'] }}
                    </p>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width:40px;height:40px;background:var(--kj-green);font-size:.9rem">
                            {{ strtoupper(substr($t['name'], 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-semibold small">{{ $t['name'] }}</div>
                            <div class="text-muted" style="font-size:.75rem">{{ $t['role'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA DOUBLE ── --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="cta-section">
            <div class="position-relative" style="z-index:1">
                <h2 class="fw-bold text-white mb-2 fs-2">
                    Prêt à commencer ?
                </h2>
                <p class="text-white opacity-75 mb-4">
                    Rejoignez des milliers de familles qui font confiance à KaayJangalma
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('search.index') }}"
                       class="btn btn-warning btn-lg fw-semibold px-4">
                        <i class="bi bi-search me-2"></i>Trouver un professeur
                    </a>
                    <a href="{{ route('auth.register') }}"
                       class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-person-workspace me-2"></i>Je suis professeur
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection