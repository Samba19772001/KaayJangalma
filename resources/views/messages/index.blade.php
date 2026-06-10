@extends('layouts.app')
@section('title', 'Messagerie')

@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand"><i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma</a>
    @if(Auth::user()->isParent())
        <a href="{{ route('parent.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
        <a href="{{ route('parent.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
        <a href="{{ route('search.index') }}"><i class="bi bi-search"></i> Rechercher</a>
        <a href="{{ route('parent.favorites') }}"><i class="bi bi-heart"></i> Favoris</a>
        <a href="{{ route('parent.requests') }}"><i class="bi bi-send"></i> Mes demandes</a>
        <a href="{{ route('messages.index') }}" class="active"><i class="bi bi-chat"></i> Messages</a>
        <a href="{{ route('parent.announcements') }}"><i class="bi bi-megaphone"></i> Mes annonces</a>
    @else
        <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
        <a href="{{ route('teacher.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
        <a href="{{ route('teacher.requests') }}"><i class="bi bi-inbox"></i> Demandes</a>
        <a href="{{ route('messages.index') }}" class="active"><i class="bi bi-chat"></i> Messages</a>
        <a href="{{ route('teacher.stats') }}"><i class="bi bi-bar-chart"></i> Statistiques</a>
        <a href="{{ route('teacher.subscription') }}"><i class="bi bi-star"></i> Abonnement Premium</a>
    @endif
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
                @if(Auth::user()->isParent())
                    <a href="{{ route('parent.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
                    <a href="{{ route('parent.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
                    <a href="{{ route('search.index') }}"><i class="bi bi-search"></i> Rechercher</a>
                    <a href="{{ route('parent.favorites') }}"><i class="bi bi-heart"></i> Favoris</a>
                    <a href="{{ route('parent.requests') }}"><i class="bi bi-send"></i> Mes demandes</a>
                    <a href="{{ route('messages.index') }}" class="active"><i class="bi bi-chat"></i> Messages</a>
                    <a href="{{ route('parent.announcements') }}"><i class="bi bi-megaphone"></i> Mes annonces</a>
                @else
                    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
                    <a href="{{ route('teacher.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
                    <a href="{{ route('teacher.requests') }}"><i class="bi bi-inbox"></i> Demandes</a>
                    <a href="{{ route('messages.index') }}" class="active"><i class="bi bi-chat"></i> Messages</a>
                    <a href="{{ route('teacher.stats') }}"><i class="bi bi-bar-chart"></i> Statistiques</a>
                    <a href="{{ route('teacher.subscription') }}"><i class="bi bi-star"></i> Abonnement Premium</a>
                @endif
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
                <i class="bi bi-chat me-2" style="color:var(--kj-green)"></i>
                Messagerie
            </h4>

            @if($conversations->isEmpty())
                <div class="card text-center py-5">
                    <i class="bi bi-chat fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">Aucune conversation</h5>
                    @if(Auth::user()->isParent())
                        <p class="text-muted small">Trouvez un professeur et envoyez-lui un message.</p>
                        <a href="{{ route('search.index') }}" class="btn btn-kj px-4">
                            <i class="bi bi-search me-2"></i>Trouver un professeur
                        </a>
                    @endif
                </div>
            @else
                <div class="card">
                    @foreach($conversations as $conv)
                        @php
                            $other      = Auth::user()->isParent() ? $conv->teacher : $conv->parent;
                            $otherName  = $other->user->name;
                            $lastMsg    = $conv->messages->first();
                            $unread     = $conv->messages->where('is_read', false)
                                                         ->where('sender_id', '!=', Auth::id())
                                                         ->count();
                        @endphp
                        <a href="{{ route('messages.show', $conv->id) }}"
                           class="text-decoration-none">
                            <div class="d-flex align-items-center gap-3 p-3 border-bottom
                                        {{ $unread ? 'bg-light' : '' }}"
                                 style="transition: background .2s"
                                 onmouseover="this.style.background='#f8f9fa'"
                                 onmouseout="this.style.background='{{ $unread ? '#f8f9fa' : '' }}'">

                                {{-- Avatar --}}
                                @if($other->photo)
                                    <img src="{{ asset('storage/'.$other->photo) }}"
                                         class="rounded-circle flex-shrink-0"
                                         style="width:50px;height:50px;object-fit:cover;border:2px solid var(--kj-green)">
                                @else
                                    <div class="rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center text-white fw-bold"
                                         style="width:50px;height:50px;background:var(--kj-green);font-size:1.2rem">
                                        {{ strtoupper(substr($otherName, 0, 1)) }}
                                    </div>
                                @endif

                                {{-- Contenu --}}
                                <div class="flex-grow-1 min-width-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-dark {{ $unread ? 'fw-bolder' : '' }}">
                                            {{ $otherName }}
                                        </span>
                                        @if($conv->last_message_at)
                                            <span class="text-muted small">
                                                {{ $conv->last_message_at->diffForHumans() }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="text-muted small mb-0 text-truncate" style="max-width:300px">
                                            {{ $lastMsg ? $lastMsg->body : 'Aucun message' }}
                                        </p>
                                        @if($unread)
                                            <span class="badge rounded-pill ms-2"
                                                  style="background:var(--kj-green)">
                                                {{ $unread }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection