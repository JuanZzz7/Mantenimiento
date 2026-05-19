@extends('layouts.app')
@section('title','Editar Plan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('maintenance-plans.index') }}" class="text-muted">Planes</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-pencil-square me-2 text-primary"></i>Editar Plan: {{ $maintenancePlan->name }}</h1>
</div>

<div class="card" style="max-width:680px;">
    <div class="card-body">
        <form method="POST" action="{{ route('maintenance-plans.update', $maintenancePlan) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Activo <span class="text-danger">*</span></label>
                    <select name="asset_id" class="form-select" required>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ old('asset_id',$maintenancePlan->asset_id) == $asset->id ? 'selected':'' }}>
                            {{ $asset->code }} — {{ $asset->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Nombre del Plan <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name',$maintenancePlan->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Frecuencia</label>
                    <select name="frequency" class="form-select">
                        @foreach(['semanal','mensual','trimestral','semestral','anual'] as $f)
                        <option value="{{ $f }}" {{ old('frequency',$maintenancePlan->frequency)===$f?'selected':'' }}>{{ ucfirst($f) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Próxima Ejecución</label>
                    <input type="date" name="next_run_at" class="form-control"
                           value="{{ old('next_run_at', $maintenancePlan->next_run_at?->format('Y-m-d')) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description',$maintenancePlan->description) }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="active" class="form-check-input" id="active" value="1"
                               {{ old('active', $maintenancePlan->active ? '1' : '0') === '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Plan activo</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Actualizar</button>
                <a href="{{ route('maintenance-plans.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
