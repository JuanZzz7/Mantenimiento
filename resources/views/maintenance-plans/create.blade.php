@extends('layouts.app')
@section('title','Nuevo Plan de Mantenimiento')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('maintenance-plans.index') }}" class="text-muted">Planes</a></li>
    <li class="breadcrumb-item active">Nuevo</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-plus-circle me-2 text-primary"></i>Nuevo Plan Preventivo</h1>
</div>

<div class="card" style="max-width:680px;">
    <div class="card-body">
        <form method="POST" action="{{ route('maintenance-plans.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Activo <span class="text-danger">*</span></label>
                    <select name="asset_id" class="form-select @error('asset_id') is-invalid @enderror" required>
                        <option value="">— Seleccionar activo —</option>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                            {{ $asset->code }} — {{ $asset->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('asset_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Nombre del Plan <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                           placeholder="Revisión mensual de filtros" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Frecuencia <span class="text-danger">*</span></label>
                    <select name="frequency" class="form-select" required>
                        @foreach(['semanal'=>'Semanal (7 días)','mensual'=>'Mensual (30 días)','trimestral'=>'Trimestral (90 días)','semestral'=>'Semestral (180 días)','anual'=>'Anual (365 días)'] as $v => $l)
                        <option value="{{ $v }}" {{ old('frequency') === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Primera Ejecución</label>
                    <input type="date" name="next_run_at" class="form-control"
                           value="{{ old('next_run_at', now()->addDays(7)->format('Y-m-d')) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Descripción del Trabajo</label>
                    <textarea name="description" class="form-control" rows="3"
                              placeholder="Actividades a realizar durante el mantenimiento...">{{ old('description') }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="active" class="form-check-input" id="active" value="1"
                               {{ old('active', '1') === '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Plan activo</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Crear Plan</button>
                <a href="{{ route('maintenance-plans.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
