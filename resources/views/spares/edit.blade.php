@extends('layouts.app')
@section('title', 'Editar ' . $spare->name . ' — Inventario')

@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:.75rem;letter-spacing:.05em;text-transform:uppercase;font-weight:600;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary text-decoration-none">KINETIC</a></li>
            <li class="breadcrumb-item"><a href="{{ route('spares.index') }}" class="text-muted text-decoration-none opacity-75">Centro de Inventario</a></li>
            <li class="breadcrumb-item"><a href="{{ route('spares.show', $spare) }}" class="text-muted text-decoration-none opacity-75">{{ $spare->code }}</a></li>
            <li class="breadcrumb-item active text-muted opacity-50" aria-current="page">Editar</li>
        </ol>
    </nav>

    <div class="mb-5">
        <h1 class="fw-bold mb-1" style="font-size:1.9rem;letter-spacing:-.02em;">
            <i class="bi bi-pencil-square text-primary me-3"></i>Editar Repuesto
        </h1>
        <p class="text-white text-opacity-50 mb-0">
            Modificando: <strong class="text-white text-opacity-80">{{ $spare->name }}</strong>
            <span class="text-primary font-monospace ms-2">{{ $spare->code }}</span>
        </p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Corrige los errores:</strong>
            <ul class="mb-0 mt-2 ps-3">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="rounded-3 p-4 p-md-5" style="max-width:860px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);">
        <form method="POST" action="{{ route('spares.update', $spare) }}">
            @csrf
            @method('PUT')

            {{-- ── Sección 1: Identificación ──────────────────────────────── --}}
            <div class="mb-5">
                <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom border-secondary border-opacity-15">
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:28px;height:28px;background:rgba(0,102,255,.15);">
                        <i class="bi bi-tag-fill text-primary" style="font-size:.8rem;"></i>
                    </div>
                    <span class="fw-bold text-white text-opacity-70 text-uppercase small" style="letter-spacing:.1em;">1. Identificación</span>
                </div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <label for="code" class="form-label small fw-bold text-muted">CÓDIGO / SKU <span class="text-danger">*</span></label>
                        <input type="text" id="code" name="code"
                               class="form-control bg-dark bg-opacity-50 border-secondary border-opacity-25 text-white py-2 @error('code') is-invalid @enderror"
                               value="{{ old('code', $spare->code) }}" required>
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8">
                        <label for="name" class="form-label small fw-bold text-muted">NOMBRE DEL REPUESTO <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name"
                               class="form-control bg-dark bg-opacity-50 border-secondary border-opacity-25 text-white py-2 @error('name') is-invalid @enderror"
                               value="{{ old('name', $spare->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label small fw-bold text-muted">DESCRIPCIÓN (opcional)</label>
                        <textarea id="description" name="description" rows="2"
                                  class="form-control bg-dark bg-opacity-50 border-secondary border-opacity-25 text-white">{{ old('description', $spare->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ── Sección 2: Stock y precio ───────────────────────────────── --}}
            <div class="mb-5">
                <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom border-secondary border-opacity-15">
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:28px;height:28px;background:rgba(0,102,255,.15);">
                        <i class="bi bi-bar-chart-fill text-primary" style="font-size:.8rem;"></i>
                    </div>
                    <span class="fw-bold text-white text-opacity-70 text-uppercase small" style="letter-spacing:.1em;">2. Stock y Precio</span>
                </div>

                <div class="p-3 mb-4 rounded-3 d-flex align-items-center gap-3"
                     style="background:rgba(0,102,255,.06);border:1px solid rgba(0,102,255,.2);">
                    <i class="bi bi-info-circle text-primary fs-5 flex-shrink-0"></i>
                    <div class="text-white text-opacity-70 small">
                        Para movimientos de stock (ingresos y salidas), usa el botón <strong class="text-primary">"Ajustar Stock"</strong>
                        desde la vista de detalle. Aquí solo ajusta el número si detectas un error de registro.
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-sm-3">
                        <label for="unit" class="form-label small fw-bold text-muted">UNIDAD <span class="text-danger">*</span></label>
                        <select id="unit" name="unit"
                                class="form-select bg-dark bg-opacity-50 border-secondary border-opacity-25 text-white py-2">
                            @foreach(['unidad'=>'Unidad','par'=>'Par','kit'=>'Kit','litro'=>'Litro','metro'=>'Metro','kg'=>'Kilogramo','caja'=>'Caja','rollo'=>'Rollo','galón'=>'Galón'] as $v=>$l)
                                <option value="{{ $v }}" {{ old('unit', $spare->unit) === $v ? 'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="stock" class="form-label small fw-bold text-muted">STOCK EN REGISTRO <span class="text-danger">*</span></label>
                        <input type="number" id="stock" name="stock" min="0"
                               class="form-control bg-dark bg-opacity-50 border-secondary border-opacity-25 text-white py-2 @error('stock') is-invalid @enderror"
                               value="{{ old('stock', $spare->stock) }}" required>
                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-3">
                        <label for="stock_min" class="form-label small fw-bold text-muted">STOCK MÍNIMO <span class="text-danger">*</span></label>
                        <input type="number" id="stock_min" name="stock_min" min="0"
                               class="form-control bg-dark bg-opacity-50 border-secondary border-opacity-25 text-white py-2 @error('stock_min') is-invalid @enderror"
                               value="{{ old('stock_min', $spare->stock_min) }}" required>
                        @error('stock_min')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-3">
                        <label for="price" class="form-label small fw-bold text-muted">PRECIO UNIT. <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary border-opacity-25 text-muted">$</span>
                            <input type="number" id="price" name="price" step="0.01" min="0"
                                   class="form-control bg-dark bg-opacity-50 border-secondary border-opacity-25 text-white py-2 @error('price') is-invalid @enderror"
                                   value="{{ old('price', $spare->price) }}" required>
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Sección 3: Ubicación y proveedor ────────────────────────── --}}
            <div class="mb-5">
                <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom border-secondary border-opacity-15">
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:28px;height:28px;background:rgba(0,102,255,.15);">
                        <i class="bi bi-geo-alt-fill text-primary" style="font-size:.8rem;"></i>
                    </div>
                    <span class="fw-bold text-white text-opacity-70 text-uppercase small" style="letter-spacing:.1em;">3. Ubicación y Proveedor</span>
                </div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <label for="category" class="form-label small fw-bold text-muted">CATEGORÍA</label>
                        <input type="text" id="category" name="category" list="cat_list"
                               class="form-control bg-dark bg-opacity-50 border-secondary border-opacity-25 text-white py-2"
                               value="{{ old('category', $spare->category) }}">
                        <datalist id="cat_list">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-md-4">
                        <label for="location" class="form-label small fw-bold text-muted">UBICACIÓN EN BODEGA</label>
                        <input type="text" id="location" name="location"
                               class="form-control bg-dark bg-opacity-50 border-secondary border-opacity-25 text-white py-2"
                               value="{{ old('location', $spare->location) }}"
                               placeholder="Ej: Estante A-3, Rack 02-B">
                    </div>
                    <div class="col-md-4">
                        <label for="supplier" class="form-label small fw-bold text-muted">PROVEEDOR</label>
                        <input type="text" id="supplier" name="supplier"
                               class="form-control bg-dark bg-opacity-50 border-secondary border-opacity-25 text-white py-2"
                               value="{{ old('supplier', $spare->supplier) }}"
                               placeholder="Ej: Distribuidora Industrial XYZ">
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="d-flex flex-column flex-sm-row gap-3 pt-2">
                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill">
                    <i class="bi bi-save me-2"></i>Guardar cambios
                </button>
                <a href="{{ route('spares.show', $spare) }}"
                   class="btn btn-outline-secondary border-opacity-25 px-5 py-2 text-muted rounded-pill">
                    Cancelar
                </a>
            </div>

        </form>
    </div>
@endsection
