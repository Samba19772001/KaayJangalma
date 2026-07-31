@extends('layouts.app')
@section('title', 'Contact et Support')

@section('content')

{{-- ── Hero ── --}}
<section style="background:linear-gradient(135deg,#1B7A4A,#0f4d2e);padding:4rem 0">
    <div class="container text-center text-white">
        <h1 class="fw-bold mb-2">Contact et Support</h1>
        <p class="opacity-75 mb-0">Notre équipe est là pour vous aider</p>
    </div>
</section>

<div class="container py-5">
    <div class="row g-5">

        {{-- ── Formulaire ── --}}
        <div class="col-lg-7">
            <div class="card p-4 p-md-5">
                <h4 class="fw-bold mb-1">Envoyez-nous un message</h4>
                <p class="text-muted small mb-4">Nous répondons sous 24h du lundi au samedi</p>

                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', Auth::user()?->name) }}"
                                       placeholder="Ex : Ibrahima Diallo" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', Auth::user()?->email) }}"
                                       placeholder="exemple@mail.com" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Sujet <span class="text-danger">*</span></label>
                            <select name="subject" class="form-select @error('subject') is-invalid @enderror" required>
                                <option value="">Choisir un sujet</option>
                                <option value="Problème technique" {{ old('subject') === 'Problème technique' ? 'selected' : '' }}>Problème technique</option>
                                <option value="Question sur un abonnement" {{ old('subject') === 'Question sur un abonnement' ? 'selected' : '' }}>Question sur un abonnement</option>
                                <option value="Signalement d'un utilisateur" {{ old('subject') === 'Signalement d\'un utilisateur' ? 'selected' : '' }}>Signalement d'un utilisateur</option>
                                <option value="Demande de partenariat" {{ old('subject') === 'Demande de partenariat' ? 'selected' : '' }}>Demande de partenariat</option>
                                <option value="Suggestion d'amélioration" {{ old('subject') === 'Suggestion d\'amélioration' ? 'selected' : '' }}>Suggestion d'amélioration</option>
                                <option value="Autre" {{ old('subject') === 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                            <textarea name="message" rows="6"
                                      class="form-control @error('message') is-invalid @enderror"
                                      placeholder="Décrivez votre problème ou votre question en détail..."
                                      required>{{ old('message') }}</textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-kj fw-semibold px-5 py-2">
                                <i class="bi bi-send me-2"></i>Envoyer le message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Infos contact ── --}}
        <div class="col-lg-5">

            {{-- Coordonnées --}}
            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-4">Nos coordonnées</h5>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:45px;height:45px;background:rgba(27,122,74,.1)">
                            <i class="bi bi-whatsapp fs-5" style="color:#25D366"></i>
                        </div>
                        <div>
                            <div class="fw-semibold small">WhatsApp</div>
                            <a href="https://wa.me/221782921001" target="_blank"
                               class="text-muted small text-decoration-none">
                                +221 78 292 10 01
                            </a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:45px;height:45px;background:rgba(27,122,74,.1)">
                            <i class="bi bi-envelope fs-5" style="color:var(--kj-green)"></i>
                        </div>
                        <div>
                            <div class="fw-semibold small">Email</div>
                            <a href="mailto:contact@kaayjangalma.sn"
                               class="text-muted small text-decoration-none">
                                contact@kaayjangalma.sn
                            </a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:45px;height:45px;background:rgba(27,122,74,.1)">
                            <i class="bi bi-geo-alt fs-5" style="color:var(--kj-green)"></i>
                        </div>
                        <div>
                            <div class="fw-semibold small">Adresse</div>
                            <span class="text-muted small">Dakar, Sénégal</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:45px;height:45px;background:rgba(27,122,74,.1)">
                            <i class="bi bi-clock fs-5" style="color:var(--kj-green)"></i>
                        </div>
                        <div>
                            <div class="fw-semibold small">Horaires</div>
                            <span class="text-muted small">Lun - Sam : 8h00 - 18h00</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FAQ --}}
            <div class="card p-4">
                <h5 class="fw-bold mb-4">Questions fréquentes</h5>
                <div class="accordion accordion-flush" id="faqAccordion">

                    @foreach([
                        ['q' => 'Comment trouver un professeur ?', 'a' => 'Utilisez la barre de recherche en haut de la page. Filtrez par matière, niveau, ville et budget pour trouver le professeur idéal.'],
                        ['q' => 'Comment devenir professeur sur KaayJangalma ?', 'a' => 'Inscrivez-vous en tant que professeur, complétez votre profil et soumettez vos documents justificatifs. Notre équipe validera votre compte sous 48h.'],
                        ['q' => 'Le service est-il gratuit ?', 'a' => 'L\'inscription et la recherche sont gratuites. Les professeurs peuvent souscrire à un abonnement Premium pour plus de visibilité.'],
                        ['q' => 'Comment contacter un professeur ?', 'a' => 'Vous pouvez envoyer un message via la messagerie interne ou contacter directement le professeur sur WhatsApp depuis son profil.'],
                        ['q' => 'Comment signaler un problème ?', 'a' => 'Utilisez ce formulaire de contact ou envoyez-nous un message WhatsApp. Nous traitons toutes les signalements sous 24h.'],
                    ] as $index => $faq)
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-semibold small px-0"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faq{{ $index }}">
                                {{ $faq['q'] }}
                            </button>
                        </h2>
                        <div id="faq{{ $index }}" class="accordion-collapse collapse"
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body px-0 text-muted small">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</div>

@endsection