@extends('layouts.app')
@section('title', __('Provision Asset'))

@section('content')
<div class="page-content pt-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:0.7rem; letter-spacing:0.05em; text-transform:uppercase;">
            <li class="breadcrumb-item"><a href="#" class="text-primary text-decoration-none fw-700">KINETIC</a></li>
            <li class="breadcrumb-item"><a href="{{ route('assets.index') }}" class="text-muted text-decoration-none opacity-50">{{ __('Asset Inventory') }}</a></li>
            <li class="breadcrumb-item active text-muted opacity-50" aria-current="page">{{ __('Provisioning') }}</li>
        </ol>
    </nav>

    <div class="mb-5">
        <h1 class="st-display-md mb-2">{{ __('Provision Asset') }}</h1>
        <p class="st-lead mb-0 text-white text-opacity-50">{{ __('Initialize a new industrial hardware registry in the global telemetry database.') }}</p>
    </div>

    <div class="st-section-low p-4 p-md-5 border border-white border-opacity-5" style="max-width: 900px;">
        <form method="POST" action="{{ route('assets.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-5">
                <!-- Section: Nomenclature -->
                <div class="col-12">
                    <label class="st-title-sm mb-4" style="color: var(--st-primary); letter-spacing: 0.1em;">
                        <i class="bi bi-tag me-2"></i>{{ __('NOMENCLATURE & IDENTITY') }}
                    </label>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-700 text-muted">{{ __('Registry Code') }} *</label>
                            <input type="text" name="code" class="form-control bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3 @error('code') is-invalid @enderror"
                                   value="{{ old('code') }}" placeholder="ACT-XXXX" required>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-700 text-muted">{{ __('Asset Designation') }} *</label>
                            <input type="text" name="name" class="form-control bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3 @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="e.g. Primary Centrifugal Pump" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Deployment -->
                <div class="col-12">
                    <label class="st-title-sm mb-4" style="color: var(--st-primary); letter-spacing: 0.1em;">
                        <i class="bi bi-geo-alt me-2"></i>{{ __('OPERATIONAL DEPLOYMENT') }}
                    </label>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-700 text-muted">{{ __('Geographic Axis / Location') }} *</label>
                            <input type="text" name="location" class="form-control bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3 @error('location') is-invalid @enderror"
                                   value="{{ old('location') }}" placeholder="e.g. Sector 7 — Hub B" required>
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-700 text-muted">{{ __('Initial Status') }} *</label>
                            <select name="status" class="form-select bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3 @error('status') is-invalid @enderror" required>
                                <option value="activo" {{ old('status','activo') === 'activo' ? 'selected' : '' }}>{{ __('Operational') }}</option>
                                <option value="inactivo" {{ old('status') === 'inactivo' ? 'selected' : '' }}>{{ __('Deactivated') }}</option>
                                <option value="en_mantenimiento" {{ old('status') === 'en_mantenimiento' ? 'selected' : '' }}>{{ __('Under Service') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-700 text-muted">{{ __('Category Tier') }}</label>
                            <input type="text" name="category" class="form-control bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3" value="{{ old('category') }}" placeholder="e.g. Mechanical">
                        </div>
                    </div>
                </div>

                <!-- Section: Hardware Specs -->
                <div class="col-12">
                    <label class="st-title-sm mb-4" style="color: var(--st-primary); letter-spacing: 0.1em;">
                        <i class="bi bi-cpu me-2"></i>{{ __('HARDWARE SPECIFICATIONS') }}
                    </label>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-700 text-muted">{{ __('OEM / Brand') }}</label>
                            <input type="text" name="brand" class="form-control bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3" value="{{ old('brand') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-700 text-muted">{{ __('Model Identifier') }}</label>
                            <input type="text" name="model" class="form-control bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3" value="{{ old('model') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-700 text-muted">{{ __('Registry Serial') }}</label>
                            <input type="text" name="serial_number" class="form-control bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3" value="{{ old('serial_number') }}">
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row gap-3 mt-4">
                        <button type="submit" class="st-btn-primary px-5 py-3">
                            <i class="bi bi-save me-2"></i>{{ __('Commit Registry') }}
                        </button>
                        <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary border-opacity-20 px-5 py-3 text-white text-opacity-50">
                            {{ __('Abort') }}
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
