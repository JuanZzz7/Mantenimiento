@extends('layouts.app')
@section('title','Nuevo Usuario')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}" class="text-muted">Usuarios</a></li>
    <li class="breadcrumb-item active">Nuevo</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-person-plus me-2 text-primary"></i>Crear Usuario</h1>
</div>

<div class="card" style="max-width:580px;">
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Rol <span class="text-danger">*</span></label>
                    <select name="role" class="form-select" required>
                        <option value="tecnico" {{ old('role') === 'tecnico' ? 'selected':'' }}>Técnico</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected':'' }}>Administrador</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="active" class="form-check-input" value="1"
                               {{ old('active','1') === '1' ? 'checked':'' }}>
                        <label class="form-check-label">Usuario activo</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Crear Usuario</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
