@extends('layouts.app')
@section('title', __('Factory Assets'))

@section('content')
<div class="page-content pt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:0.75rem; letter-spacing:0.05em; text-transform:uppercase; font-weight:600;">
            <li class="breadcrumb-item"><a href="#" class="text-primary text-decoration-none">KINETIC</a></li>
            <li class="breadcrumb-item active text-muted opacity-75" aria-current="page">{{ __('Maintenance Ops') }}</li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 mb-5">
        <div>
            <h1 class="st-display-md mb-2 fw-900" style="letter-spacing:-0.02em;">{{ __('Factory Assets') }}</h1>
            <p class="st-lead mb-0 text-white text-opacity-50" style="font-size:1.1rem; max-width:600px;">
                {{ __('Industrial equipment nomenclature, telemetry registry and sensor fleet oversight.') }}
            </p>
        </div>
        <a href="{{ route('assets.create') }}" class="btn btn-primary rounded-pill px-4 py-3 fw-bold shadow-lg d-flex align-items-center gap-2" style="background: linear-gradient(135deg, var(--st-primary), #4d94ff); border:none; transition: transform 0.2s;">
            <i class="bi bi-plus-circle-fill fs-5"></i> {{ __('Provision Asset') }}
        </a>
    </div>

    <!-- Search and Filters (Mockup for UI Consistency) -->
    <div class="st-card p-4 mb-5 border-0 shadow-sm" style="background: rgba(255,255,255,0.02); border-radius:16px;">
        <form method="GET" action="{{ route('assets.index') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label text-muted fw-bold mb-2 small" style="letter-spacing:0.05em; text-transform:uppercase;">Buscar Activo</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary border-opacity-25 text-muted border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control bg-dark border-secondary border-opacity-25 text-white border-start-0 ps-0 py-2" 
                           placeholder="ID, Nombre o Ubicación..." value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="col-md-4">
                <label class="form-label text-muted fw-bold mb-2 small" style="letter-spacing:0.05em; text-transform:uppercase;">Estado Operativo</label>
                <select name="status" class="form-select bg-dark border-secondary border-opacity-25 text-white py-2">
                    <option value="">Todos los estados</option>
                    <option value="operativo" {{ request('status') == 'operativo' ? 'selected' : '' }}>Operativo</option>
                    <option value="en_mantenimiento" {{ request('status') == 'en_mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                    <option value="fuera_de_servicio" {{ request('status') == 'fuera_de_servicio' ? 'selected' : '' }}>Fuera de Servicio</option>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1 py-2 fw-bold">Filtrar</button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary py-2" title="Limpiar Filtros">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Assets Grid -->
    <div class="row g-4">
        @forelse($assets as $asset)
            @php
                // Map status to specific UI colors
                $statusColor = $asset->status_color ?? 'secondary';
                $statusIcon = 'bi-check-circle-fill';
                if($asset->status == 'en_mantenimiento') $statusIcon = 'bi-tools';
                if($asset->status == 'fuera_de_servicio') $statusIcon = 'bi-x-circle-fill';
            @endphp
            
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <div class="card spare-card h-100 border-0 shadow-sm" style="background: rgba(255,255,255,0.03); border-radius: 16px; transition: transform 0.2s, box-shadow 0.2s;">
                    
                    <!-- Card Header -->
                    <div class="card-header border-0 bg-transparent pt-4 pb-0 d-flex justify-content-between align-items-center">
                        <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} border border-{{ $statusColor }} border-opacity-25 px-2 py-1 fw-bold" style="font-size:0.65rem; letter-spacing:0.05em;">
                            <i class="bi {{ $statusIcon }} me-1" style="font-size:0.5rem; vertical-align:middle;"></i>{{ strtoupper($asset->status_label) }}
                        </span>
                        
                        <!-- Dropdown Options -->
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical fs-5"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border border-white border-opacity-10 bg-dark">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-white" href="{{ route('assets.show', $asset) }}">
                                        <i class="bi bi-eye text-primary"></i> {{ __('Inspect Detail') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-white" href="{{ route('assets.edit', $asset) }}">
                                        <i class="bi bi-pencil text-warning"></i> {{ __('Modify Config') }}
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider border-secondary border-opacity-25"></li>
                                <li>
                                    <form method="POST" action="{{ route('assets.destroy', $asset) }}" onsubmit="return confirm('{{ __('Decommission Asset?') }}');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                                            <i class="bi bi-trash"></i> {{ __('Decommission') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px; background: rgba(var(--bs-primary-rgb), 0.1); border: 1px solid rgba(var(--bs-primary-rgb), 0.2);">
                                <i class="bi bi-cpu text-primary fs-4" style="text-shadow: 0 0 10px rgba(var(--bs-primary-rgb), 0.5);"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="card-title text-white fw-bold mb-1 text-truncate" title="{{ $asset->name }}">{{ $asset->name }}</h5>
                                <div class="text-primary font-monospace small bg-primary bg-opacity-10 d-inline-block px-2 py-1 rounded">REF: #{{ $asset->code }}</div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-4">
                            @if($asset->category)
                                <span class="badge bg-secondary bg-opacity-25 text-white text-opacity-75 fw-normal">
                                    <i class="bi bi-tags-fill me-1 opacity-50"></i>{{ strtoupper($asset->category) }}
                                </span>
                            @endif
                            @if($asset->location)
                                <span class="badge bg-secondary bg-opacity-25 text-white text-opacity-75 fw-normal">
                                    <i class="bi bi-geo-alt-fill me-1 opacity-50"></i>{{ $asset->location }}
                                </span>
                            @endif
                            @if($asset->brand)
                                <span class="badge bg-secondary bg-opacity-25 text-white text-opacity-75 fw-normal">
                                    <i class="bi bi-award-fill me-1 opacity-50"></i>{{ $asset->brand }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Footer: Telemetry/Activity -->
                    <div class="card-footer bg-transparent border-top border-secondary border-opacity-25 p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-column">
                                <span class="text-muted" style="font-size:0.65rem; text-transform:uppercase; letter-spacing:0.05em;">{{ __('Activity') }}</span>
                                <span class="text-white fw-bold d-flex align-items-center gap-1">
                                    <i class="bi bi-journal-text text-primary"></i> 
                                    {{ $asset->work_orders_count }} <span class="fw-normal text-white text-opacity-50 ms-1 small">{{ __('log entries') }}</span>
                                </span>
                            </div>
                            <a href="{{ route('assets.show', $asset) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                                Analizar <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        @empty
            <div class="col-12">
                <div class="text-center py-5 st-card border-0" style="background: rgba(255,255,255,0.02); border-radius:16px;">
                    <div class="mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-dark" style="width:80px; height:80px;">
                            <i class="bi bi-cpu-fill text-muted opacity-50" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="text-white fw-bold mb-2">Registro de Activos Vacío</h3>
                    <p class="text-muted mb-4">{{ __('No industrial assets found in current registry.') }}</p>
                    <a href="{{ route('assets.create') }}" class="btn btn-primary rounded-pill px-4 py-2">
                        <i class="bi bi-plus-lg me-2"></i>{{ __('Provision Asset') }}
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($assets->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $assets->links() }}
        </div>
    @endif
</div>
@endsection
