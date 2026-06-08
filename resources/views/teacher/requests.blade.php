@extends('layouts.app')
@section('title', 'Mes demandes')

@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand"><i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma</a>
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
    <a href="{{ route('teacher.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
    <a href="{{ route('teacher.requests') }}" class="active"><i class="bi bi-inbox"></i> Demandes</a>
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
                <a href="{{ route('teacher.profile') }}"><i class="bi bi-person-circle"></i> Mon profil</a>
                <a href="{{ route('teacher.requests') }}" class="active"><i class="bi bi-inbox"></i> Demandes</a>
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
            <h4 class="fw-bold mb-4">
                <i class="bi bi-inbox me-2" style="color:var(--kj-green)"></i>
                Demandes de cours reçues
            </h4>

            @if($requests->isEmpty())
                <div class="card text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">Aucune demande reçue</h5>
                    <p class="text-muted small">Complétez votre profil pour attirer plus de parents.</p>
                    <div>
                        <a href="{{ route('teacher.profile') }}" class="btn btn-kj px-4">Compléter mon profil</a>
                    </div>
                </div>
            @else
                <div class="row g-3">
                    @php
                        $levels = ['primary'=>'Primaire','middle'=>'Collège','high'=>'Lycée'];
                    @endphp
                    @foreach($requests as $req)
                        <div class="col-md-6">
                            <div class="card p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <div class="fw-bold">{{ $req->parent->user->name }}</div>
                                        <div class="small text-muted">{{ $req->created_at->format('d/m/Y à H:i') }}</div>
                                    </div>
                                    @php
                                        $badges = [
                                            'sent'      => ['bg-info text-dark','Envoyée'],
                                            'accepted'  => ['bg-success','Acceptée'],
                                            'refused'   => ['bg-danger','Refusée'],
                                            'completed' => ['bg-secondary','Terminée'],
                                        ];
                                        [$color, $label] = $badges[$req->status] ?? ['bg-secondary','—'];
                                    @endphp
                                    <span class="badge {{ $color }}">{{ $label }}</span>
                                </div>
                                <div class="d-flex gap-3 mb-2 small">
                                    <span><i class="bi bi-book me-1 text-muted"></i>{{ $req->subject->name }}</span>
                                    <span><i class="bi bi-mortarboard me-1 text-muted"></i>{{ $levels[$req->level] ?? $req->level }}</span>
                                    @if($req->hours)
                                        <span><i class="bi bi-clock me-1 text-muted"></i>{{ $req->hours }}h</span>
                                    @endif
                                </div>
                                @if($req->address)
                                    <div class="small text-muted mb-2">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $req->address }}
                                    </div>
                                @endif
                                @if($req->message)
                                    <p class="small bg-light rounded p-2 mb-2">{{ $req->message }}</p>
                                @endif
                                @if($req->status === 'sent')
                                    <div class="d-flex gap-2 mt-2">
                                        <form action="{{ route('teacher.requests.update', $req->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="accepted">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-check-lg me-1"></i>Accepter
                                            </button>
                                        </form>
                                        <form action="{{ route('teacher.requests.update', $req->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="refused">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-x-lg me-1"></i>Refuser
                                            </button>
                                        </form>
                                    </div>
                                @elseif($req->status === 'accepted')
                                    <form action="{{ route('teacher.requests.update', $req->id) }}" method="POST" class="mt-2">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="btn btn-sm btn-secondary">
                                            <i class="bi bi-check2-all me-1"></i>Marquer comme terminé
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection