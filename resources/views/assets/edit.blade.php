@extends('layouts.app')
@section('title', __('Modify Config'))

@section('content')
<div class="page-content pt-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:0.7rem; letter-spacing:0.05em; text-transform:uppercase;">
            <li class="breadcrumb-item"><a href="#" class="text-primary text-decoration-none fw-700">KINETIC</a></li>
            <li class="breadcrumb-item"><a href="{{ route('assets.index') }}" class="text-muted text-decoration-none opacity-50">{{ __('Asset Inventory') }}</a></li>
            <li class="breadcrumb-item active text-muted opacity-50" aria-current="page">{{ __('Configuration') }}</li>
        </ol>
    </nav>

    <div class="mb-5">
        <h1 class="st-display-md mb-2">{{ __('Modify Config') }}: <span class="text-white text-opacity-50">#{{ $asset->code }}</span></h1>
        <p class="st-lead mb-0 text-white text-opacity-50">{{ __('Updating industrial hardware nomenclature and operational deployment parameters.') }}</p>
    </div>

    <div class="st-section-low p-4 p-md-5 border border-white border-opacity-5" style="max-width: 900px;">
        <form method="POST" action="{{ route('assets.update', $asset) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="row g-5">
                <!-- Section: Identity -->
                <div class="col-12">
                    <label class="st-title-sm mb-4" style="color: var(--st-primary); letter-spacing: 0.1em;">
                        <i class="bi bi-tag me-2"></i>{{ __('IDENTITY & NOMENCLATURE') }}
                    </label>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-700 text-muted">{{ __('Registry Code') }} *</label>
                            <input type="text" name="code" class="form-control bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3 @error('code') is-invalid @enderror"
                                   value="{{ old('code', $asset->code) }}" required>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-700 text-muted">{{ __('Asset Designation') }} *</label>
                            <input type="text" name="name" class="form-control bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3 @error('name') is-invalid @enderror"
                                   value="{{ old('name', $asset->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Operational Deployment -->
                <div class="col-12">
                    <label class="st-title-sm mb-4" style="color: var(--st-primary); letter-spacing: 0.1em;">
                        <i class="bi bi-geo-alt me-2"></i>{{ __('OPERATIONAL DEPLOYMENT') }}
                    </label>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-700 text-muted">{{ __('Geographic Axis / Location') }} *</label>
                            <input type="text" name="location" class="form-control bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3 @error('location') is-invalid @enderror"
                                   value="{{ old('location', $asset->location) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-700 text-muted">{{ __('Operational Status') }} *</label>
                            <select name="status" class="form-select bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3 @error('status') is-invalid @enderror" required>
                                @foreach(['activo','inactivo','en_mantenimiento'] as $s)
                                <option value="{{ $s }}" {{ old('status', $asset->status) === $s ? 'selected' : '' }}>
                                    {{ __(ucfirst(str_replace('_',' ', $s))) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-700 text-muted">{{ __('Category Tier') }}</label>
                            <input type="text" name="category" class="form-control bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3" value="{{ old('category', $asset->category) }}">
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row gap-3 mt-4">
                        <button type="submit" class="st-btn-primary px-5 py-3">
                            <i class="bi bi-save me-2"></i>{{ __('Commit Update') }}
                        </button>
                        <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline-secondary border-opacity-20 px-5 py-3 text-white text-opacity-50">
                            {{ __('Abort') }}
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
