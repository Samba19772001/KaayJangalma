@extends('layouts.app')
@section('title', 'Conditions Générales d\'Utilisation')

@section('content')

<section style="background:linear-gradient(135deg,#1B7A4A,#0f4d2e);padding:4rem 0">
    <div class="container text-center text-white">
        <h1 class="fw-bold mb-2">Conditions Générales d'Utilisation</h1>
        <p class="opacity-75 mb-0">Dernière mise à jour : {{ date('d/m/Y') }}</p>
    </div>
</section>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4 p-md-5">

                <div class="alert alert-light border mb-4 small">
                    <i class="bi bi-file-text me-2" style="color:var(--kj-green)"></i>
                    En utilisant KaayJangalma, vous acceptez les présentes conditions générales d'utilisation.
                    Veuillez les lire attentivement avant de vous inscrire.
                </div>

                @foreach([
                    [
                        'title' => '1. Présentation de la plateforme',
                        'content' => 'KaayJangalma est une plateforme en ligne permettant la mise en relation entre des parents cherchant des professeurs à domicile et des enseignants proposant leurs services au Sénégal. KaayJangalma agit en tant qu\'intermédiaire et n\'est pas partie prenante dans les contrats conclus entre parents et professeurs.'
                    ],
                    [
                        'title' => '2. Inscription et compte utilisateur',
                        'content' => 'Pour utiliser les services de KaayJangalma, vous devez :
                        <ul>
                            <li>Avoir au moins 18 ans ou être représenté par un tuteur légal</li>
                            <li>Fournir des informations exactes et complètes lors de l\'inscription</li>
                            <li>Maintenir la confidentialité de vos identifiants de connexion</li>
                            <li>Notifier immédiatement KaayJangalma en cas d\'utilisation non autorisée de votre compte</li>
                        </ul>
                        KaayJangalma se réserve le droit de suspendre ou supprimer tout compte ne respectant pas ces conditions.'
                    ],
                    [
                        'title' => '3. Règles d\'utilisation',
                        'content' => 'Les utilisateurs s\'engagent à :
                        <ul>
                            <li>Fournir des informations véridiques sur leur identité et leurs qualifications</li>
                            <li>Respecter les autres utilisateurs de la plateforme</li>
                            <li>Ne pas publier de contenu offensant, trompeur ou illégal</li>
                            <li>Ne pas utiliser la plateforme à des fins commerciales non autorisées</li>
                            <li>Ne pas tenter de contourner les systèmes de sécurité de la plateforme</li>
                            <li>Signaler tout comportement suspect ou abusif à l\'équipe KaayJangalma</li>
                        </ul>'
                    ],
                    [
                        'title' => '4. Responsabilités des professeurs',
                        'content' => 'Les professeurs inscrits sur KaayJangalma s\'engagent à :
                        <ul>
                            <li>Fournir des documents justificatifs authentiques (CNI, diplômes, certificats)</li>
                            <li>Dispenser des cours de qualité conformes à leur profil</li>
                            <li>Respecter les engagements pris avec les parents</li>
                            <li>Informer rapidement en cas d\'empêchement</li>
                            <li>Maintenir un comportement professionnel et respectueux</li>
                        </ul>'
                    ],
                    [
                        'title' => '5. Responsabilités des parents',
                        'content' => 'Les parents inscrits sur KaayJangalma s\'engagent à :
                        <ul>
                            <li>Fournir un environnement sûr et approprié pour les cours à domicile</li>
                            <li>Respecter les horaires convenus avec le professeur</li>
                            <li>Honorer les engagements financiers pris avec le professeur</li>
                            <li>Laisser des avis honnêtes et constructifs</li>
                        </ul>'
                    ],
                    [
                        'title' => '6. Abonnement Premium',
                        'content' => 'L\'abonnement Premium est réservé aux professeurs et offre une visibilité accrue sur la plateforme. Les conditions de l\'abonnement sont :
                        <ul>
                            <li>Le paiement est non remboursable sauf en cas d\'erreur de notre part</li>
                            <li>L\'abonnement est activé après confirmation du paiement par notre équipe</li>
                            <li>KaayJangalma se réserve le droit de modifier les tarifs avec un préavis de 30 jours</li>
                            <li>En cas de violation des CGU, l\'abonnement peut être suspendu sans remboursement</li>
                        </ul>'
                    ],
                    [
                        'title' => '7. Propriété intellectuelle',
                        'content' => 'Tout le contenu de KaayJangalma (logo, design, textes, fonctionnalités) est protégé par les droits de propriété intellectuelle. Les utilisateurs conservent la propriété de leur contenu (photos, descriptions) mais accordent à KaayJangalma une licence d\'utilisation pour l\'affichage sur la plateforme.'
                    ],
                    [
                        'title' => '8. Limitation de responsabilité',
                        'content' => 'KaayJangalma agit en tant qu\'intermédiaire et ne peut être tenu responsable :
                        <ul>
                            <li>De la qualité des cours dispensés par les professeurs</li>
                            <li>Des litiges entre parents et professeurs</li>
                            <li>Des interruptions de service pour maintenance</li>
                            <li>Des pertes de données dues à des cas de force majeure</li>
                        </ul>'
                    ],
                    [
                        'title' => '9. Résiliation',
                        'content' => 'Vous pouvez supprimer votre compte à tout moment en contactant notre équipe. KaayJangalma se réserve le droit de suspendre ou supprimer tout compte en cas de :
                        <ul>
                            <li>Violation des présentes CGU</li>
                            <li>Comportement frauduleux ou abusif</li>
                            <li>Fourniture de fausses informations</li>
                        </ul>'
                    ],
                    [
                        'title' => '10. Droit applicable',
                        'content' => 'Les présentes CGU sont régies par le droit sénégalais. Tout litige relatif à l\'utilisation de KaayJangalma sera soumis à la juridiction compétente de Dakar, Sénégal. Pour toute question, contactez-nous à : <a href="mailto:contact@kaayjangalma.sn">contact@kaayjangalma.sn</a>'
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