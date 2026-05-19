@extends('layouts.app')
@section('title', $asset->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('assets.index') }}" class="text-muted">Activos</a></li>
    <li class="breadcrumb-item active">{{ $asset->name }}</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">{{ $asset->name }}</h1>
        <span class="text-muted" style="font-size:.85rem;">{{ $asset->code }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('work-orders.create', ['asset_id' => $asset->id]) }}" class="btn btn-warning">
            <i class="bi bi-plus-lg me-1"></i>Nueva OT
        </a>
        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $asset->image_url }}" class="rounded mb-3" style="width:100%;max-height:200px;object-fit:cover;" alt="{{ $asset->name }}">
                <span class="badge bg-{{ $asset->status_color }} fs-6 mb-2">{{ $asset->status_label }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header"><span class="fw-600">Información del Activo</span></div>
            <div class="card-body">
                <dl class="row g-2" style="font-size:.87rem;">
                    <dt class="col-sm-4 text-muted">Código</dt>
                    <dd class="col-sm-8">{{ $asset->code }}</dd>
                    <dt class="col-sm-4 text-muted">Ubicación</dt>
                    <dd class="col-sm-8"><i class="bi bi-geo-alt me-1"></i>{{ $asset->location }}</dd>
                    <dt class="col-sm-4 text-muted">Categoría</dt>
                    <dd class="col-sm-8">{{ $asset->category ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Marca / Modelo</dt>
                    <dd class="col-sm-8">{{ $asset->brand ?? '—' }} / {{ $asset->model ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">N° de Serie</dt>
                    <dd class="col-sm-8">{{ $asset->serial_number ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Adquisición</dt>
                    <dd class="col-sm-8">{{ $asset->acquisition_date?->format('d/m/Y') ?? '—' }}</dd>
                    @if($asset->description)
                    <dt class="col-sm-4 text-muted">Descripción</dt>
                    <dd class="col-sm-8">{{ $asset->description }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>

<!-- Historial OTs -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-600"><i class="bi bi-clock-history me-2"></i>Historial de Mantenimientos</span>
        <span class="badge bg-secondary">{{ $workOrders->total() }} órdenes</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr>
                    <th>Código</th><th>Tipo</th><th>Descripción</th><th>Prioridad</th>
                    <th>Estado</th><th>Técnico</th><th>Fecha</th><th></th>
                </tr></thead>
                <tbody>
                @forelse($workOrders as $order)
                <tr>
                    <td><span class="badge bg-secondary">{{ $order->code }}</span></td>
                    <td><span class="badge" style="background:rgba(99,102,241,.2);color:#a5b4fc;">{{ $order->type_label }}</span></td>
                    <td style="font-size:.82rem;max-width:200px;">{{ Str::limit($order->description, 50) }}</td>
                    <td><span class="badge bg-{{ $order->priority_color }}">{{ $order->priority_label }}</span></td>
                    <td><span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                    <td style="font-size:.82rem;">{{ $order->assignedUser->name ?? '—' }}</td>
                    <td style="font-size:.78rem;color:#94a3b8;">{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('work-orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Sin órdenes registradas</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($workOrders->hasPages())
        <div class="p-3">{{ $workOrders->links() }}</div>
        @endif
    </div>
</div>
@endsection
