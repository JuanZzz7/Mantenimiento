@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@php $user = auth()->user(); @endphp

<!-- Hero Header -->
<div class="page-content pt-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:0.7rem; letter-spacing:0.05em; text-transform:uppercase;">
            <li class="breadcrumb-item"><a href="#" class="text-primary text-decoration-none fw-700">KINETIC</a></li>
            <li class="breadcrumb-item active text-muted opacity-50" aria-current="page">{{ __('Operation Console') }}</li>
        </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end gap-4 mb-5">
        <div>
            <h1 class="st-display-md mb-2">{{ __('Systems Status') }}</h1>
            <p class="st-lead mb-0 text-white text-opacity-50" style="max-width: 500px;">{{ __('Real-time operational overview, tactical asset telemetry and mission deployment status.') }}</p>
        </div>
        <div class="st-profile-widget m-0" style="background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);">
            <div class="st-title-sm mb-1" style="color: var(--st-primary); font-size: 0.55rem;">{{ __('Active Session') }}</div>
            <div class="fw-800 text-white" style="font-size: 0.9rem;">{{ $user->name }}</div>
        </div>
    </div>
</div>

@if($user->isAdmin())
<!-- Tactical Index Row (Rule 5-3-2-1) -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="st-section-low">
            <div class="st-display-md text-success mb-2" style="font-size:2rem;">98%</div>
            <div class="small st-lead">{{ __('Optimal performance') }}</div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="st-section-low">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-700 mb-0">{{ __('Tactical Index') }}</h5>
                    <div class="st-title-sm" style="font-size:0.6rem;">{{ __('Estrategia Aplicada') }}</div>
                </div>
                <div class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2">
                    <i class="bi bi-shield-check me-2"></i>KINETIC ANALYTICS
                </div>
            </div>
            
            <div class="row g-4">
                {{-- Preventive --}}
                <div class="col-md-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="st-title-sm" style="font-size:0.65rem;">{{ __('Preventive') }} (5)</span>
                        <span class="fw-800 text-white" style="font-size:0.75rem;">{{ $tacticalRule['preventive'] * 10 }}%</span>
                    </div>
                    <div class="progress bg-white bg-opacity-5" style="height:6px; border-radius:10px;">
                        <div class="progress-bar bg-primary" style="width:{{ min(($tacticalRule['preventive'] * 10), 100) }}%; border-radius:10px; box-shadow: 0 0 10px rgba(0, 88, 190, 0.4);"></div>
                    </div>
                    <div class="extra-small text-muted mt-2">{{ __('Target') }}: 50%</div>
                </div>
                {{-- Corrective --}}
                <div class="col-md-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="st-title-sm" style="font-size:0.65rem;">{{ __('Corrective') }} (3)</span>
                        <span class="fw-800 text-white" style="font-size:0.75rem;">{{ $tacticalRule['corrective'] * 10 }}%</span>
                    </div>
                    <div class="progress bg-white bg-opacity-5" style="height:6px; border-radius:10px;">
                        <div class="progress-bar bg-warning" style="width:{{ min(($tacticalRule['corrective'] * 10), 100) }}%; border-radius:10px; box-shadow: 0 0 10px rgba(245, 158, 11, 0.4);"></div>
                    </div>
                    <div class="extra-small text-muted mt-2">{{ __('Target') }}: 30%</div>
                </div>
                {{-- Predictive --}}
                <div class="col-md-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="st-title-sm" style="font-size:0.65rem;">{{ __('Predictive') }} (2)</span>
                        <span class="fw-800 text-white" style="font-size:0.75rem;">{{ $tacticalRule['predictive'] * 10 }}%</span>
                    </div>
                    <div class="progress bg-white bg-opacity-5" style="height:6px; border-radius:10px;">
                        <div class="progress-bar bg-danger" style="width:{{ min(($tacticalRule['predictive'] * 10), 100) }}%; border-radius:10px; box-shadow: 0 0 10px rgba(239, 68, 68, 0.4);"></div>
                    </div>
                    <div class="extra-small text-muted mt-2">{{ __('Target') }}: 20%</div>
                </div>
                {{-- Improvement --}}
                <div class="col-md-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="st-title-sm" style="font-size:0.65rem;">{{ __('Improvement') }} (1)</span>
                        <span class="fw-800 text-white" style="font-size:0.75rem;">{{ $tacticalRule['improvement'] * 10 }}%</span>
                    </div>
                    <div class="progress bg-white bg-opacity-5" style="height:6px; border-radius:10px;">
                        <div class="progress-bar bg-info" style="width:{{ min(($tacticalRule['improvement'] * 10), 100) }}%; border-radius:10px; box-shadow: 0 0 10px rgba(13, 202, 240, 0.4);"></div>
                    </div>
                    <div class="extra-small text-muted mt-2">{{ __('Target') }}: 10%</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-8">
        <div class="st-section-low h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-700 mb-0">{{ __('Uptime Performance') }}</h5>
                    <div class="st-title-sm" style="font-size:0.6rem;">{{ __('Monthly maintenance trends') }}</div>
                </div>
            </div>
            <canvas id="monthlyChart" style="max-height:300px;"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="st-section-low h-100">
            <h5 class="fw-700 mb-4">{{ __('Tactical Distribution') }}</h5>
            <div class="st-title-sm mb-4">{{ __('Workload by Status') }}</div>
            <div class="d-flex align-items-center justify-content-center" style="height:250px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="st-card p-0 overflow-hidden">
            <div class="p-4 bg-transparent border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                <h5 class="fw-700 mb-0 text-white">Recent Activity Logs</h5>
                <a href="{{ route('work-orders.index') }}" class="btn btn-outline-primary py-2 px-3 fw-bold" style="font-size:0.7rem;">EXPLORE ALL</a>
            </div>
            <div class="table-responsive">
                <table class="st-table table m-0">
                    <thead>
                        <tr>
                            <th>Ref</th>
                            <th>Asset Profile</th>
                            <th>Status</th>
                            <th>Timeline</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td class="fw-700 text-primary">#{{ $order->code }}</td>
                            <td>
                                <div class="fw-600">{{ $order->asset->name }}</div>
                                <div class="extra-small text-muted">{{ $order->asset->category }}</div>
                            </td>
                            <td>
                                <span class="badge rounded-pill badge-st" style="background:rgba(0,102,255,0.1); color:#0066ff;">
                                    {{ strtoupper($order->status_label) }}
                                </span>
                            </td>
                            <td class="small text-muted">{{ $order->created_at->format('d M') }}</td>
                            <td class="text-end">
                                <a href="{{ route('work-orders.show', $order) }}" class="btn btn-sm btn-link text-primary"><i class="bi bi-arrow-right"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="st-card h-100" style="border-top: 4px solid var(--st-danger) !important;">
            <div class="st-title-sm mb-4">System Alerts</div>
            <div class="space-y-4">
                @if($stats['low_stock_spares'] > 0)
                <div class="d-flex gap-3 mb-4">
                    <div class="st-section-low p-2 text-danger"><i class="bi bi-gpu-card"></i></div>
                    <div>
                        <div class="fw-700 small">Inventory Depletion</div>
                        <p class="st-lead small mb-1">{{ $stats['low_stock_spares'] }} items below safety threshold.</p>
                        <a href="{{ route('spares.index', ['low_stock' => 1]) }}" class="text-danger small fw-800 text-decoration-none">Stock Audit →</a>
                    </div>
                </div>
                @endif
                @forelse($duePlans as $plan)
                <div class="d-flex gap-3 mb-4">
                    <div class="st-section-low p-2 text-warning"><i class="bi bi-shield-exclamation"></i></div>
                    <div>
                        <div class="fw-700 small">Preventive Delay</div>
                        <p class="st-lead small mb-0">{{ $plan->asset->name }} needs attention.</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">Systems Nominal. No alerts.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@else
<!-- TÉCNICO DASHBOARD: Simplified Hero -->
<div class="row g-4 mb-5">
    <div class="col-4">
        <div class="st-card text-center">
            <div class="st-display-md text-warning mb-1" style="font-size:2rem;">{{ $myStats['pending'] }}</div>
            <div class="st-title-sm">Pending</div>
        </div>
    </div>
    <div class="col-4">
        <div class="st-card text-center">
            <div class="st-display-md text-info mb-1" style="font-size:2rem;">{{ $myStats['in_progress'] }}</div>
            <div class="st-title-sm">Current</div>
        </div>
    </div>
    <div class="col-4">
        <div class="st-card text-center">
            <div class="st-display-md text-success mb-1" style="font-size:2rem;">{{ $myStats['completed'] }}</div>
            <div class="st-title-sm">Finished</div>
        </div>
    </div>
</div>

<div class="st-card p-0 overflow-hidden">
    <div class="p-4 bg-transparent border-bottom border-secondary border-opacity-25">
        <h5 class="fw-700 mb-0 text-white">Assigned Missions</h5>
    </div>
    <div class="list-group list-group-flush">
        @forelse($myOrders as $order)
        <div class="list-group-item bg-transparent border-secondary border-opacity-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="st-title-sm" style="color: var(--st-primary);">Ref: {{ $order->code }}</span>
                <span class="badge badge-st rounded-pill" style="background:rgba(245,158,11,0.1); color:#f59e0b;">{{ strtoupper($order->priority_label) }}</span>
            </div>
            <h6 class="fw-700 text-white mb-2">{{ $order->asset->name }}</h6>
            <p class="st-lead small mb-3">{{ Str::limit($order->description, 100) }}</p>
            <div class="d-flex justify-content-between align-items-end">
                <div class="extra-small text-muted"><i class="bi bi-clock me-1"></i> Due {{ $order->scheduled_date->format('d M, H:i') }}</div>
                <a href="{{ route('work-orders.show', $order) }}" class="st-btn-primary py-2 px-3" style="font-size:0.65rem;">Enter Mission →</a>
            </div>
        </div>
        @empty
        <div class="p-5 text-center">
            <i class="bi bi-shield-check text-success display-4 mb-3"></i>
            <div class="fw-700">All Systems Nominal</div>
            <div class="st-lead">No pending assignments.</div>
        </div>
        @endforelse
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="{{ asset('js/vendor/chart.umd.min.js') }}"></script>
@if(auth()->user()->isAdmin())
<script>
const stColors = {
    primary: '#0058be',
    primaryCont: '#2170e4',
    onSurface: '#111c2d',
    onSurfaceVar: '#424754',
    grid: 'rgba(114, 119, 133, 0.1)'
};

const chartDefaults = {
    color: stColors.onSurfaceVar,
    font: { family: 'Inter', size: 11 },
    plugins: { 
        legend: { labels: { color: stColors.onSurfaceVar, boxWidth: 10, usePointStyle: true } } 
    },
    scales: {
        x: { grid: { display: false }, ticks: { color: stColors.onSurfaceVar } },
        y: { 
            grid: { color: stColors.grid, drawBorder: false }, 
            ticks: { color: stColors.onSurfaceVar, stepSize: 1 } 
        }
    }
};

// Donut — OTs por Estado
const statusData = @json($ordersByStatus);
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1).replace('_',' ')),
        datasets: [{
            data: Object.values(statusData),
            backgroundColor: ['#0058be', '#2170e4', '#dee8ff', '#d8e3fb'],
            hoverOffset: 10,
            borderWidth: 0
        }]
    },
    options: { 
        cutout: '75%',
        plugins: { 
            legend: { position: 'bottom', labels: { padding: 20 } } 
        } 
    }
});

// line — OTs mensuales (Kinetic Engine Style)
const monthlyRaw = @json($monthlyOrders);
const monthNames = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
const ctx = document.getElementById('monthlyChart').getContext('2d');
const gradient = ctx.createLinearGradient(0, 0, 0, 300);
gradient.addColorStop(0, 'rgba(0, 88, 190, 0.15)');
gradient.addColorStop(1, 'rgba(0, 88, 190, 0)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: monthlyRaw.map(r => monthNames[r.month - 1] + ' ' + r.year),
        datasets: [{
            label: 'Órdenes',
            data: monthlyRaw.map(r => r.total),
            fill: true,
            backgroundColor: gradient,
            borderColor: stColors.primary,
            borderWidth: 3,
            tension: 0.4,
            pointBackgroundColor: '#fff',
            pointBorderColor: stColors.primary,
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        ...chartDefaults,
        plugins: { 
            ...chartDefaults.plugins,
            legend: { display: false } 
        },
        maintainAspectRatio: false
    }
});
</script>
@endif
@endpush
