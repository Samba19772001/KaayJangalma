@extends('layouts.app')
@section('title', 'Gestion des utilisateurs')

@section('sidebar_content')
    <a href="{{ route('home') }}" class="brand"><i class="bi bi-mortarboard-fill me-2"></i>KaayJangalma</a>
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
    <a href="{{ route('admin.users') }}" class="active"><i class="bi bi-people"></i> Utilisateurs</a>
    <a href="{{ route('admin.teachers') }}"><i class="bi bi-person-workspace"></i> Professeurs</a>
    <a href="{{ route('admin.subjects') }}"><i class="bi bi-book"></i> Matières</a>
    <a href="{{ route('admin.payments') }}"><i class="bi bi-credit-card"></i> Paiements</a>
    <a href="{{ route('admin.reviews') }}"><i class="bi bi-star"></i> Avis</a>
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
                <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
                <a href="{{ route('admin.users') }}" class="active"><i class="bi bi-people"></i> Utilisateurs</a>
                <a href="{{ route('admin.teachers') }}"><i class="bi bi-person-workspace"></i> Professeurs</a>
                <a href="{{ route('admin.subjects') }}"><i class="bi bi-book"></i> Matières</a>
                <a href="{{ route('admin.payments') }}"><i class="bi bi-credit-card"></i> Paiements</a>
                <a href="{{ route('admin.reviews') }}"><i class="bi bi-star"></i> Avis</a>
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
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h4 class="fw-bold mb-0">
                    <i class="bi bi-people me-2" style="color:var(--kj-green)"></i>
                    Gestion des utilisateurs
                </h4>
            </div>

            {{-- Filtres --}}
            <div class="card p-3 mb-4">
                <form action="{{ route('admin.users') }}" method="GET">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Rechercher</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                   placeholder="Nom ou téléphone..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Rôle</label>
                            <select name="role" class="form-select form-select-sm">
                                <option value="">Tous les rôles</option>
                                <option value="parent"  {{ request('role') === 'parent'  ? 'selected' : '' }}>Parents</option>
                                <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>Professeurs</option>
                                <option value="admin"   {{ request('role') === 'admin'   ? 'selected' : '' }}>Admins</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Statut</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Tous</option>
                                <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Bloqués</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-kj btn-sm w-100">
                                <i class="bi bi-search me-1"></i>Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Utilisateur</th>
                                <th>Téléphone</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="{{ $user->is_blocked ? 'table-danger' : '' }}">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center
                                                    text-white fw-bold flex-shrink-0"
                                             style="width:36px;height:36px;
                                                    background:{{ $user->is_blocked ? '#c0392b' : 'var(--kj-green)' }};
                                                    font-size:.85rem">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold small">{{ $user->name }}</div>
                                            <div class="text-muted" style="font-size:.75rem">
                                                {{ $user->email ?? '—' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="small">{{ $user->phone }}</td>
                                <td>
                                    @php
                                        $roleBadges = [
                                            'parent'  => ['bg-info text-dark', 'Parent'],
                                            'teacher' => ['bg-primary', 'Professeur'],
                                            'admin'   => ['bg-dark', 'Admin'],
                                        ];
                                        [$rc, $rl] = $roleBadges[$user->role] ?? ['bg-secondary', $user->role];
                                    @endphp
                                    <span class="badge {{ $rc }}">{{ $rl }}</span>
                                </td>
                                <td>
                                    @if($user->is_blocked)
                                        <span class="badge bg-danger">Suspendu</span>
                                        @if($user->block_reason)
                                            <div class="text-muted" style="font-size:.72rem">
                                                {{ Str::limit($user->block_reason, 30) }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="badge bg-success">Actif</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if(!$user->isAdmin())
                                        <div class="d-flex gap-1">
                                            {{-- Bloquer / Débloquer --}}
                                            <button class="btn btn-sm {{ $user->is_blocked ? 'btn-success' : 'btn-warning' }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#blockModal{{ $user->id }}">
                                                @if($user->is_blocked)
                                                    <i class="bi bi-unlock"></i>
                                                @else
                                                    <i class="bi bi-lock"></i>
                                                @endif
                                            </button>

                                            {{-- Supprimer --}}
                                            <button class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $user->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        {{-- Modal Bloquer/Débloquer --}}
                                        <div class="modal fade" id="blockModal{{ $user->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">
                                                            {{ $user->is_blocked ? 'Réactiver le compte' : 'Suspendre le compte' }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.users.block', $user->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <div class="modal-body">
                                                            <p class="small text-muted">
                                                                Utilisateur : <strong>{{ $user->name }}</strong>
                                                            </p>
                                                            @if(!$user->is_blocked)
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-semibold">
                                                                        Raison de la suspension
                                                                    </label>
                                                                    <textarea name="block_reason" class="form-control"
                                                                              rows="3"
                                                                              placeholder="Ex : Fausses informations, comportement abusif..."></textarea>
                                                                </div>
                                                            @else
                                                                <div class="alert alert-info small">
                                                                    Le compte sera réactivé et l'utilisateur pourra se reconnecter.
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Annuler</button>
                                                            <button type="submit"
                                                                    class="btn {{ $user->is_blocked ? 'btn-success' : 'btn-warning' }} fw-semibold">
                                                                {{ $user->is_blocked ? 'Réactiver' : 'Suspendre' }}
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal Supprimer --}}
                                        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold text-danger">
                                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                                            Supprimer l'utilisateur
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert alert-danger small">
                                                            <strong>Attention !</strong> Cette action est irréversible.
                                                            Toutes les données de cet utilisateur seront supprimées
                                                            (profil, messages, demandes, avis...).
                                                        </div>
                                                        <p class="small">
                                                            Voulez-vous vraiment supprimer
                                                            <strong>{{ $user->name }}</strong> ?
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Annuler</button>
                                                        <form action="{{ route('admin.users.delete', $user->id) }}"
                                                              method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-danger fw-semibold">
                                                                <i class="bi bi-trash me-1"></i>Supprimer définitivement
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Aucun utilisateur trouvé.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection