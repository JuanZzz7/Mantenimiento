<?php

namespace App\Services;

use App\Models\MaintenancePlan;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\Auth;

class MaintenancePlanService
{
    public function generateWorkOrder(MaintenancePlan $plan): WorkOrder
    {
        $order = WorkOrder::create([
            'asset_id'           => $plan->asset_id,
            'type'               => 'preventiva',
            'priority'           => 'media',
            'status'             => 'pendiente',
            'description'        => "Mantenimiento preventivo: {$plan->name}",
            'maintenance_plan_id'=> $plan->id,
            'created_by'         => Auth::id() ?? 1,
            'scheduled_date'     => $plan->next_run_at ?? now(),
            'notes'              => $plan->description,
        ]);

        // Actualizar el plan con la nueva fecha de ejecución
        $plan->update([
            'last_run_at' => now(),
            'next_run_at' => $plan->calculateNextRun(),
        ]);

        return $order;
    }

    public function generateDueOrders(): int
    {
        $duePlans = MaintenancePlan::due()->with('asset')->get();
        $count = 0;

        foreach ($duePlans as $plan) {
            // Evitar duplicados: si ya existe una OT pendiente del mismo plan
            $exists = WorkOrder::where('maintenance_plan_id', $plan->id)
                ->whereIn('status', ['pendiente', 'en_proceso'])
                ->exists();

            if (! $exists) {
                $this->generateWorkOrder($plan);
                $count++;
            }
        }

        return $count;
    }
}
