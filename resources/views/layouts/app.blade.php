<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — CMMS Industrial</title>

    <!-- Bootstrap 5 -->
    <link href="{{ asset('css/vendor/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="{{ asset('css/vendor/bootstrap-icons.css') }}" rel="stylesheet">
    <!-- Inter Font (self-hosted) -->
    <link href="{{ asset('css/vendor/fonts.css') }}" rel="stylesheet">
    <!-- Premium Styles -->
    <link href="{{ asset('css/premium.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
<!-- ■ SIDEBAR ──────────────────────────────────────────────────────────────── -->
<nav id="sidebar">
    <div class="sidebar-brand d-flex align-items-center gap-3">
        <div class="brand-icon" style="width:42px; height:42px; border-radius:12px; background:linear-gradient(135deg, var(--st-primary), #4d94ff); display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 15px var(--st-primary-glow);">
            <i class="bi bi-shield-shaded text-white fs-4"></i>
        </div>
        <div>
            <div class="fw-800 text-white" style="letter-spacing: -0.02em; font-size: 1.2rem; line-height: 1;">KINETIC</div>
            <div class="st-title-sm" style="font-size: 0.55rem; color: var(--st-primary); opacity: 0.8;">Industrial OS</div>
        </div>
    </div>

    <div class="sidebar-nav pt-4">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> {{ __('Control Hub') }}
        </a>

        <div class="sidebar-category mt-4">{{ __('Maintenance Ops') }}</div>
        <a href="{{ route('assets.index') }}" class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}">
            <i class="bi bi-cpu-fill"></i> {{ __('Factory Assets') }}
        </a>
        <a href="{{ route('work-orders.index') }}" class="nav-link {{ request()->routeIs('work-orders.*') ? 'active' : '' }}">
            <i class="bi bi-lightning-charge-fill"></i> {{ __('Tactical Orders') }}
            @php $pendingCount = \App\Models\WorkOrder::where('status','pendiente')->count(); @endphp
            @if($pendingCount > 0)
                <span class="badge rounded-pill bg-warning text-dark ms-auto fw-800" style="font-size: 0.6rem; padding: 0.4em 0.7em;">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('maintenance-plans.index') }}" class="nav-link {{ request()->routeIs('maintenance-plans.*') ? 'active' : '' }}">
            <i class="bi bi-shield-check"></i> {{ __('Preventive Plans') }}
        </a>

        <div class="sidebar-category mt-4">{{ __('Supply Chain') }}</div>
        <a href="{{ route('spares.index') }}" class="nav-link {{ request()->routeIs('spares.*') ? 'active' : '' }}">
            <i class="bi bi-box fs-5"></i> {{ __('Inventory Hub') }}
            @php $lowStock = \App\Models\Spare::whereColumn('stock','<=','stock_min')->count(); @endphp
            @if($lowStock > 0)
                <span class="badge rounded-pill bg-danger ms-auto fw-bold" style="font-size: 0.65rem; padding: 0.4em 0.6em; box-shadow: 0 0 8px rgba(220,53,69,0.5);">{{ $lowStock }}</span>
            @endif
        </a>

        <div class="sidebar-category mt-4">{{ __('System Console') }}</div>
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i> {{ __('Intel Reports') }}
        </a>

        @if(auth()->user()->isAdmin())
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> {{ __('Operator Access') }}
        </a>
        @endif
    </div>

    <div class="sidebar-footer">
        <div class="st-profile-widget d-flex align-items-center gap-3">
            <div class="bg-primary rounded-pill d-flex align-items-center justify-content-center text-white fw-900" style="width:40px; height:40px; font-size:0.9rem; box-shadow: 0 4px 10px rgba(0,102,255,0.3);">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="fw-700 text-truncate text-white" style="font-size:.85rem; letter-spacing: -0.01em;">{{ auth()->user()->name }}</div>
                <div class="st-title-sm" style="font-size:.5rem; opacity: 0.6;">{{ strtoupper(auth()->user()->role_label) }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0 text-muted hover-white" title="Secure Logout">
                    <i class="bi bi-power fs-5"></i>
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- ■ MAIN ─────────────────────────────────────────────────────────────────── -->
<div id="main-content">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Topbar -->
    <div id="topbar">
        <button class="btn btn-link text-muted d-lg-none p-0 me-3" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div class="ms-auto d-flex align-items-center gap-4">
            <!-- Locale Switcher -->
            <div class="dropdown">
                <button class="btn btn-link p-0 text-muted text-decoration-none d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <span class="extra-small fw-800 opacity-50">{{ strtoupper(App::getLocale()) }}</span>
                    <i class="bi bi-translate fs-5"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3 bg-dark border border-white border-opacity-10 py-2" style="border-radius:12px;">
                    <li>
                        <a class="dropdown-item py-2 px-4 small {{ App::getLocale() == 'es' ? 'active' : '' }}" href="{{ route('switch.locale', 'es') }}">
                            <span class="me-2">🇪🇸</span> Español (LATAM)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 px-4 small {{ App::getLocale() == 'en' ? 'active' : '' }}" href="{{ route('switch.locale', 'en') }}">
                            <span class="me-2">🇺🇸</span> English (US)
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Notifications -->
            @php $notifications = auth()->user()->unreadNotifications->take(5); @endphp
            <div class="dropdown">
                <button class="btn btn-link text-muted position-relative p-0" data-bs-toggle="dropdown">
                    <i class="bi bi-bell-fill fs-5"></i>
                    @if($notifications->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.5rem; padding: 0.35em 0.5em;">
                            {{ $notifications->count() }}
                        </span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end p-0" style="width:300px;">
                    <div class="p-3 border-bottom border-secondary border-opacity-10">
                        <div class="st-title-sm" style="font-size:0.6rem;">Recent Alerts</div>
                    </div>
                    @forelse($notifications as $notif)
                        @php
                            $isStock  = ($notif->data['type'] ?? '') === 'stock_alert';
                            $notifUrl = $isStock
                                ? route('spares.index', ['search' => $notif->data['code'] ?? ''])
                                : ($notif->data['work_order_id'] ?? null
                                    ? route('work-orders.show', $notif->data['work_order_id'])
                                    : route('work-orders.index'));
                            $notifMsg = $notif->data['message'] ?? 'Notificación';
                        @endphp
                        <a href="{{ $notifUrl }}" class="dropdown-item py-3 border-bottom border-secondary border-opacity-10">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi {{ $isStock ? 'bi-box-seam text-warning' : 'bi-lightning-charge text-primary' }} mt-1 flex-shrink-0"></i>
                                <div>
                                    <div class="fw-700 mb-1" style="font-size:0.8rem; white-space:normal;">{{ $notifMsg }}</div>
                                    <div class="extra-small text-muted">{{ $notif->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-4 text-center st-lead">No pending alerts</div>
                    @endforelse
                </div>
            </div>

            <div class="d-none d-md-block h-100 bg-outline" style="width:1px; height:20px; background:rgba(255,255,255,0.1);"></div>
            <div class="st-title-sm d-none d-md-block">{{ now()->format('D, d M') }}</div>
        </div>
    </div>

    <!-- Alertas flash -->
    <div class="px-4 pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <div class="page-content">
        @yield('content')
    </div>

    <!-- Floating Action Button -->
    <a href="{{ route('work-orders.create') }}" class="fab" title="Nueva Orden de Trabajo">
        <i class="bi bi-plus-lg fs-3"></i>
    </a>
</div>

<!-- Bootstrap JS -->
<script src="{{ asset('js/vendor/bootstrap.bundle.min.js') }}"></script>
<!-- App Logic -->
<script src="{{ asset('js/app.js') }}"></script>

@stack('scripts')
</body>
</html>
