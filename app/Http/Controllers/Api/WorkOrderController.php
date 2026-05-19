<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkOrderController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = WorkOrder::with(['asset:id,name,code', 'assignedUser:id,name']);

        if ($user->isTecnico()) {
            $query->forTecnico($user->id);
        }

        $query->when($request->status, fn($q) => $q->where('status', $request->status))
              ->when($request->priority, fn($q) => $q->where('priority', $request->priority));

        return response()->json($query->latest()->paginate(20));
    }

    public function show(WorkOrder $workOrder)
    {
        return response()->json($workOrder->load(['asset', 'assignedUser', 'workOrderSpares.spare']));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'asset_id'    => 'required|exists:assets,id',
            'type'        => 'required|in:correctiva,preventiva',
            'priority'    => 'required|in:baja,media,alta,critica',
            'description' => 'required|string|min:10',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        $data['created_by'] = Auth::id();
        $data['status']     = 'pendiente';

        return response()->json(WorkOrder::create($data), 201);
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $data = $request->validate([
            'status'      => 'sometimes|in:pendiente,en_proceso,completada,cancelada',
            'priority'    => 'sometimes|in:baja,media,alta,critica',
            'assigned_to' => 'sometimes|nullable|exists:users,id',
            'notes'       => 'sometimes|nullable|string',
        ]);

        if (isset($data['status']) && $data['status'] === 'en_proceso' && !$workOrder->started_at) {
            $data['started_at'] = now();
        }
        if (isset($data['status']) && $data['status'] === 'completada' && !$workOrder->completed_at) {
            $data['completed_at'] = now();
        }

        $workOrder->update($data);
        return response()->json($workOrder->fresh()->load('asset', 'assignedUser'));
    }

    public function destroy(WorkOrder $workOrder)
    {
        $workOrder->delete();
        return response()->json(['message' => 'Orden eliminada.']);
    }
}
