@extends('layouts.app')
@section('title', 'Politique de confidentialité')

@section('content')

<section style="background:linear-gradient(135deg,#1B7A4A,#0f4d2e);padding:4rem 0">
    <div class="container text-center text-white">
        <h1 class="fw-bold mb-2">Politique de confidentialité</h1>
        <p class="opacity-75 mb-0">Dernière mise à jour : {{ date('d/m/Y') }}</p>
    </div>
</section>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4 p-md-5">

                <div class="alert alert-light border mb-4 small">
                    <i class="bi bi-shield-check me-2" style="color:var(--kj-green)"></i>
                    KaayJangalma s'engage à protéger la vie privée de ses utilisateurs.
                    Cette politique explique comment nous collectons, utilisons et protégeons vos données.
                </div>

                @foreach([
                    [
                        'title' => '1. Données collectées',
                        'content' => 'Nous collectons les informations suivantes lors de votre inscription et utilisation de la plateforme :
                        <ul>
                            <li>Informations d\'identité : nom, prénom, photo de profil</li>
                            <li>Coordonnées : numéro de téléphone, adresse email</li>
                            <li>Informations de localisation : ville, quartier, région</li>
                            <li>Informations professionnelles (pour les professeurs) : diplômes, expérience, documents justificatifs</li>
                            <li>Données d\'utilisation : messages échangés, demandes de cours, avis laissés</li>
                            <li>Données de paiement : références de transactions (nous ne stockons pas les données bancaires complètes)</li>
                        </ul>'
                    ],
                    [
                        'title' => '2. Utilisation des données',
                        'content' => 'Vos données personnelles sont utilisées pour :
                        <ul>
                            <li>Créer et gérer votre compte utilisateur</li>
                            <li>Faciliter la mise en relation entre parents et professeurs</li>
                            <li>Traiter les demandes de cours et les paiements</li>
                            <li>Envoyer des notifications liées à votre activité</li>
                            <li>Améliorer nos services et l\'expérience utilisateur</li>
                            <li>Prévenir les fraudes et assurer la sécurité de la plateforme</li>
                        </ul>'
                    ],
                    [
                        'title' => '3. Partage des données',
                        'content' => 'Nous ne vendons jamais vos données personnelles à des tiers. Vos données peuvent être partagées uniquement dans les cas suivants :
                        <ul>
                            <li>Entre parents et professeurs dans le cadre de leur mise en relation (profil public)</li>
                            <li>Avec nos prestataires de paiement (PayTech) pour le traitement des transactions</li>
                            <li>Avec les autorités compétentes si requis par la loi</li>
                        </ul>'
                    ],
                    [
                        'title' => '4. Conservation des données',
                        'content' => 'Vos données sont conservées pendant toute la durée de votre compte actif sur KaayJangalma. En cas de suppression de votre compte, vos données personnelles sont supprimées dans un délai de 30 jours, à l\'exception des données requises par la loi (données de transaction, etc.).'
                    ],
                    [
                        'title' => '5. Sécurité des données',
                        'content' => 'Nous mettons en œuvre des mesures de sécurité techniques et organisationnelles pour protéger vos données :
                        <ul>
                            <li>Chiffrement des mots de passe (hachage bcrypt)</li>
                            <li>Connexions sécurisées (HTTPS)</li>
                            <li>Accès restreint aux données selon les rôles</li>
                            <li>Sauvegardes régulières</li>
                        </ul>'
                    ],
                    [
                        'title' => '6. Vos droits',
                        'content' => 'Conformément à la loi sénégalaise sur la protection des données personnelles, vous disposez des droits suivants :
                        <ul>
                            <li>Droit d\'accès à vos données personnelles</li>
                            <li>Droit de rectification de vos données</li>
                            <li>Droit à l\'effacement de vos données</li>
                            <li>Droit d\'opposition au traitement de vos données</li>
                        </ul>
                        Pour exercer ces droits, contactez-nous à : <a href="mailto:contact@kaayjangalma.sn">contact@kaayjangalma.sn</a>'
                    ],
                    [
                        'title' => '7. Cookies',
                        'content' => 'KaayJangalma utilise des cookies essentiels pour le fonctionnement de la plateforme (session utilisateur, sécurité). Nous n\'utilisons pas de cookies publicitaires ou de tracking tiers.'
                    ],
                    [
                        'title' => '8. Contact',
                        'content' => 'Pour toute question relative à cette politique de confidentialité, contactez-nous :
                        <ul>
                            <li>Email : <a href="mailto:contact@kaayjangalma.sn">contact@kaayjangalma.sn</a></li>
                            <li>Via notre <a href="' . route('contact') . '">formulaire de contact</a></li>
                        </ul>'
                    ],
                ] as $section)
                <div class="mb-4">
                    <h5 class="fw-bold mb-3" style="color:var(--kj-green)">{{ $section['title'] }}</h5>
                    <div class="text-muted small" style="line-height:1.8">
                        {!! $section['content'] !!}
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</div>

@endsection