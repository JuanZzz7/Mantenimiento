@extends('layouts.app')
@section('title', 'Control de Misiones')

@section('content')
<div class="page-content pt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:0.75rem; letter-spacing:0.05em; text-transform:uppercase; font-weight:600;">
            <li class="breadcrumb-item"><a href="#" class="text-primary text-decoration-none">KINETIC</a></li>
            <li class="breadcrumb-item active text-muted opacity-75" aria-current="page">Task Management</li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 mb-5">
        <div>
            <h1 class="st-display-md mb-2 fw-900" style="letter-spacing:-0.02em;">{{ __('Tactical Missions') }}</h1>
            <p class="st-lead mb-0 text-white text-opacity-50" style="font-size:1.1rem; max-width:600px;">
                {{ __('Work order lifecycle, task priority matrices and operational workforce deployment.') }}
            </p>
        </div>
        <a href="{{ route('work-orders.create') }}" class="btn btn-primary rounded-pill px-4 py-3 fw-bold shadow-lg d-flex align-items-center gap-2" style="background: linear-gradient(135deg, var(--st-primary), #4d94ff); border:none; transition: transform 0.2s;">
            <i class="bi bi-plus-circle-fill fs-5"></i> {{ __('Provision Mission') }}
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="st-card p-4 mb-5 border-0 shadow-sm" style="background: rgba(255,255,255,0.02); border-radius:16px;">
        <form method="GET" action="{{ route('work-orders.index') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label text-muted fw-bold mb-2 small" style="letter-spacing:0.05em; text-transform:uppercase;">Buscar Misión</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary border-opacity-25 text-muted border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control bg-dark border-secondary border-opacity-25 text-white border-start-0 ps-0 py-2" 
                           placeholder="ID, Activo o Descripción..." value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="col-md-4">
                <label class="form-label text-muted fw-bold mb-2 small" style="letter-spacing:0.05em; text-transform:uppercase;">Estado</label>
                <select name="status" class="form-select bg-dark border-secondary border-opacity-25 text-white py-2">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_progreso" {{ request('status') == 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                    <option value="completada" {{ request('status') == 'completada' ? 'selected' : '' }}>Completada</option>
                    <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1 py-2 fw-bold">Filtrar</button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('work-orders.index') }}" class="btn btn-outline-secondary py-2" title="Limpiar Filtros">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Work Orders Grid -->
    <div class="row g-4">
        @forelse($orders as $order)
            @php
                // Icons mapping based on status
                $statusIcon = 'bi-clock-fill';
                if($order->status == 'en_progreso') $statusIcon = 'bi-play-circle-fill';
                if($order->status == 'completada') $statusIcon = 'bi-check-circle-fill';
                if($order->status == 'cancelada') $statusIcon = 'bi-x-circle-fill';
            @endphp
            
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <div class="card spare-card h-100 border-0 shadow-sm" style="background: rgba(255,255,255,0.03); border-radius: 16px; transition: transform 0.2s, box-shadow 0.2s;">
                    
                    <!-- Card Header -->
                    <div class="card-header border-0 bg-transparent pt-4 pb-0 d-flex justify-content-between align-items-center">
                        <span class="badge rounded-pill bg-{{ $order->status_color }} bg-opacity-10 text-{{ $order->status_color }} border border-{{ $order->status_color }} border-opacity-25 px-2 py-1 fw-bold" style="font-size:0.65rem; letter-spacing:0.05em;">
                            <i class="bi {{ $statusIcon }} me-1" style="font-size:0.5rem; vertical-align:middle;"></i>{{ strtoupper($order->status_label) }}
                        </span>
                        
                        <!-- Dropdown Options -->
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical fs-5"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border border-white border-opacity-10 bg-dark">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-white" href="{{ route('work-orders.show', $order) }}">
                                        <i class="bi bi-eye text-primary"></i> Ingresar Misión
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-white" href="{{ route('work-orders.edit', $order) }}">
                                        <i class="bi bi-pencil text-warning"></i> Editar Misión
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider border-secondary border-opacity-25"></li>
                                <li>
                                    <form method="POST" action="{{ route('work-orders.destroy', $order) }}" onsubmit="return confirm('¿Eliminar misión táctica permanentemente?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                                            <i class="bi bi-trash"></i> Abortar Misión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px; background: rgba(var(--bs-{{ $order->priority_color }}-rgb), 0.1); border: 1px solid rgba(var(--bs-{{ $order->priority_color }}-rgb), 0.2);">
                                <i class="bi bi-lightning-charge-fill fs-4" style="color: var(--bs-{{ $order->priority_color }}); text-shadow: 0 0 10px rgba(var(--bs-{{ $order->priority_color }}-rgb), 0.5);"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="card-title text-white fw-bold mb-1 text-truncate" title="{{ $order->asset->name ?? 'SYSTEM' }}">{{ $order->asset->name ?? 'SYSTEM' }}</h5>
                                <div class="text-primary font-monospace small bg-primary bg-opacity-10 d-inline-block px-2 py-1 rounded">ID: #{{ $order->code }}</div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-secondary bg-opacity-25 text-white text-opacity-75 fw-normal">
                                <i class="bi bi-layers-fill me-1 opacity-50"></i>{{ strtoupper($order->type_label) }}
                            </span>
                            <span class="badge bg-secondary bg-opacity-25 text-white text-opacity-75 fw-normal">
                                <i class="bi bi-geo-alt-fill me-1 opacity-50"></i>{{ $order->asset->location ?? 'GRID' }}
                            </span>
                            <span class="badge bg-{{ $order->priority_color }} bg-opacity-25 text-{{ $order->priority_color }} fw-bold" style="border: 1px solid rgba(var(--bs-{{ $order->priority_color }}-rgb), 0.3);">
                                <i class="bi bi-exclamation-triangle-fill me-1 opacity-50"></i>{{ strtoupper($order->priority_label) }}
                            </span>
                        </div>
                        
                        <p class="text-muted small mb-0 text-truncate" title="{{ $order->description }}">{{ $order->description }}</p>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer bg-transparent border-top border-secondary border-opacity-25 p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:24px; height:24px; font-size:0.6rem;">
                                    {{ isset($order->assignedUser) ? substr($order->assignedUser->name, 0, 1) : '?' }}
                                </div>
                                <span class="text-white text-opacity-75 small fw-bold text-truncate" style="max-width:80px;">
                                    {{ $order->assignedUser->name ?? 'UNASSIGNED' }}
                                </span>
                            </div>
                            <div class="text-end">
                                <span class="d-block text-white fw-bold small"><i class="bi bi-calendar-event text-primary me-1"></i>{{ $order->scheduled_date?->format('d M') ?? 'TBD' }}</span>
                                <span class="d-block text-muted extra-small">{{ $order->scheduled_date?->format('H:i') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('work-orders.show', $order) }}" class="btn btn-outline-primary w-100 btn-sm rounded-pill py-2 fw-bold d-flex justify-content-center align-items-center gap-2">
                            Ingresar Misión <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                </div>
            </div>

        @empty
            <div class="col-12">
                <div class="text-center py-5 st-card border-0" style="background: rgba(255,255,255,0.02); border-radius:16px;">
                    <div class="mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-dark" style="width:80px; height:80px;">
                            <i class="bi bi-lightning-charge-fill text-muted opacity-50" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h3 class="text-white fw-bold mb-2">Sin Misiones Tácticas</h3>
                    <p class="text-muted mb-4">No se encontraron órdenes de trabajo en el registro.</p>
                    <a href="{{ route('work-orders.create') }}" class="btn btn-primary rounded-pill px-4 py-2">
                        <i class="bi bi-plus-lg me-2"></i>{{ __('Provision Mission') }}
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
