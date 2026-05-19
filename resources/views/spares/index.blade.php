@extends('layouts.app')
@section('title', 'Centro de Inventario')

@section('content')

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:.75rem;letter-spacing:.05em;text-transform:uppercase;font-weight:600;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary text-decoration-none">KINETIC</a></li>
            <li class="breadcrumb-item active text-muted opacity-75" aria-current="page">{{ __('Inventory Hub') }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-5">
        <div>
            <h1 class="fw-bold mb-1" style="font-size:2rem;letter-spacing:-.02em;">
                <i class="bi bi-boxes text-primary me-3"></i>{{ __('Inventory Center') }}
            </h1>
            <p class="text-white text-opacity-50 mb-0">
                {{ __('Control of spare parts, consumables and materials.') }}
            </p>
        </div>
        @if(auth()->user()->isAdmin() || auth()->user()->isTecnico())
            <a href="{{ route('spares.create') }}"
               class="btn btn-primary rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2"
               style="background:linear-gradient(135deg,var(--st-primary),#4d94ff);border:none;white-space:nowrap;">
                <i class="bi bi-plus-circle-fill fs-5"></i> {{ __('New Spare') }}
            </a>
        @endif
    </div>

    {{-- ── Tarjetas de resumen ──────────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <a href="{{ route('spares.index') }}" class="text-decoration-none">
                <div class="p-3 rounded-3 h-100 {{ !request()->hasAny(['low_stock','out_of_stock']) && !request('search') && !request('category') ? 'border border-primary border-opacity-50' : '' }}"
                     style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);transition:.2s;">
                    <div class="text-muted small fw-bold text-uppercase mb-2" style="letter-spacing:.06em;font-size:.7rem;">{{ __('Total Items') }}</div>
                    <div class="text-white fw-bold" style="font-size:2rem;line-height:1;">{{ $stats['total'] }}</div>
                    <div class="text-muted mt-1" style="font-size:.7rem;">{{ __('references in catalog') }}</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-3 h-100" style="background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.2);">
                <div class="text-success small fw-bold text-uppercase mb-2" style="letter-spacing:.06em;font-size:.7rem;">{{ __('Warehouse Value') }}</div>
                <div class="text-success fw-bold" style="font-size:2rem;line-height:1;">${{ number_format($stats['value'], 0) }}</div>
                <div class="text-muted mt-1" style="font-size:.7rem;">{{ __('total stock value') }}</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('spares.index', array_merge(request()->query(), ['low_stock' => 1])) }}" class="text-decoration-none">
                <div class="p-3 rounded-3 h-100 {{ request('low_stock') ? 'border border-warning border-opacity-50' : '' }}"
                     style="background:rgba(245,158,11,0.07);border:1px solid rgba(245,158,11,0.2);transition:.2s;">
                    <div class="text-warning small fw-bold text-uppercase mb-2" style="letter-spacing:.06em;font-size:.7rem;">{{ __('Low Stock') }}</div>
                    <div class="text-warning fw-bold" style="font-size:2rem;line-height:1;">{{ $stats['low_stock'] }}</div>
                    <div class="text-muted mt-1" style="font-size:.7rem;">{{ __('items below minimum') }} ↗</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('spares.index', array_merge(request()->query(), ['out_of_stock' => 1])) }}" class="text-decoration-none">
                <div class="p-3 rounded-3 h-100 {{ request('out_of_stock') ? 'border border-danger border-opacity-50' : '' }}"
                     style="background:rgba(239,68,68,0.07);border:1px solid rgba(239,68,68,0.2);transition:.2s;">
                    <div class="text-danger small fw-bold text-uppercase mb-2" style="letter-spacing:.06em;font-size:.7rem;">{{ __('Out of Stock') }}</div>
                    <div class="text-danger fw-bold" style="font-size:2rem;line-height:1;">{{ $stats['out_stock'] }}</div>
                    <div class="text-muted mt-1" style="font-size:.7rem;">{{ __('no units available') }} ↗</div>
                </div>
            </a>
        </div>
    </div>

    {{-- ── Barra de filtros ─────────────────────────────────────────────────── --}}
    <div class="p-3 mb-4 rounded-3" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);">
        <form method="GET" action="{{ route('spares.index') }}" class="row g-2 align-items-end">
            {{-- Búsqueda --}}
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary border-opacity-25 text-muted border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search"
                           class="form-control bg-dark border-secondary border-opacity-25 text-white border-start-0 py-2"
                           placeholder="Nombre, código, proveedor..."
                           value="{{ request('search') }}">
                </div>
            </div>
            {{-- Categoría --}}
            <div class="col-6 col-md-2">
                <select name="category" class="form-select bg-dark border-secondary border-opacity-25 text-white py-2">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ ucfirst($cat) }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Ordenar --}}
            <div class="col-6 col-md-2">
                <select name="sort" class="form-select bg-dark border-secondary border-opacity-25 text-white py-2">
                    <option value="updated_at" {{ request('sort','updated_at') == 'updated_at' ? 'selected':'' }}>Recientes</option>
                    <option value="name"       {{ request('sort') == 'name'       ? 'selected':'' }}>Nombre A-Z</option>
                    <option value="stock"      {{ request('sort') == 'stock'      ? 'selected':'' }}>Stock</option>
                    <option value="price"      {{ request('sort') == 'price'      ? 'selected':'' }}>Precio</option>
                </select>
            </div>
            {{-- Switches --}}
            <div class="col-md-2">
                <div class="d-flex flex-column gap-1">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="low_stock" id="sw_low" value="1"
                               {{ request('low_stock') ? 'checked':'' }}
                               onchange="this.form.submit()">
                        <label class="form-check-label text-white text-opacity-75 small" for="sw_low">Solo stock bajo</label>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="out_of_stock" id="sw_out" value="1"
                               {{ request('out_of_stock') ? 'checked':'' }}
                               onchange="this.form.submit()">
                        <label class="form-check-label text-white text-opacity-75 small" for="sw_out">Solo agotados</label>
                    </div>
                </div>
            </div>
            {{-- Botones --}}
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1 py-2 fw-bold">
                    <i class="bi bi-funnel me-1"></i>Filtrar
                </button>
                @if(request()->hasAny(['search','category','low_stock','out_of_stock','sort']))
                    <a href="{{ route('spares.index') }}" class="btn btn-outline-secondary py-2" title="Limpiar filtros">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Resultado count --}}
    @if(request()->hasAny(['search','category','low_stock','out_of_stock']))
        <div class="mb-3 text-muted small">
            <i class="bi bi-info-circle me-1"></i>
            Mostrando <strong class="text-white">{{ $spares->total() }}</strong> resultado(s)
            @if(request('search')) con "<strong class="text-white">{{ request('search') }}</strong>"@endif
            — <a href="{{ route('spares.index') }}" class="text-primary text-decoration-none">ver todos</a>
        </div>
    @endif

    {{-- ── Tabla de inventario ──────────────────────────────────────────────── --}}
    @forelse($spares as $spare)
        @php
            if ($spare->stock <= 0) {
                $sc = 'danger'; $st = 'AGOTADO'; $sg = '#ef4444';
            } elseif ($spare->stock <= $spare->stock_min) {
                $sc = 'warning'; $st = 'STOCK BAJO'; $sg = '#f59e0b';
            } else {
                $sc = 'success'; $st = 'DISPONIBLE'; $sg = '#10b981';
            }
            $pct = $spare->stock_min > 0
                ? min(100, max(4, round($spare->stock / $spare->stock_min * 50)))
                : 100;
        @endphp

        <div class="mb-3 rounded-3 spare-row" id="spare-{{ $spare->id }}"
             style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,{{ $sc === 'danger' ? '0.12' : ($sc === 'warning' ? '0.07' : '0.05') }});transition:.2s;">

            <div class="row g-0 align-items-center p-3">

                {{-- Status dot + Icon --}}
                <div class="col-auto me-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:44px;height:44px;background:rgba(255,255,255,0.05);border:1px solid {{ $sg }}44;flex-shrink:0;">
                        <i class="bi bi-nut-fill" style="font-size:1.2rem;color:{{ $sg }};"></i>
                    </div>
                </div>

                {{-- Nombre + Código --}}
                <div class="col-12 col-md-3 mb-2 mb-md-0">
                    <a href="{{ route('spares.show', $spare) }}" class="text-white text-decoration-none fw-bold spare-name">
                        {{ $spare->name }}
                    </a>
                    <div class="mt-1 d-flex flex-wrap gap-1">
                        <span class="badge bg-dark border border-secondary border-opacity-25 text-primary font-monospace"
                              style="font-size:.7rem;">{{ $spare->code }}</span>
                        @if($spare->category)
                            <span class="badge bg-secondary bg-opacity-20 text-white text-opacity-60"
                                  style="font-size:.68rem;">{{ ucfirst($spare->category) }}</span>
                        @endif
                    </div>
                </div>

                {{-- Estado + barra de stock --}}
                <div class="col-12 col-md-3 mb-2 mb-md-0">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-{{ $sc }} bg-opacity-15 text-{{ $sc }} border border-{{ $sc }} border-opacity-25"
                              style="font-size:.65rem;letter-spacing:.05em;">{{ $st }}</span>
                        <span class="text-white fw-bold">{{ $spare->stock }}</span>
                        <span class="text-muted small">/ mín {{ $spare->stock_min }} {{ $spare->unit }}</span>
                    </div>
                    <div class="progress" style="height:5px;border-radius:3px;background:rgba(255,255,255,0.06);">
                        <div class="progress-bar bg-{{ $sc }}" style="width:{{ $pct }}%;box-shadow:0 0 6px {{ $sg }}88;"></div>
                    </div>
                </div>

                {{-- Ubicación + Proveedor --}}
                <div class="col-12 col-md-2 mb-2 mb-md-0">
                    @if($spare->location)
                        <div class="text-muted small mb-1">
                            <i class="bi bi-geo-alt me-1"></i>{{ $spare->location }}
                        </div>
                    @endif
                    @if($spare->supplier)
                        <div class="text-muted small">
                            <i class="bi bi-truck me-1"></i>{{ Str::limit($spare->supplier, 20) }}
                        </div>
                    @endif
                    @if(!$spare->location && !$spare->supplier)
                        <span class="text-muted small opacity-40">—</span>
                    @endif
                </div>

                {{-- Precio --}}
                <div class="col-6 col-md-1 text-md-end mb-2 mb-md-0">
                    <div class="text-success fw-bold" style="font-size:.95rem;">${{ number_format($spare->price,2) }}</div>
                    <div class="text-muted" style="font-size:.68rem;">{{ __('per') }} {{ $spare->unit }}</div>
                </div>

                {{-- Acciones --}}
                <div class="col-6 col-md-2 d-flex justify-content-end align-items-center gap-2">
                    {{-- Ajuste rápido --}}
                    <button class="btn btn-sm btn-outline-secondary border-opacity-25 text-white py-1 px-2"
                            data-bs-toggle="modal" data-bs-target="#adjModal{{ $spare->id }}"
                            title="{{ __('Adjust Stock') }}">
                        <i class="bi bi-sliders me-1"></i>Stock
                    </button>

                    {{-- Ver detalle --}}
                    <a href="{{ route('spares.show', $spare) }}"
                       class="btn btn-sm btn-outline-primary border-opacity-25 py-1 px-2" title="{{ __('Inspect Detail') }}">
                        <i class="bi bi-eye"></i>
                    </a>

                    @if(auth()->user()->isAdmin() || auth()->user()->isTecnico())
                        {{-- Editar --}}
                        <a href="{{ route('spares.edit', $spare) }}"
                           class="btn btn-sm btn-outline-secondary border-opacity-25 text-white py-1 px-2" title="{{ __('Modify Config') }}">
                            <i class="bi bi-pencil"></i>
                        </a>

                        {{-- Eliminar --}}
                        <form action="{{ route('spares.destroy', $spare) }}" method="POST"
                               onsubmit="return confirm('{{ __('Decommission') }} «{{ addslashes($spare->name) }}»?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger border-opacity-25 py-1 px-2" title="{{ __('Decommission') }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Barra urgente si está agotado --}}
            @if($spare->stock <= 0)
                <div class="px-3 pb-2">
                    <div class="alert alert-danger py-1 px-2 mb-0 d-flex align-items-center gap-2" style="font-size:.78rem;border-radius:6px;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        {{ __('Out of stock: This spare part needs urgent replacement.') }}
                    </div>
                </div>
            @elseif($spare->stock <= $spare->stock_min)
                <div class="px-3 pb-2">
                    <div class="alert alert-warning py-1 px-2 mb-0 d-flex align-items-center gap-2" style="font-size:.78rem;border-radius:6px;background:rgba(245,158,11,0.1);border-color:rgba(245,158,11,0.3);color:#f59e0b;">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        {{ __('Low stock: Only :stock :unit left. Recommended minimum: :min.', ['stock' => $spare->stock, 'unit' => $spare->unit, 'min' => $spare->stock_min]) }}
                    </div>
                </div>
            @endif
        </div>

        {{-- Modal de ajuste de stock --}}
        <div class="modal fade" id="adjModal{{ $spare->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
                <div class="modal-content border-0 shadow-lg" style="background:var(--st-surface);border-radius:16px;">
                    <div class="modal-header border-bottom border-secondary border-opacity-20 p-4">
                        <div>
                            <h5 class="modal-title text-white fw-bold mb-0">
                                <i class="bi bi-arrow-left-right text-primary me-2"></i>{{ __('Adjust Stock') }}
                            </h5>
                            <div class="text-muted small mt-1">{{ $spare->name }} <span class="text-primary font-monospace ms-2">{{ $spare->code }}</span></div>
                        </div>
                        <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('spares.adjust-stock', $spare) }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            {{-- Stock actual --}}
                            <div class="text-center mb-4 p-3 rounded-3" style="background:rgba(255,255,255,0.04);">
                                <div class="text-muted small mb-1">Stock actual</div>
                                <div class="text-white fw-bold" style="font-size:2.5rem;line-height:1;">
                                    {{ $spare->stock }}
                                    <span class="fs-6 text-muted fw-normal ms-1">{{ $spare->unit }}(s)</span>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-{{ $sc }} bg-opacity-15 text-{{ $sc }} border border-{{ $sc }} border-opacity-25">{{ $st }}</span>
                                </div>
                            </div>

                            {{-- Botones rápidos --}}
                            <div class="mb-3">
                                <label class="form-label text-white text-opacity-75 small fw-bold mb-2">Ajuste rápido</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    @foreach([-10, -5, -1, 1, 5, 10] as $q)
                                        <button type="button"
                                                class="btn btn-sm {{ $q < 0 ? 'btn-outline-danger' : 'btn-outline-success' }} border-opacity-40 px-3"
                                                onclick="setAdjustment({{ $spare->id }}, {{ $q }})">
                                            {{ $q > 0 ? '+' : '' }}{{ $q }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Input personalizado --}}
                            <div class="mb-3">
                                <label class="form-label text-white text-opacity-75 small fw-bold">
                                    Cantidad personalizada <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="adjustment" id="adjInput{{ $spare->id }}"
                                       class="form-control bg-dark border-secondary border-opacity-25 text-white py-3 text-center fw-bold"
                                       style="font-size:1.5rem;"
                                       placeholder="Ej: -3 o +15"
                                       required>
                                <div class="form-text text-center text-muted mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Positivo (+) para ingresar • Negativo (-) para retirar
                                </div>
                            </div>

                            {{-- Motivo --}}
                            <div>
                                <label class="form-label text-white text-opacity-75 small fw-bold">Motivo (opcional)</label>
                                <input type="text" name="reason"
                                       class="form-control bg-dark border-secondary border-opacity-25 text-white"
                                       placeholder="Ej: Compra, uso en OT-045, inventario físico...">
                            </div>
                        </div>
                        <div class="modal-footer border-top border-secondary border-opacity-20 p-3 d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary flex-grow-1" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">
                                <i class="bi bi-check-lg me-1"></i>Confirmar ajuste
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @empty
        <div class="text-center py-5 rounded-3" style="background:rgba(255,255,255,0.02);border:1px dashed rgba(255,255,255,0.08);">
            <i class="bi bi-box-seam text-muted" style="font-size:3rem;opacity:.4;"></i>
            <h4 class="text-white fw-bold mt-3 mb-2">
                {{ request()->hasAny(['search','category','low_stock','out_of_stock']) ? 'Sin resultados' : 'Inventario vacío' }}
            </h4>
            <p class="text-muted mb-4">
                {{ request()->hasAny(['search','category','low_stock','out_of_stock'])
                    ? 'No hay repuestos que coincidan con los filtros aplicados.'
                    : 'Aún no hay repuestos registrados en el sistema.' }}
            </p>
            <div class="d-flex gap-2 justify-content-center">
                @if(request()->hasAny(['search','category','low_stock','out_of_stock']))
                    <a href="{{ route('spares.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-lg me-1"></i>Limpiar filtros
                    </a>
                @endif
                @if(auth()->user()->isAdmin() || auth()->user()->isTecnico())
                    <a href="{{ route('spares.create') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-plus-lg me-1"></i>Agregar repuesto
                    </a>
                @endif
            </div>
        </div>
    @endforelse

    {{-- Paginación --}}
    @if($spares->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $spares->links() }}
        </div>
    @endif

@endsection

@push('styles')
<link href="{{ asset('css/spares.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('js/spares.js') }}"></script>
@endpush
