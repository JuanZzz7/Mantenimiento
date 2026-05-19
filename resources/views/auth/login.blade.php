@extends('layouts.auth')
@section('title', 'Iniciar Sesión')

@section('content')
    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success mb-3" style="background:rgba(16,185,129,.15);color:#6ee7b7;border:none;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" style="font-size:.85rem;color:#94a3b8;">Correo electrónico</label>
            <div class="input-group">
                <span class="input-group-text" style="background:#0f172a;border-color:#334155;color:#64748b;">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="usuario@empresa.com" required autofocus>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label" style="font-size:.85rem;color:#94a3b8;">Contraseña</label>
            <div class="input-group">
                <span class="input-group-text" style="background:#0f172a;border-color:#334155;color:#64748b;">
                    <i class="bi bi-lock"></i>
                </span>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                <button type="button" class="btn" style="background:#0f172a;border-color:#334155;color:#64748b;"
                        onclick="togglePwd()">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember" style="font-size:.82rem;color:#94a3b8;">Recordarme</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
        </button>
    </form>

    <p class="text-center mt-4 mb-0" style="font-size:.75rem;color:#475569;">
        CMMS Industrial v1.0 &mdash; Sistema de Gestión de Mantenimiento
    </p>
@endsection

@push('scripts')
<script src="{{ asset('js/login.js') }}"></script>
@endpush
