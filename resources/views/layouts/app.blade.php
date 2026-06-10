<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KaayJangalma') — Cours à domicile au Sénégal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --kj-green:  #1B7A4A;
            --kj-yellow: #F5C518;
            --kj-red:    #C0392B;
        }
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; }

        /* ── Navbar ── */
        .navbar-brand { font-weight: 800; font-size: 1.4rem; color: var(--kj-green) !important; }
        .nav-link:hover { color: var(--kj-green) !important; }

        /* ── Boutons ── */
        .btn-kj { background: var(--kj-green); color: #fff; border: none; }
        .btn-kj:hover { background: #145c37; color: #fff; }
        .btn-outline-kj { border: 2px solid var(--kj-green); color: var(--kj-green); background: transparent; }
        .btn-outline-kj:hover { background: var(--kj-green); color: #fff; }

        /* ── Sidebar desktop ── */
        .sidebar {
            background: var(--kj-green);
            min-height: 100vh;
            padding: 1.5rem 1rem;
        }
        .sidebar .brand {
            font-weight: 800;
            font-size: 1.2rem;
            color: #fff;
            text-decoration: none;
            display: block;
            margin-bottom: 2rem;
        }
        .sidebar a {
            color: rgba(255,255,255,.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .65rem 1rem;
            border-radius: 8px;
            margin-bottom: 4px;
            font-size: .95rem;
            transition: all .2s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,.15);
            color: #fff;
        }
        .sidebar .sidebar-divider {
            border-color: rgba(255,255,255,.2);
            margin: 1rem 0;
        }

        /* ── Sidebar mobile drawer ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,.5);
            z-index: 1040;
        }
        .sidebar-drawer {
            position: fixed;
            top: 0; left: -280px;
            width: 260px;
            height: 100%;
            background: var(--kj-green);
            z-index: 1045;
            padding: 1.5rem 1rem;
            transition: left .3s ease;
            overflow-y: auto;
        }
        .sidebar-drawer.open { left: 0; }
        .sidebar-drawer .brand {
            font-weight: 800;
            font-size: 1.2rem;
            color: #fff;
            text-decoration: none;
            display: block;
            margin-bottom: 2rem;
        }
        .sidebar-drawer a {
            color: rgba(255,255,255,.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .65rem 1rem;
            border-radius: 8px;
            margin-bottom: 4px;
            font-size: .95rem;
            transition: all .2s;
        }
        .sidebar-drawer a:hover, .sidebar-drawer a.active {
            background: rgba(255,255,255,.15);
            color: #fff;
        }
        .sidebar-drawer .sidebar-divider {
            border-color: rgba(255,255,255,.2);
            margin: 1rem 0;
        }
        .sidebar-mobile-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: var(--kj-green);
            color: #fff;
            border: none;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,.3);
            display: none;
            align-items: center;
            justify-content: center;
        }
        @media (max-width: 767px) {
            .sidebar-mobile-toggle { display: flex; }
        }

        /* ── Badges ── */
        .badge-verified { background: #d4edda; color: #155724; font-size: .72rem; padding: .3rem .6rem; border-radius: 20px; }
        .badge-premium  { background: #fff3cd; color: #856404; font-size: .72rem; padding: .3rem .6rem; border-radius: 20px; }

        /* ── Étoiles ── */
        .stars { color: var(--kj-yellow); }

        /* ── Cards ── */
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
        .card-header { border-radius: 12px 12px 0 0 !important; }

        /* ── Stats cards ── */
        .stat-card { border-radius: 12px; padding: 1.5rem; color: #fff; }
        .stat-card .stat-number { font-size: 2rem; font-weight: 700; }
        .stat-card .stat-label  { font-size: .85rem; opacity: .85; }

        /* ── Teacher card ── */
        .teacher-card { transition: transform .2s, box-shadow .2s; cursor: pointer; }
        .teacher-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
        .teacher-avatar { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; border: 3px solid var(--kj-green); }
        .teacher-avatar-placeholder {
            width: 70px; height: 70px; border-radius: 50%;
            background: var(--kj-green); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; font-weight: 700;
            border: 3px solid var(--kj-green);
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── Navbar ── --}}
<nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="bi bi-mortarboard-fill me-1" style="color:var(--kj-green)"></i>KaayJangalma
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('search.index') }}">
                        <i class="bi bi-search me-1"></i> Trouver un professeur
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                @guest
                    <a href="{{ route('auth.login') }}" class="btn btn-outline-kj btn-sm px-3">Connexion</a>
                    <a href="{{ route('auth.register') }}" class="btn btn-kj btn-sm px-3">Inscription</a>
                @else
                    {{-- Compteur notifications --}}
                    @php
                        $unreadNotifs = Auth::check()
                            ? \App\Models\Notification::where('user_id', Auth::id())
                                                    ->whereNull('read_at')
                                                    ->count()
                            : 0;
                        $unreadMessages = 0;
                        if (Auth::check()) {
                            if (Auth::user()->isParent() && Auth::user()->parentProfile) {
                                $unreadMessages = \App\Models\Conversation::where('parent_id', Auth::user()->parentProfile->id)
                                    ->withCount(['messages' => fn($q) => $q->where('is_read', false)->where('sender_id', '!=', Auth::id())])
                                    ->get()->sum('messages_count');
                            } elseif (Auth::user()->isTeacher() && Auth::user()->teacherProfile) {
                                $unreadMessages = \App\Models\Conversation::where('teacher_id', Auth::user()->teacherProfile->id)
                                    ->withCount(['messages' => fn($q) => $q->where('is_read', false)->where('sender_id', '!=', Auth::id())])
                                    ->get()->sum('messages_count');
                            }
                        }
                    @endphp

                    {{-- Bouton Messages --}}
                    @if(Auth::user()->isParent() || Auth::user()->isTeacher())
                        <a href="{{ route('messages.index') }}"
                        class="btn btn-sm btn-outline-secondary position-relative">
                            <i class="bi bi-chat"></i>
                            @if($unreadMessages > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    style="font-size:.6rem">
                                    {{ $unreadMessages }}
                                </span>
                            @endif
                        </a>
                    @endif

                    {{-- Bouton Notifications --}}
                    <a href="{{ route('notifications.index') }}"
                    class="btn btn-sm btn-outline-secondary position-relative">
                        <i class="bi bi-bell"></i>
                        @if($unreadNotifs > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                style="font-size:.6rem">
                                {{ $unreadNotifs }}
                            </span>
                        @endif
                    </a>

                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2"
                                data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            @if(Auth::user()->isParent())
                                <li><a class="dropdown-item" href="{{ route('parent.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Mon espace</a></li>
                                <li><a class="dropdown-item" href="{{ route('messages.index') }}"><i class="bi bi-chat me-2"></i>Messages</a></li>
                            @elseif(Auth::user()->isTeacher())
                                <li><a class="dropdown-item" href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Mon espace</a></li>
                                <li><a class="dropdown-item" href="{{ route('messages.index') }}"><i class="bi bi-chat me-2"></i>Messages</a></li>
                            @elseif(Auth::user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-check me-2"></i>Administration</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('notifications.index') }}">
                                <i class="bi bi-bell me-2"></i>Notifications
                                @if($unreadNotifs > 0)
                                    <span class="badge bg-danger ms-1">{{ $unreadNotifs }}</span>
                                @endif
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('auth.logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

{{-- ── Flash messages ── --}}
@if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show rounded-3">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif
@if(session('info'))
    <div class="container mt-3">
        <div class="alert alert-info alert-dismissible fade show rounded-3">
            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif
@if($errors->any())
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show rounded-3">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

{{-- ── Contenu principal ── --}}
@yield('content')

{{-- ── Sidebar mobile drawer ── --}}
@hasSection('sidebar_content')
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    <div class="sidebar-drawer" id="sidebarDrawer">
        @yield('sidebar_content')
    </div>
    <button class="sidebar-mobile-toggle" onclick="openSidebar()">
        <i class="bi bi-list"></i>
    </button>
@endif

{{-- ── Footer ── --}}
<footer class="mt-5 py-4 bg-white border-top">
    <div class="container text-center text-muted small">
        <p class="mb-1"><strong style="color:var(--kj-green)">KaayJangalma</strong> — La plateforme de cours à domicile au Sénégal</p>
        <p class="mb-0">© {{ date('Y') }} Tous droits réservés</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openSidebar() {
    document.getElementById('sidebarDrawer').classList.add('open');
    document.getElementById('sidebarOverlay').style.display = 'block';
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('sidebarDrawer').classList.remove('open');
    document.getElementById('sidebarOverlay').style.display = 'none';
    document.body.style.overflow = '';
}
</script>
@stack('scripts')
</body>
</html>