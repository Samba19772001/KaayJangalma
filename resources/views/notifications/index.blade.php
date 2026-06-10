@extends('layouts.app')
@section('title', 'Notifications')

@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand"><i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma</a>
    @if(Auth::user()->isParent())
        <a href="{{ route('parent.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
        <a href="{{ route('parent.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
        <a href="{{ route('search.index') }}"><i class="bi bi-search"></i> Rechercher</a>
        <a href="{{ route('parent.favorites') }}"><i class="bi bi-heart"></i> Favoris</a>
        <a href="{{ route('parent.requests') }}"><i class="bi bi-send"></i> Mes demandes</a>
        <a href="{{ route('messages.index') }}"><i class="bi bi-chat"></i> Messages</a>
        <a href="{{ route('notifications.index') }}" class="active"><i class="bi bi-bell"></i> Notifications</a>
    @elseif(Auth::user()->isTeacher())
        <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
        <a href="{{ route('teacher.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
        <a href="{{ route('teacher.requests') }}"><i class="bi bi-inbox"></i> Demandes</a>
        <a href="{{ route('messages.index') }}"><i class="bi bi-chat"></i> Messages</a>
        <a href="{{ route('notifications.index') }}" class="active"><i class="bi bi-bell"></i> Notifications</a>
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
                    <a href="{{ route('messages.index') }}"><i class="bi bi-chat"></i> Messages</a>
                    <a href="{{ route('notifications.index') }}" class="active"><i class="bi bi-bell"></i> Notifications</a>
                @elseif(Auth::user()->isTeacher())
                    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
                    <a href="{{ route('teacher.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
                    <a href="{{ route('teacher.requests') }}"><i class="bi bi-inbox"></i> Demandes</a>
                    <a href="{{ route('messages.index') }}"><i class="bi bi-chat"></i> Messages</a>
                    <a href="{{ route('notifications.index') }}" class="active"><i class="bi bi-bell"></i> Notifications</a>
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">
                    <i class="bi bi-bell me-2" style="color:var(--kj-green)"></i>
                    Notifications
                </h4>
                @if($notifications->total() > 0)
                    <form action="{{ route('notifications.readAll') }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-check2-all me-1"></i>Tout marquer comme lu
                        </button>
                    </form>
                @endif
            </div>

            @if($notifications->isEmpty())
                <div class="card text-center py-5">
                    <i class="bi bi-bell fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">Aucune notification</h5>
                </div>
            @else
                <div class="card">
                    @foreach($notifications as $notif)
                        @php
                            $data    = json_decode($notif->data, true);
                            $isRead  = !is_null($notif->read_at);
                            $icons   = [
                                'new_message'       => ['bi-chat-fill',        'text-primary'],
                                'new_request'       => ['bi-send-fill',        'text-success'],
                                'profile_validated' => ['bi-patch-check-fill', 'text-success'],
                                'new_review'        => ['bi-star-fill',        'text-warning'],
                                'subscription_expiry'=> ['bi-star',            'text-danger'],
                            ];
                            [$icon, $color] = $icons[$notif->type] ?? ['bi-bell-fill', 'text-muted'];
                        @endphp
                        <div class="d-flex align-items-start gap-3 p-3 border-bottom
                                    {{ !$isRead ? 'bg-light' : '' }}">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:42px;height:42px;background:{{ !$isRead ? 'rgba(27,122,74,.1)' : '#f0f0f0' }}">
                                <i class="bi {{ $icon }} {{ $color }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold small {{ !$isRead ? '' : 'text-muted' }}">
                                    {{ $data['message'] ?? 'Notification' }}
                                </div>
                                <div class="text-muted" style="font-size:.75rem">
                                    {{ $notif->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @if(isset($data['conversation_id']))
                                    <a href="{{ route('messages.show', $data['conversation_id']) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                @endif
                                @if(!$isRead)
                                    <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-kj">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection