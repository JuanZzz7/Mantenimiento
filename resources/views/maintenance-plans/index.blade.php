@extends('layouts.app')
@section('title','Planes de Mantenimiento')
@section('breadcrumb')
    <li class="breadcrumb-item active">Planes Preventivos</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title"><i class="bi bi-calendar3 me-2 text-primary"></i>Planes de Mantenimiento Preventivo</h1>
    <a href="{{ route('maintenance-plans.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Plan
    </a>
</div>

<!-- Filtros -->
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Buscar plan o activo..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="frequency" class="form-select form-select-sm">
                    <option value="">Todas las frecuencias</option>
                    @foreach(['semanal','mensual','trimestral','semestral','anual'] as $f)
                    <option value="{{ $f }}" {{ request('frequency')===$f?'selected':'' }}>{{ ucfirst($f) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-search"></i></button>
                <a href="{{ route('maintenance-plans.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3">
    @forelse($plans as $plan)
    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="fw-600" style="font-size:.9rem;">{{ $plan->name }}</div>
                    @if($plan->active)
                        <span class="badge bg-success">Activo</span>
                    @else
                        <span class="badge bg-secondary">Inactivo</span>
                    @endif
                </div>
                <div style="font-size:.8rem;color:#94a3b8;" class="mb-3">
                    <i class="bi bi-cpu me-1"></i>{{ $plan->asset->name }}
                </div>

                <div class="row g-1 mb-3" style="font-size:.78rem;">
                    <div class="col-6">
                        <span style="color:#94a3b8;">Frecuencia:</span><br>
                        <span class="badge" style="background:rgba(59,130,246,.15);color:#93c5fd;">{{ $plan->frequency_label }}</span>
                    </div>
                    <div class="col-6">
                        <span style="color:#94a3b8;">Próxima ejecución:</span><br>
                        @if($plan->next_run_at)
                            <span class="{{ $plan->next_run_at->isPast() ? 'text-danger fw-600' : 'text-white' }}">
                                {{ $plan->next_run_at->format('d/m/Y') }}
                            </span>
                            @if($plan->next_run_at->isPast())
                                <span class="badge bg-danger ms-1">Vencido</span>
                            @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </div>
                </div>

                <div class="d-flex gap-1 mt-auto">
                    <form method="POST" action="{{ route('maintenance-plans.generate', $plan) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-warning" title="Generar OT preventiva">
                            <i class="bi bi-play-fill me-1"></i>Generar OT
                        </button>
                    </form>
                    <a href="{{ route('maintenance-plans.edit', $plan) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('maintenance-plans.destroy', $plan) }}"
                          onsubmit="return confirm('¿Eliminar este plan?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-calendar3 fs-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted">No hay planes de mantenimiento</h5>
                <a href="{{ route('maintenance-plans.create') }}" class="btn btn-primary mt-2">Crear Plan</a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-4 d-flex justify-content-center">{{ $plans->links() }}</div>
@endsection
