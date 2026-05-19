<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Órdenes de Trabajo — CMMS Industrial</title>
    {{--
        NOTA: El CSS de este PDF se mantiene INLINE intencionalmente.
        DomPDF (generador de PDF) no puede cargar hojas de estilo externas
        por restricciones de acceso al filesystem/red. El archivo de referencia
        está documentado en: public/css/reports.css
    --}}
    <style>
        * { font-family: DejaVu Sans, sans-serif; font-size: 9pt; margin: 0; padding: 0; }
        body { padding: 15px; color: #1e293b; }

        .header { background: #1e40af; color: #fff; padding: 12px 15px; border-radius: 6px; margin-bottom: 15px; }
        .header h1 { font-size: 14pt; margin-bottom: 2px; }
        .header small { font-size: 8pt; opacity: .8; }

        .summary { display: flex; gap: 10px; margin-bottom: 15px; }
        .summary-box { flex: 1; text-align: center; padding: 8px; border-radius: 4px; border: 1px solid #e2e8f0; }
        .summary-box .val { font-size: 16pt; font-weight: bold; }
        .summary-box .lbl { font-size: 7pt; color: #64748b; text-transform: uppercase; }

        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #1e40af; color: #fff; padding: 6px 8px; text-align: left; font-size: 8pt; }
        td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        tr:nth-child(even) td { background: #f8fafc; }

        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 7pt; font-weight: bold; }
        .badge-success  { background: #d1fae5; color: #065f46; }
        .badge-warning  { background: #fef3c7; color: #92400e; }
        .badge-info     { background: #dbeafe; color: #1e40af; }
        .badge-secondary{ background: #f1f5f9; color: #475569; }
        .badge-danger   { background: #fee2e2; color: #991b1b; }

        .footer { margin-top: 15px; font-size: 7pt; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Órdenes de Trabajo</h1>
        <small>CMMS Industrial — Generado el {{ now()->format('d/m/Y H:i') }}</small>
        @if(!empty(array_filter($filters ?? [])))
        <div style="margin-top:4px;font-size:7.5pt;opacity:.9;">
            Filtros aplicados:
            @if(!empty($filters['from'])) Desde {{ $filters['from'] }}@endif
            @if(!empty($filters['to'])) hasta {{ $filters['to'] }}@endif
            @if(!empty($filters['status'])) | Estado: {{ ucfirst(str_replace('_',' ',$filters['status'])) }}@endif
            @if(!empty($filters['type'])) | Tipo: {{ ucfirst($filters['type']) }}@endif
            @if(!empty($filters['priority'])) | Prioridad: {{ ucfirst($filters['priority']) }}@endif
        </div>
        @endif
    </div>

    <div class="summary">
        <div class="summary-box">
            <div class="val" style="color:#1e40af;">{{ $summary['total'] }}</div>
            <div class="lbl">Total</div>
        </div>
        <div class="summary-box">
            <div class="val" style="color:#065f46;">{{ $summary['completadas'] }}</div>
            <div class="lbl">Completadas</div>
        </div>
        <div class="summary-box">
            <div class="val" style="color:#92400e;">{{ $summary['pendientes'] }}</div>
            <div class="lbl">Pendientes</div>
        </div>
        <div class="summary-box">
            <div class="val" style="color:#1e40af;">{{ $summary['en_proceso'] }}</div>
            <div class="lbl">En Proceso</div>
        </div>
        <div class="summary-box">
            <div class="val" style="color:#475569;">{{ $summary['canceladas'] }}</div>
            <div class="lbl">Canceladas</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Activo</th>
                <th>Tipo</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Técnico</th>
                <th>Programada</th>
                <th>Completada</th>
            </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
        @php
            $statusClass = match($order->status) {
                'completada' => 'success',
                'pendiente'  => 'warning',
                'en_proceso' => 'info',
                default      => 'secondary',
            };
            $priorityClass = match($order->priority) {
                'critica' => 'danger',
                'alta'    => 'warning',
                'media'   => 'info',
                default   => 'secondary',
            };
        @endphp
        <tr>
            <td><strong>{{ $order->code }}</strong></td>
            <td>{{ $order->asset->name ?? '—' }}</td>
            <td>{{ $order->type_label }}</td>
            <td><span class="badge badge-{{ $priorityClass }}">{{ $order->priority_label }}</span></td>
            <td><span class="badge badge-{{ $statusClass }}">{{ $order->status_label }}</span></td>
            <td>{{ $order->assignedUser->name ?? '—' }}</td>
            <td>{{ $order->scheduled_date?->format('d/m/Y') ?? '—' }}</td>
            <td>{{ $order->completed_at?->format('d/m/Y') ?? '—' }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>

    <div class="footer">
        CMMS Industrial — {{ config('app.name') }} | Total de registros: {{ $orders->count() }}
    </div>
</body>
</html>
