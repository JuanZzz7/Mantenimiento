@extends('layouts.app')
@section('title', $spare->name . ' — Inventario')

@section('content')
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

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:.75rem;letter-spacing:.05em;text-transform:uppercase;font-weight:600;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary text-decoration-none">KINETIC</a></li>
            <li class="breadcrumb-item"><a href="{{ route('spares.index') }}" class="text-muted text-decoration-none opacity-75">Centro de Inventario</a></li>
            <li class="breadcrumb-item active text-muted opacity-50" aria-current="page">{{ $spare->code }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-4 mb-5">
        <div class="d-flex align-items-start gap-4">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:64px;height:64px;background:rgba(255,255,255,0.05);border:1px solid {{ $sg }}44;">
                <i class="bi bi-nut-fill" style="font-size:1.8rem;color:{{ $sg }};text-shadow:0 0 15px {{ $sg }}77;"></i>
            </div>
            <div>
                <h1 class="fw-bold mb-1" style="font-size:1.8rem;letter-spacing:-.02em;">{{ $spare->name }}</h1>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <span class="badge bg-dark border border-secondary border-opacity-30 text-primary font-monospace fs-6">{{ $spare->code }}</span>
                    <span class="badge bg-{{ $sc }} bg-opacity-15 text-{{ $sc }} border border-{{ $sc }} border-opacity-30"
                          style="font-size:.75rem;">{{ $st }}</span>
                    @if($spare->category)
                        <span class="badge bg-secondary bg-opacity-25 text-white text-opacity-70">{{ ucfirst($spare->category) }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-primary rounded-pill px-4 py-2 fw-bold"
                    data-bs-toggle="modal" data-bs-target="#adjModalShow">
                <i class="bi bi-arrow-left-right me-2"></i>Ajustar Stock
            </button>
            @if(auth()->user()->isAdmin() || auth()->user()->isTecnico())
                <a href="{{ route('spares.edit', $spare) }}"
                   class="btn btn-outline-secondary rounded-pill px-3 py-2">
                    <i class="bi bi-pencil me-1"></i>Editar
                </a>
            @endif
            <a href="{{ route('spares.index') }}"
               class="btn btn-outline-secondary rounded-pill px-3 py-2 text-muted">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── Columna izquierda: datos del repuesto ─────────────────────── --}}
        <div class="col-12 col-lg-4">

            {{-- Stock Card --}}
            <div class="p-4 rounded-3 mb-4" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);">
                <div class="st-title-sm mb-3">Estado de Stock</div>

                <div class="text-center mb-4">
                    <div class="text-white fw-bold mb-1" style="font-size:3.5rem;line-height:1;">
                        {{ $spare->stock }}
                    </div>
                    <div class="text-muted">{{ $spare->unit }}(s) disponibles</div>
                </div>

                <div class="progress mb-3" style="height:10px;border-radius:5px;background:rgba(255,255,255,0.06);">
                    <div class="progress-bar bg-{{ $sc }}"
                         style="width:{{ $pct }}%;border-radius:5px;box-shadow:0 0 10px {{ $sg }}88;"></div>
                </div>

                <div class="d-flex justify-content-between text-muted small mb-4">
                    <span>Mínimo: <strong class="text-white">{{ $spare->stock_min }}</strong></span>
                    <span>Nivel: <strong class="text-{{ $sc }}">{{ $st }}</strong></span>
                </div>

                {{-- Métricas rápidas --}}
                <div class="row g-2">
                    <div class="col-6">
                        <div class="p-2 rounded-2 text-center" style="background:rgba(255,255,255,0.04);">
                            <div class="text-success fw-bold">${{ number_format($spare->price, 2) }}</div>
                            <div class="text-muted" style="font-size:.7rem;">Precio / unidad</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2 text-center" style="background:rgba(255,255,255,0.04);">
                            <div class="text-success fw-bold">${{ number_format($spare->stock * $spare->price, 2) }}</div>
                            <div class="text-muted" style="font-size:.7rem;">Valor total</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2 text-center" style="background:rgba(255,255,255,0.04);">
                            <div class="text-white fw-bold">{{ $totalUsed }}</div>
                            <div class="text-muted" style="font-size:.7rem;">Unidades usadas</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-2 text-center" style="background:rgba(255,255,255,0.04);">
                            <div class="text-white fw-bold">{{ $usageHistory->count() }}</div>
                            <div class="text-muted" style="font-size:.7rem;">Órdenes de trabajo</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info del repuesto --}}
            <div class="p-4 rounded-3" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <div class="st-title-sm mb-3">Información del Repuesto</div>
                <dl class="mb-0" style="font-size:.9rem;">
                    <dt class="text-muted fw-normal mb-1">Código</dt>
                    <dd class="text-primary font-monospace fw-bold mb-3">{{ $spare->code }}</dd>

                    <dt class="text-muted fw-normal mb-1">Unidad de medida</dt>
                    <dd class="text-white mb-3">{{ ucfirst($spare->unit) }}</dd>

                    @if($spare->category)
                        <dt class="text-muted fw-normal mb-1">Categoría</dt>
                        <dd class="text-white mb-3">{{ ucfirst($spare->category) }}</dd>
                    @endif

                    @if($spare->location)
                        <dt class="text-muted fw-normal mb-1">
                            <i class="bi bi-geo-alt me-1"></i>Ubicación en bodega
                        </dt>
                        <dd class="text-white mb-3">{{ $spare->location }}</dd>
                    @endif

                    @if($spare->supplier)
                        <dt class="text-muted fw-normal mb-1">
                            <i class="bi bi-truck me-1"></i>Proveedor
                        </dt>
                        <dd class="text-white mb-3">{{ $spare->supplier }}</dd>
                    @endif

                    @if($spare->description)
                        <dt class="text-muted fw-normal mb-1">Descripción</dt>
                        <dd class="text-white mb-0" style="line-height:1.6;">{{ $spare->description }}</dd>
                    @endif

                    <dt class="text-muted fw-normal mb-1 mt-3">Última actualización</dt>
                    <dd class="text-white mb-0">{{ $spare->updated_at->format('d/m/Y H:i') }}</dd>
                </dl>
            </div>
        </div>

        {{-- ── Columna derecha: historial de uso ───────────────────────────── --}}
        <div class="col-12 col-lg-8">

            {{-- Alertas urgentes --}}
            @if($spare->stock <= 0)
                <div class="alert alert-danger d-flex align-items-center gap-3 mb-4" style="border-radius:12px;">
                    <i class="bi bi-exclamation-octagon-fill fs-3 flex-shrink-0"></i>
                    <div>
                        <div class="fw-bold mb-1">⚠ Repuesto AGOTADO</div>
                        <div style="font-size:.9rem;">No hay unidades disponibles. Se necesita reposición urgente para no detener las operaciones de mantenimiento.</div>
                    </div>
                </div>
            @elseif($spare->stock <= $spare->stock_min)
                <div class="alert mb-4 d-flex align-items-center gap-3"
                     style="border-radius:12px;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.3);color:#f59e0b;">
                    <i class="bi bi-exclamation-triangle-fill fs-3 flex-shrink-0"></i>
                    <div>
                        <div class="fw-bold mb-1">Stock bajo el mínimo</div>
                        <div style="font-size:.9rem;">Hay {{ $spare->stock }} {{ $spare->unit }}(s). El stock mínimo recomendado es {{ $spare->stock_min }}. Se recomienda reabastecer pronto.</div>
                    </div>
                </div>
            @endif

            {{-- Historial de uso en órdenes de trabajo --}}
            <div class="p-4 rounded-3 mb-4" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="st-title-sm">Historial de uso en Órdenes de Trabajo</div>
                    @if($totalUsed > 0)
                        <span class="badge bg-primary bg-opacity-20 text-primary border border-primary border-opacity-25">
                            {{ $totalUsed }} {{ $spare->unit }}(s) usados en total
                        </span>
                    @endif
                </div>

                @forelse($usageHistory as $order)
                    @php
                        $orderStatus = match($order->status) {
                            'pendiente'  => ['color'=>'warning', 'label'=>'Pendiente'],
                            'en_proceso' => ['color'=>'primary', 'label'=>'En proceso'],
                            'completada' => ['color'=>'success', 'label'=>'Completada'],
                            'cancelada'  => ['color'=>'secondary','label'=>'Cancelada'],
                            default      => ['color'=>'secondary', 'label'=>ucfirst($order->status)],
                        };
                    @endphp
                    <div class="d-flex align-items-start gap-3 py-3 border-bottom border-secondary border-opacity-10 last-child-no-border">
                        <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:36px;height:36px;background:rgba(0,102,255,.1);">
                            <i class="bi bi-lightning-charge-fill text-primary" style="font-size:.9rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <a href="{{ route('work-orders.show', $order) }}"
                                   class="text-white fw-bold text-decoration-none hover-primary">
                                    {{ $order->code ?? 'OT-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                </a>
                                <span class="badge bg-{{ $orderStatus['color'] }} bg-opacity-15 text-{{ $orderStatus['color'] }} border border-{{ $orderStatus['color'] }} border-opacity-25"
                                      style="font-size:.65rem;">{{ $orderStatus['label'] }}</span>
                            </div>
                            <div class="text-muted small mb-1">
                                <i class="bi bi-cpu me-1"></i>
                                {{ $order->asset->name ?? 'Activo desconocido' }}
                            </div>
                            <div class="d-flex flex-wrap gap-3 text-muted" style="font-size:.78rem;">
                                <span>
                                    <i class="bi bi-boxes me-1"></i>
                                    <strong class="text-white">{{ $order->pivot->quantity }}</strong> {{ $spare->unit }}(s) usados
                                </span>
                                @if($order->assignedUser)
                                    <span>
                                        <i class="bi bi-person me-1"></i>{{ $order->assignedUser->name }}
                                    </span>
                                @endif
                                <span>
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ \Carbon\Carbon::parse($order->pivot->created_at)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-x text-muted" style="font-size:2.5rem;opacity:.4;"></i>
                        <p class="text-muted mt-3 mb-0">Este repuesto aún no ha sido usado en ninguna orden de trabajo.</p>
                    </div>
                @endforelse
            </div>

            {{-- Gestión de inventario --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isTecnico())
                <div class="p-4 rounded-3" style="background:rgba(0,102,255,0.04);border:1px solid rgba(0,102,255,0.15);">
                    <div class="st-title-sm mb-3 text-primary">Gestión de Inventario</div>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('spares.edit', $spare) }}" class="btn btn-outline-primary rounded-pill px-4 py-2">
                            <i class="bi bi-pencil-square me-2"></i>Editar todos los datos
                        </a>
                        <form action="{{ route('spares.destroy', $spare) }}" method="POST"
                              onsubmit="return confirm('¿Estás seguro de eliminar &laquo;{{ addslashes($spare->name) }}&raquo; del inventario? Esta acción no se puede deshacer.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-4 py-2">
                                <i class="bi bi-trash3 me-2"></i>Eliminar del inventario
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- ── Modal de ajuste de stock ─────────────────────────────────────────── --}}
    <div class="modal fade" id="adjModalShow" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
            <div class="modal-content border-0 shadow-lg" style="background:var(--st-surface);border-radius:16px;">
                <div class="modal-header border-bottom border-secondary border-opacity-20 p-4">
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-0">
                            <i class="bi bi-arrow-left-right text-primary me-2"></i>Ajustar Stock
                        </h5>
                        <div class="text-muted small mt-1">{{ $spare->name }}</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('spares.adjust-stock', $spare) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        {{-- Stock actual --}}
                        <div class="text-center mb-4 p-3 rounded-3" style="background:rgba(255,255,255,0.04);">
                            <div class="text-muted small mb-1">Stock actual</div>
                            <div class="text-white fw-bold" style="font-size:3rem;line-height:1;">
                                {{ $spare->stock }}<span class="fs-5 text-muted fw-normal ms-2">{{ $spare->unit }}(s)</span>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-{{ $sc }} bg-opacity-15 text-{{ $sc }} border border-{{ $sc }} border-opacity-25">{{ $st }}</span>
                            </div>
                        </div>

                        {{-- Botones rápidos --}}
                        <div class="mb-4">
                            <div class="text-white text-opacity-70 small fw-bold mb-2">Ajuste rápido</div>
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach([-20, -10, -5, -1, 1, 5, 10, 20] as $q)
                                    <button type="button"
                                            class="btn btn-sm {{ $q < 0 ? 'btn-outline-danger' : 'btn-outline-success' }} border-opacity-40 px-3"
                                            onclick="document.getElementById('adjInputShow').value = {{ $q }}">
                                        {{ $q > 0 ? '+' : '' }}{{ $q }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Input --}}
                        <div class="mb-3">
                            <label class="form-label text-white text-opacity-75 small fw-bold">
                                Cantidad <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="adjustment" id="adjInputShow"
                                   class="form-control bg-dark border-secondary border-opacity-25 text-white py-3 text-center fw-bold"
                                   style="font-size:1.8rem;"
                                   placeholder="0" required>
                            <div class="form-text text-center text-muted mt-2">
                                Positivo para ingresar • Negativo para retirar
                            </div>
                        </div>

                        <div>
                            <label class="form-label text-white text-opacity-75 small fw-bold">Motivo (opcional)</label>
                            <input type="text" name="reason"
                                   class="form-control bg-dark border-secondary border-opacity-25 text-white"
                                   placeholder="Ej: Compra nueva, uso en OT-045, conteo físico...">
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary border-opacity-20 p-3 d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary flex-grow-1" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">
                            <i class="bi bi-check-lg me-2"></i>Confirmar ajuste
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<link href="{{ asset('css/spares.css') }}" rel="stylesheet">
@endpush
