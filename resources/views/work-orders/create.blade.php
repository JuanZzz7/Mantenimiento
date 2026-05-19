@extends('layouts.app')
@section('title', __('Provision Mission'))

@section('content')
<div class="page-content pt-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:0.7rem; letter-spacing:0.05em; text-transform:uppercase;">
            <li class="breadcrumb-item"><a href="#" class="text-primary text-decoration-none fw-700">KINETIC</a></li>
            <li class="breadcrumb-item"><a href="{{ route('work-orders.index') }}" class="text-muted text-decoration-none opacity-50">{{ __('Tactical Missions') }}</a></li>
            <li class="breadcrumb-item active text-muted opacity-50" aria-current="page">{{ __('Provisioning') }}</li>
        </ol>
    </nav>

    <div class="mb-5">
        <h1 class="st-display-md mb-2">{{ __('Provision Mission') }}</h1>
        <p class="st-lead mb-0 text-white text-opacity-50">{{ __('Deploy a new tactical work order for industrial asset remediation or prevention.') }}</p>
    </div>

    <div class="st-section-low p-4 p-md-5 border border-white border-opacity-5" style="max-width: 1000px;">
        <form method="POST" action="{{ route('work-orders.store') }}">
            @csrf
            
            <div class="row g-5">
                <!-- Section: Deployment Context -->
                <div class="col-12">
                    <label class="st-title-sm mb-4" style="color: var(--st-primary); letter-spacing: 0.1em;">
                        <i class="bi bi-crosshair2 me-2"></i>{{ __('DEPLOYMENT CONTEXT') }}
                    </label>
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label small fw-700 text-muted">{{ __('Target Asset') }} *</label>
                            <select name="asset_id" class="form-select bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3 @error('asset_id') is-invalid @enderror" required>
                                <option value="">— {{ __('Select Hardware') }} —</option>
                                @foreach($assets as $asset)
                                <option value="{{ $asset->id }}" {{ (old('asset_id', request('asset_id')) == $asset->id) ? 'selected' : '' }}>
                                    {{ $asset->code }} — {{ $asset->name }} [{{ $asset->location }}]
                                </option>
                                @endforeach
                            </select>
                            @error('asset_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-700 text-muted">{{ __('Mission Type') }} *</label>
                            <select name="type" class="form-select bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3" required>
                                <option value="correctiva" {{ old('type','correctiva')==='correctiva'?'selected':'' }}>{{ __('Corrective') }}</option>
                                <option value="preventiva" {{ old('type')==='preventiva'?'selected':'' }}>{{ __('Preventive') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section: Tactical Profile -->
                <div class="col-12">
                    <label class="st-title-sm mb-4" style="color: var(--st-primary); letter-spacing: 0.1em;">
                        <i class="bi bi-shield-shaded me-2"></i>{{ __('TACTICAL PROFILE') }}
                    </label>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-700 text-muted">{{ __('Tactical Priority') }} *</label>
                            <select name="priority" class="form-select bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3" required>
                                @foreach(['baja'=>__('Routine'),'media'=>__('Standard'),'alta'=>__('High Priority'),'critica'=>__('CRITICAL')] as $val => $label)
                                <option value="{{ $val }}" {{ old('priority','media')===$val?'selected':'' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-700 text-muted">{{ __('Assigned Personnel') }}</label>
                            <select name="assigned_to" class="form-select bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3">
                                <option value="">— {{ __('Awaiting Dispatch') }} —</option>
                                @foreach($tecnicos as $t)
                                <option value="{{ $t->id }}" {{ old('assigned_to') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-700 text-muted">{{ __('Scheduled Deployment') }}</label>
                            <input type="datetime-local" name="scheduled_date" class="form-control bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3"
                                   value="{{ old('scheduled_date') }}">
                        </div>
                    </div>
                </div>

                <!-- Section: Mission Briefing -->
                <div class="col-12">
                    <label class="st-title-sm mb-4" style="color: var(--st-primary); letter-spacing: 0.1em;">
                        <i class="bi bi-card-text me-2"></i>{{ __('MISSION BRIEFING') }}
                    </label>
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label small fw-700 text-muted">{{ __('Operational Objectives') }} *</label>
                            <textarea name="description" class="form-control bg-dark bg-opacity-25 border-white border-opacity-10 text-white py-3 @error('description') is-invalid @enderror"
                                      rows="4" placeholder="{{ __('Describe the technical intervention required...') }}" required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row gap-3 mt-4">
                        <button type="submit" class="st-btn-primary px-5 py-3">
                            <i class="bi bi-lightning-fill me-2"></i>{{ __('Deploy Mission') }}
                        </button>
                        <a href="{{ route('work-orders.index') }}" class="btn btn-outline-secondary border-opacity-20 px-5 py-3 text-white text-opacity-50">
                            {{ __('Abort') }}
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
