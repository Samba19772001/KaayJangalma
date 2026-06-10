@extends('layouts.app')
@section('title', 'Conversation')

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

@push('styles')
<style>
    .messages-container {
        height: 500px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: .75rem;
        padding: 1.5rem;
        background: #f8f9fa;
    }
    .message-bubble {
        max-width: 70%;
        padding: .75rem 1rem;
        border-radius: 18px;
        font-size: .9rem;
        line-height: 1.4;
    }
    .message-mine {
        background: var(--kj-green);
        color: #fff;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
    }
    .message-other {
        background: #fff;
        color: #333;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
    }
    .message-time {
        font-size: .72rem;
        opacity: .7;
        margin-top: .3rem;
    }
    @media (max-width: 767px) {
        .messages-container { height: 350px; }
        .message-bubble { max-width: 85%; }
    }
</style>
@endpush

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

            {{-- Header conversation --}}
            @php
                $other     = Auth::user()->isParent() ? $conversation->teacher : $conversation->parent;
                $otherName = $other->user->name;
            @endphp

            <div class="card" style="max-width:800px">

                {{-- Barre supérieure --}}
                <div class="card-header bg-white py-3 d-flex align-items-center gap-3">
                    <a href="{{ route('messages.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    @if($other->photo)
                        <img src="{{ asset('storage/'.$other->photo) }}"
                             class="rounded-circle"
                             style="width:42px;height:42px;object-fit:cover;border:2px solid var(--kj-green)">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width:42px;height:42px;background:var(--kj-green);font-size:1rem">
                            {{ strtoupper(substr($otherName, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="fw-bold">{{ $otherName }}</div>
                        <div class="small text-muted">
                            @if(Auth::user()->isParent())
                                Professeur
                            @else
                                Parent
                            @endif
                        </div>
                    </div>
                    @if(Auth::user()->isParent() && $conversation->teacher->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $conversation->teacher->whatsapp) }}"
                           target="_blank"
                           class="btn btn-sm btn-success ms-auto">
                            <i class="bi bi-whatsapp me-1"></i>WhatsApp
                        </a>
                    @endif
                </div>

                {{-- Messages --}}
                <div class="messages-container" id="messagesContainer">
                    @forelse($messages as $message)
                        @php $isMine = $message->sender_id === Auth::id(); @endphp
                        <div class="d-flex flex-column {{ $isMine ? 'align-items-end' : 'align-items-start' }}">
                            <div class="message-bubble {{ $isMine ? 'message-mine' : 'message-other' }}">
                                {{ $message->body }}
                                <div class="message-time text-end">
                                    {{ $message->created_at->format('H:i') }}
                                    @if($isMine)
                                        <i class="bi bi-check{{ $message->is_read ? '2' : '' }} ms-1"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-chat fs-1 d-block mb-2"></i>
                            Commencez la conversation !
                        </div>
                    @endforelse
                </div>

                {{-- Zone de saisie --}}
                <div class="card-footer bg-white p-3">
                    <form action="{{ route('messages.send', $conversation->id) }}" method="POST">
                        @csrf
                        <div class="d-flex gap-2">
                            <input type="text" name="body"
                                   class="form-control"
                                   placeholder="Écrivez votre message..."
                                   autocomplete="off" required>
                            <button type="submit" class="btn btn-kj px-3">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Scroll vers le bas automatiquement
    const container = document.getElementById('messagesContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
</script>
@endpush