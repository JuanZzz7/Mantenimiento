@extends('layouts.app')
@section('title','Usuarios')
@section('breadcrumb')
    <li class="breadcrumb-item active">Usuarios</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title"><i class="bi bi-people me-2 text-primary"></i>Gestión de Usuarios</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i>Nuevo Usuario
    </a>
</div>

<!-- Filtros -->
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Buscar por nombre o correo..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select form-select-sm">
                    <option value="">Todos los roles</option>
                    <option value="admin" {{ request('role')==='admin'?'selected':'' }}>Administrador</option>
                    <option value="tecnico" {{ request('role')==='tecnico'?'selected':'' }}>Técnico</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-search"></i></button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr>
                    <th>Usuario</th><th>Correo</th><th>Rol</th><th>Teléfono</th><th>Estado</th><th>Acciones</th>
                </tr></thead>
                <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $user->avatar_url }}" class="rounded-circle" width="34" height="34" alt="{{ $user->name }}">
                            <div>
                                <div style="font-size:.87rem;font-weight:500;">{{ $user->name }}</div>
                                @if($user->id === auth()->id())
                                    <span class="badge bg-info" style="font-size:.65rem;">Tú</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.83rem;">{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->isAdmin() ? 'bg-primary' : 'bg-secondary' }}">
                            {{ $user->role_label }}
                        </span>
                    </td>
                    <td style="font-size:.83rem;color:#94a3b8;">{{ $user->phone ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $user->active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $user->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                  onsubmit="return confirm('¿Eliminar usuario {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-5">No se encontraron usuarios</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $users->links() }}</div>
    </div>
</div>
@endsection
