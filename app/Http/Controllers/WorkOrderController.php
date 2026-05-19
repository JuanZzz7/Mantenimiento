<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Asset;
use App\Models\User;
use App\Models\Spare;
use App\Models\WorkOrderSpare;
use App\Http\Requests\StoreWorkOrderRequest;
use App\Http\Requests\UpdateWorkOrderRequest;
use App\Notifications\WorkOrderAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = WorkOrder::with(['asset', 'assignedUser']);

        // Técnico solo ve sus propias órdenes
        if ($user->isTecnico()) {
            $query->forTecnico($user->id);
        }

        if ($search = $request->get('search')) {
            $query->search($search);
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }
        if ($priority = $request->get('priority')) {
            $query->where('priority', $priority);
        }
        if ($from = $request->get('from')) {
            $query->where('created_at', '>=', $from);
        }
        if ($to = $request->get('to')) {
            $query->where('created_at', '<=', $to . ' 23:59:59');
        }

        $orders = $query->orderByRaw("FIELD(priority,'critica','alta','media','baja')")
                        ->latest()->paginate(15)->withQueryString();

        return view('work-orders.index', compact('orders'));
    }

    public function create()
    {
        $assets   = Asset::where('status', '!=', 'inactivo')->orderBy('name')->get();
        $tecnicos = User::where('role', 'tecnico')->active()->orderBy('name')->get();
        return view('work-orders.create', compact('assets', 'tecnicos'));
    }

    public function store(StoreWorkOrderRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();
        $data['status']     = 'pendiente';

        $order = WorkOrder::create($data);

        // Notificar al técnico asignado
        if ($order->assigned_to) {
            try {
                $order->assignedUser->notify(new WorkOrderAssigned($order));
            } catch (\Exception $e) {
                // Silenciar errores de notificación en desarrollo
            }
        }

        return redirect()->route('work-orders.show', $order)
            ->with('success', "Orden {$order->code} creada correctamente.");
    }

    public function show(WorkOrder $workOrder)
    {
        $workOrder->load(['asset', 'assignedUser', 'createdByUser', 'workOrderSpares.spare']);
        $availableSpares = Spare::where('stock', '>', 0)->orderBy('name')->get();
        return view('work-orders.show', compact('workOrder', 'availableSpares'));
    }

    public function edit(WorkOrder $workOrder)
    {
        $assets   = Asset::orderBy('name')->get();
        $tecnicos = User::where('role', 'tecnico')->active()->orderBy('name')->get();
        return view('work-orders.edit', compact('workOrder', 'assets', 'tecnicos'));
    }

    public function update(UpdateWorkOrderRequest $request, WorkOrder $workOrder)
    {
        $data        = $request->validated();
        $oldAssigned = $workOrder->assigned_to;

        // Auto-timestamps
        if ($data['status'] === 'en_proceso' && ! $workOrder->started_at) {
            $data['started_at'] = now();
        }
        if ($data['status'] === 'completada' && ! $workOrder->completed_at) {
            $data['completed_at'] = now();
        }

        $workOrder->update($data);

        // Notificar si cambió el técnico
        if ($data['assigned_to'] && $data['assigned_to'] != $oldAssigned) {
            try {
                $workOrder->refresh()->assignedUser->notify(new WorkOrderAssigned($workOrder));
            } catch (\Exception $e) {}
        }

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Orden de trabajo actualizada.');
    }

    public function destroy(WorkOrder $workOrder)
    {
        if (in_array($workOrder->status, ['completada'])) {
            return back()->with('error', 'No se puede eliminar una orden completada.');
        }
        $code = $workOrder->code;
        $workOrder->delete();
        return redirect()->route('work-orders.index')
            ->with('success', "Orden {$code} eliminada.");
    }

    // ── Agregar repuesto a OT ─────────────────────────────────────────────
    public function addSpare(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'spare_id'   => 'required|exists:spares,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $spare = Spare::findOrFail($request->spare_id);

        if ($spare->stock < $request->quantity) {
            return back()->with('error', "Stock insuficiente. Disponible: {$spare->stock} {$spare->unit}.");
        }

        DB::transaction(function () use ($workOrder, $spare, $request) {
            WorkOrderSpare::create([
                'work_order_id' => $workOrder->id,
                'spare_id'      => $spare->id,
                'quantity'      => $request->quantity,
                'unit_price'    => $spare->price,
            ]);
            $spare->decreaseStock($request->quantity);
        });

        return back()->with('success', "Repuesto \"{$spare->name}\" agregado.");
    }

    // ── Remover repuesto de OT ────────────────────────────────────────────
    public function removeSpare(WorkOrder $workOrder, WorkOrderSpare $spare)
    {
        DB::transaction(function () use ($spare) {
            $spare->spare->increaseStock($spare->quantity);
            $spare->delete();
        });

        return back()->with('success', 'Repuesto removido y stock restaurado.');
    }

    // ── Cambios de estado rápidos ──────────────────────────────────────────
    public function start(WorkOrder $workOrder)
    {
        if ($workOrder->status !== 'pendiente') return back();
        
        $workOrder->update([
            'status' => 'en_proceso',
            'started_at' => now()
        ]);
        
        return back()->with('success', __('Mission started. Operational clock running.'));
    }

    public function complete(WorkOrder $workOrder)
    {
        if ($workOrder->status !== 'en_proceso') return back();
        
        $workOrder->update([
            'status' => 'completada',
            'completed_at' => now()
        ]);
        
        return back()->with('success', __('Mission accomplished. Records synchronized.'));
    }
}
