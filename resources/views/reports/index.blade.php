@extends('layouts.app')
@section('title', __('Intelligence Center'))

@section('content')
<div class="page-content pt-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:0.7rem; letter-spacing:0.05em; text-transform:uppercase;">
            <li class="breadcrumb-item"><a href="#" class="text-primary text-decoration-none fw-700">KINETIC</a></li>
            <li class="breadcrumb-item active text-muted opacity-50" aria-current="page">{{ __('Operational Intelligence') }}</li>
        </ol>
    </nav>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end gap-4 mb-5">
        <div>
            <h1 class="st-display-md mb-2">{{ __('Intelligence Center') }}</h1>
            <p class="st-lead mb-0 text-white text-opacity-50">{{ __('Data-driven maintenance oversight, reliability metrics, and operational logistics.') }}</p>
        </div>
        <a href="{{ route('reports.pdf') }}?{{ http_build_query(request()->all()) }}" class="st-btn-dossier text-center" target="_blank">
            <i class="bi bi-file-earmark-pdf me-2"></i>{{ __('Exportar PDF') }}
        </a>
    </div>

    <!-- Intelligence Metrics Grid -->
    <div class="row g-4 mb-5">
        <!-- MTTR (Mean Time to Repair) -->
        <div class="col-6 col-lg-3">
            <div class="st-section-low p-4 border border-white border-opacity-5 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="st-title-sm" style="font-size:0.65rem; color: var(--st-primary);">{{ __('MTTR INDEX') }}</span>
                    <i class="bi bi-stopwatch text-primary"></i>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="st-display-md mb-0">{{ $summary['mttr'] }}</h2>
                    <span class="text-muted extra-small fw-700">{{ __('HRS') }}</span>
                </div>
                <div class="extra-small text-muted mt-2">{{ __('Current Repair Velocity') }}</div>
            </div>
        </div>

        <!-- Reliability Score -->
        <div class="col-6 col-lg-3">
            <div class="st-section-low p-4 border border-white border-opacity-5 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="st-title-sm" style="font-size:0.65rem; color: var(--st-success);">{{ __('SYSTEM RELIABILITY') }}</span>
                    <i class="bi bi-shield-check text-success"></i>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="st-display-md mb-0 text-success">{{ $summary['reliability'] }}</h2>
                    <span class="text-muted extra-small fw-700">%</span>
                </div>
                <div class="extra-small text-muted mt-2">{{ __('Uptime optimization level') }}</div>
            </div>
        </div>

        <!-- Mission Completion -->
        <div class="col-6 col-lg-3">
            <div class="st-section-low p-4 border border-white border-opacity-5 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="st-title-sm" style="font-size:0.65rem; color: var(--st-warning);">{{ __('MISSION ACCOMPLISHED') }}</span>
                    <i class="bi bi-check2-all text-warning"></i>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h2 class="st-display-md mb-0">{{ $summary['completadas'] }}</h2>
                    <span class="text-muted extra-small fw-700">/ {{ $summary['total'] }}</span>
                </div>
                <div class="extra-small text-muted mt-2">{{ __('Total Task Finalization Rate') }}</div>
            </div>
        </div>

        <!-- Capital Allocation -->
        <div class="col-6 col-lg-3">
            <div class="st-section-low p-4 border border-white border-opacity-5 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="st-title-sm" style="font-size:0.65rem; color: #a855f7;">{{ __('LOGISTICS CAPITAL') }}</span>
                    <i class="bi bi-cash-stack" style="color: #a855f7;"></i>
                </div>
                <div class="d-flex align-items-baseline gap-1">
                    <span class="text-muted extra-small fw-700">$</span>
                    <h2 class="st-display-md mb-0" style="color: #a855f7;">{{ number_format($summary['costo_total'], 0) }}</h2>
                </div>
                <div class="extra-small text-muted mt-2">{{ __('Material and parts investment') }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Console -->
    <div class="st-section-low mb-5 p-4 border border-white border-opacity-5">
        <form method="GET" class="row g-4 align-items-end">
            <div class="col-md-2 col-6">
                <label class="extra-small text-muted fw-700 mb-2 uppercase">{{ __('Initial Axis') }}</label>
                <input type="date" name="from" class="form-control bg-dark bg-opacity-25 border-white border-opacity-10 text-white" value="{{ request('from') }}">
            </div>
            <div class="col-md-2 col-6">
                <label class="extra-small text-muted fw-700 mb-2 uppercase">{{ __('Final Axis') }}</label>
                <input type="date" name="to" class="form-control bg-dark bg-opacity-25 border-white border-opacity-10 text-white" value="{{ request('to') }}">
            </div>
            <div class="col-md-2">
                <label class="extra-small text-muted fw-700 mb-2 uppercase">{{ __('Mission Status') }}</label>
                <select name="status" class="form-select bg-dark bg-opacity-25 border-white border-opacity-10 text-white">
                    <option value="">{{ __('ALL STATUS') }}</option>
                    @foreach(['pendiente','en_proceso','completada','cancelada'] as $s)
                    <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ strtoupper(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="extra-small text-muted fw-700 mb-2 uppercase">{{ __('Member Assigned') }}</label>
                <select name="assigned_to" class="form-select bg-dark bg-opacity-25 border-white border-opacity-10 text-white">
                    <option value="">{{ __('ALL MEMBERS') }}</option>
                    @foreach($tecnicos as $t)
                    <option value="{{ $t->id }}" {{ request('assigned_to') == $t->id?'selected':'' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="st-btn-primary flex-grow-1 py-2">{{ __('Generate Query') }}</button>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary border-opacity-20 d-flex align-items-center px-3">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Analytical Registry Table -->
    <div class="st-card p-0 overflow-hidden border border-white border-opacity-5">
        <div class="table-responsive">
            <table class="st-table table m-0">
                <thead>
                    <tr>
                        <th>{{ __('Nomenclature') }}</th>
                        <th>{{ __('Asset Entity') }}</th>
                        <th>{{ __('Tier / Priority') }}</th>
                        <th class="text-center">{{ __('Status') }}</th>
                        <th class="text-end">{{ __('Deployment Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('work-orders.show', $order) }}" class="text-primary text-decoration-none fw-700 font-monospace">
                                {{ $order->code }}
                            </a>
                        </td>
                        <td>
                            <div class="text-white fw-600">{{ $order->asset->name ?? 'GRID' }}</div>
                            <div class="extra-small text-muted">{{ $order->asset->location ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <span class="extra-small fw-700 text-{{ $order->priority_color }}">{{ strtoupper($order->priority_label) }}</span>
                            <div class="extra-small text-muted mt-1">{{ strtoupper($order->type_label) }}</div>
                        </td>
                        <td class="text-center">
                            <span class="badge border border-{{ $order->status_color }} text-{{ $order->status_color }} extra-small py-1 px-2" style="background:rgba(var(--st-{{ $order->status_color }}-rgb), 0.1);">
                                {{ strtoupper($order->status_label) }}
                            </span>
                        </td>
                        <td class="text-end font-monospace text-white text-opacity-50 small">
                            {{ $order->created_at->format('d/m/Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted opacity-50">
                            {{ __('No operational records found for the current query.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
</div>
@endsection
