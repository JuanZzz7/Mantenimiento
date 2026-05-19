@extends('layouts.app')
@section('title', $workOrder->code)

@section('content')
<div class="page-content pt-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:0.7rem; letter-spacing:0.05em; text-transform:uppercase;">
            <li class="breadcrumb-item"><a href="#" class="text-primary text-decoration-none fw-700">KINETIC</a></li>
            <li class="breadcrumb-item"><a href="{{ route('work-orders.index') }}" class="text-muted text-decoration-none opacity-50">{{ __('Tactical Missions') }}</a></li>
            <li class="breadcrumb-item active text-muted opacity-50" aria-current="page">{{ $workOrder->code }}</li>
        </ol>
    </nav>

    <!-- Mission Hero Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4 mb-5">
        <div>
            <div class="d-flex align-items-center gap-3 mb-2">
                <h1 class="st-display-md mb-0">{{ $workOrder->code }}</h1>
                <span class="badge border border-{{ $workOrder->status_color }} text-{{ $workOrder->status_color }} py-2 px-3 fw-700" style="background:rgba(var(--st-{{ $workOrder->status_color }}-rgb), 0.1); font-size:0.75rem;">
                    {{ strtoupper($workOrder->status_label) }}
                </span>
            </div>
            <p class="st-lead mb-0 text-white text-opacity-50">
                <i class="bi bi-cpu me-2"></i>{{ $workOrder->asset->name }} — {{ $workOrder->asset->location }}
            </p>
        </div>
        <div class="d-flex gap-2">
            @if($workOrder->status === 'pendiente')
                <form action="{{ route('work-orders.start', $workOrder) }}" method="POST">
                    @csrf
                    <button type="submit" class="st-btn-primary px-4">
                        <i class="bi bi-play-fill me-2 text-white"></i>{{ __('Initiate Mission') }}
                    </button>
                </form>
            @elseif($workOrder->status === 'en_proceso')
                <form action="{{ route('work-orders.complete', $workOrder) }}" method="POST">
                    @csrf
                    <button type="submit" class="st-btn-primary px-4" style="background: var(--st-success);">
                        <i class="bi bi-check-lg me-2 text-white"></i>{{ __('Accomplish Mission') }}
                    </button>
                </form>
            @endif
            <a href="{{ route('work-orders.edit', $workOrder) }}" class="btn btn-outline-secondary border-opacity-20 text-white text-opacity-50 px-4">
                <i class="bi bi-pencil me-2"></i>{{ __('Modify Config') }}
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Intel Column -->
        <div class="col-lg-8">
            <!-- Mission Briefing -->
            <div class="st-section-low p-4 p-md-5 mb-4 border border-white border-opacity-5">
                <label class="st-title-sm mb-4" style="color: var(--st-primary); letter-spacing: 0.1em;">
                    <i class="bi bi-card-text me-2"></i>{{ __('MISSION BRIEFING & OBJECTIVES') }}
                </label>
                <div class="text-white text-opacity-75 fs-5 mb-4 lh-lg">
                    {{ $workOrder->description }}
                </div>
                @if($workOrder->notes)
                <div class="p-3 rounded bg-dark bg-opacity-25 border-start border-primary border-4 mt-4">
                    <div class="extra-small text-primary fw-700 mb-1">{{ __('FIELD NOTES') }}</div>
                    <div class="text-white text-opacity-50 small">{{ $workOrder->notes }}</div>
                </div>
                @endif
            </div>

            <!-- Logistics: Spare Parts Deployment -->
            <div class="st-card p-0 overflow-hidden border border-white border-opacity-5">
                <div class="p-4 border-bottom border-white border-opacity-5 d-flex justify-content-between align-items-center">
                    <label class="st-title-sm mb-0" style="color: var(--st-primary); letter-spacing: 0.1em;">
                        <i class="bi bi-box-seam me-2"></i>{{ __('DEPLOYED COMPONENTS & HARDWARE') }}
                    </label>
                    <div class="text-white fw-700" style="font-size:0.85rem;">
                        <span class="opacity-50">{{ __('TOTAL LOGISTICS COST') }}:</span> 
                        <span class="text-success ms-2">${{ number_format($workOrder->total_cost, 2) }}</span>
                    </div>
                </div>

                @if(!in_array($workOrder->status, ['completada','cancelada']))
                <div class="p-4 bg-white bg-opacity-5 border-bottom border-white border-opacity-5">
                    <form method="POST" action="{{ route('work-orders.spares.add', $workOrder) }}" class="row g-3 align-items-end">
                        @csrf
                        <div class="col-md-7">
                            <label class="extra-small text-muted fw-700 mb-2">{{ __('RESOURCE ALLOCATION') }}</label>
                            <select name="spare_id" class="form-select bg-dark bg-opacity-50 border-white border-opacity-10 text-white py-2" style="font-size:0.85rem;" required>
                                <option value="">— {{ __('Select Component') }} —</option>
                                @foreach($availableSpares as $spare)
                                <option value="{{ $spare->id }}">{{ $spare->name }} [{{ $spare->stock }} {{ $spare->unit }} available]</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="extra-small text-muted fw-700 mb-2">{{ __('QTY') }}</label>
                            <input type="number" name="quantity" class="form-control bg-dark bg-opacity-50 border-white border-opacity-10 text-white py-2" value="1" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="st-btn-primary py-2 w-100" style="font-size:0.85rem;">
                                <i class="bi bi-plus-lg me-2"></i>{{ __('Provision') }}
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="st-table table m-0">
                        <thead>
                            <tr>
                                <th>{{ __('Component Nomenclature') }}</th>
                                <th class="text-center">{{ __('Deployed Qty') }}</th>
                                <th class="text-end">{{ __('Unit Value') }}</th>
                                <th class="text-end">{{ __('Subtotal') }}</th>
                                @if(!in_array($workOrder->status, ['completada','cancelada']))
                                <th class="text-end"></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($workOrder->workOrderSpares as $item)
                            <tr>
                                <td class="fw-700 text-white">{{ $item->spare->name }}</td>
                                <td class="text-center text-muted fw-600">{{ $item->quantity }} {{ $item->spare->unit }}</td>
                                <td class="text-end text-muted font-monospace">${{ number_format($item->unit_price,2) }}</td>
                                <td class="text-end text-success fw-700 font-monospace">${{ number_format($item->subtotal,2) }}</td>
                                @if(!in_array($workOrder->status, ['completada','cancelada']))
                                <td class="text-end">
                                    <form method="POST" action="{{ route('work-orders.spares.remove', [$workOrder, $item]) }}" onsubmit="return confirm('{{ __('De-provision component?') }}')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-link link-danger p-0 border-0"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted opacity-50">
                                    {{ __('No hardware deployed for this mission.') }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Operational Telemetry Sidebar -->
        <div class="col-lg-4">
            <!-- Mission Status Widget -->
            <div class="st-section-low p-4 mb-4 border border-white border-opacity-5">
                <label class="st-title-sm mb-3" style="color: var(--st-primary); letter-spacing: 0.1em;">
                    <i class="bi bi-activity me-2"></i>{{ __('MISSION CONTROL') }}
                </label>
                <div class="vstack gap-3 mt-4">
                    <div class="d-flex justify-content-between">
                        <span class="small text-muted">{{ __('MEMBER ASSIGNED') }}</span>
                        <span class="small text-white fw-700">{{ $workOrder->assignedUser->name ?? __('AWAITING DISPATCH') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="small text-muted">{{ __('PRIORITY TIER') }}</span>
                        <span class="small fw-700 text-{{ $workOrder->priority_color }}">{{ strtoupper($workOrder->priority_label) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="small text-muted">{{ __('DEPLOYMENT TYPE') }}</span>
                        <span class="small text-white fw-700">{{ strtoupper($workOrder->type_label) }}</span>
                    </div>
                </div>
            </div>

            <!-- Timeline Widget -->
            <div class="st-section-low p-4 border border-white border-opacity-5">
                <label class="st-title-sm mb-3" style="color: var(--st-primary); letter-spacing: 0.1em;">
                    <i class="bi bi-clock-history me-2"></i>{{ __('CHRONOLOGY') }}
                </label>
                <div class="mt-4 vstack gap-4 position-relative">
                    <div class="position-absolute h-100 border-start border-white border-opacity-10" style="left:7px; top:10px;"></div>
                    
                    <div class="d-flex gap-3 position-relative">
                        <div class="rounded-circle bg-primary" style="width:15px; height:15px; border:3px solid var(--st-surface);"></div>
                        <div>
                            <div class="extra-small text-muted fw-700">{{ __('INITIALIZED') }}</div>
                            <div class="small text-white opacity-75">{{ $workOrder->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 position-relative">
                        <div class="rounded-circle {{ $workOrder->started_at ? 'bg-primary' : 'bg-dark border border-white border-opacity-20' }}" style="width:15px; height:15px; border:3px solid var(--st-surface);"></div>
                        <div>
                            <div class="extra-small text-muted fw-700">{{ __('MISSION START') }}</div>
                            <div class="small text-white opacity-75">{{ $workOrder->started_at?->format('d/m/Y H:i') ?? __('WAITING...') }}</div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 position-relative">
                        <div class="rounded-circle {{ $workOrder->completed_at ? 'bg-success' : 'bg-dark border border-white border-opacity-20' }}" style="width:15px; height:15px; border:3px solid var(--st-surface);"></div>
                        <div>
                            <div class="extra-small text-muted fw-700">{{ __('ACCOMPLISHED') }}</div>
                            <div class="small text-white opacity-75">{{ $workOrder->completed_at?->format('d/m/Y H:i') ?? __('ONGOING') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
