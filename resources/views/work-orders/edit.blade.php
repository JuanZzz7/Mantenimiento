@extends('layouts.app')
@section('title','Editar Orden '.$workOrder->code)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('work-orders.index') }}" class="text-muted">Órdenes</a></li>
    <li class="breadcrumb-item"><a href="{{ route('work-orders.show', $workOrder) }}" class="text-muted">{{ $workOrder->code }}</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-pencil-square me-2 text-primary"></i>Editar: {{ $workOrder->code }}</h1>
</div>

<div class="card" style="max-width:800px;">
    <div class="card-body">
        <form method="POST" action="{{ route('work-orders.update', $workOrder) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Activo <span class="text-danger">*</span></label>
                    <select name="asset_id" class="form-select" required>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ old('asset_id',$workOrder->asset_id) == $asset->id ? 'selected':'' }}>
                            {{ $asset->code }} — {{ $asset->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipo</label>
                    <select name="type" class="form-select">
                        <option value="correctiva" {{ old('type',$workOrder->type)==='correctiva'?'selected':'' }}>Correctiva</option>
                        <option value="preventiva" {{ old('type',$workOrder->type)==='preventiva'?'selected':'' }}>Preventiva</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Prioridad</label>
                    <select name="priority" class="form-select">
                        @foreach(['baja'=>'Baja','media'=>'Media','alta'=>'Alta','critica'=>'Crítica'] as $v => $l)
                        <option value="{{ $v }}" {{ old('priority',$workOrder->priority)===$v?'selected':'' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select">
                        @foreach(['pendiente'=>'Pendiente','en_proceso'=>'En Proceso','completada'=>'Completada','cancelada'=>'Cancelada'] as $v => $l)
                        <option value="{{ $v }}" {{ old('status',$workOrder->status)===$v?'selected':'' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Técnico Asignado</label>
                    <select name="assigned_to" class="form-select">
                        <option value="">— Sin asignar —</option>
                        @foreach($tecnicos as $t)
                        <option value="{{ $t->id }}" {{ old('assigned_to',$workOrder->assigned_to)==$t->id?'selected':'' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha Programada</label>
                    <input type="datetime-local" name="scheduled_date" class="form-control"
                           value="{{ old('scheduled_date', $workOrder->scheduled_date?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Inicio Real</label>
                    <input type="datetime-local" name="started_at" class="form-control"
                           value="{{ old('started_at', $workOrder->started_at?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha Completada</label>
                    <input type="datetime-local" name="completed_at" class="form-control"
                           value="{{ old('completed_at', $workOrder->completed_at?->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Descripción <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="4" required>{{ old('description',$workOrder->description) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Notas</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes',$workOrder->notes) }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Guardar Cambios</button>
                <a href="{{ route('work-orders.show', $workOrder) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
