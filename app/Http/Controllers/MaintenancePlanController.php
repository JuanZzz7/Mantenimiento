<?php

namespace App\Http\Controllers;

use App\Models\MaintenancePlan;
use App\Models\Asset;
use App\Http\Requests\StoreMaintenancePlanRequest;
use App\Services\MaintenancePlanService;
use Illuminate\Http\Request;

class MaintenancePlanController extends Controller
{
    public function __construct(private MaintenancePlanService $service) {}

    public function index(Request $request)
    {
        $query = MaintenancePlan::with('asset');

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhereHas('asset', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }
        if ($frequency = $request->get('frequency')) {
            $query->where('frequency', $frequency);
        }
        if ($request->get('active') !== null) {
            $query->where('active', $request->boolean('active'));
        }

        $plans = $query->latest()->paginate(12)->withQueryString();
        return view('maintenance-plans.index', compact('plans'));
    }

    public function create()
    {
        $assets = Asset::where('status', 'activo')->orderBy('name')->get();
        return view('maintenance-plans.create', compact('assets'));
    }

    public function store(StoreMaintenancePlanRequest $request)
    {
        $data = $request->validated();
        $data['active'] = $request->boolean('active', true);

        // Calcular next_run_at si no se proporcionó
        if (empty($data['next_run_at'])) {
            $tempPlan = new MaintenancePlan($data);
            $data['next_run_at'] = now()->addDays($tempPlan->frequency_days);
        }

        $plan = MaintenancePlan::create($data);

        return redirect()->route('maintenance-plans.index')
            ->with('success', "Plan \"{$plan->name}\" creado correctamente.");
    }

    public function show(MaintenancePlan $maintenancePlan)
    {
        $maintenancePlan->load(['asset', 'workOrders.assignedUser']);
        return view('maintenance-plans.show', compact('maintenancePlan'));
    }

    public function edit(MaintenancePlan $maintenancePlan)
    {
        $assets = Asset::orderBy('name')->get();
        return view('maintenance-plans.edit', compact('maintenancePlan', 'assets'));
    }

    public function update(StoreMaintenancePlanRequest $request, MaintenancePlan $maintenancePlan)
    {
        $data = $request->validated();
        $data['active'] = $request->boolean('active', true);
        $maintenancePlan->update($data);

        return redirect()->route('maintenance-plans.index')
            ->with('success', 'Plan de mantenimiento actualizado.');
    }

    public function destroy(MaintenancePlan $maintenancePlan)
    {
        $maintenancePlan->delete();
        return redirect()->route('maintenance-plans.index')
            ->with('success', 'Plan eliminado correctamente.');
    }

    // ── Generar OT preventiva manualmente ────────────────────────────────
    public function generate(MaintenancePlan $maintenancePlan)
    {
        $order = $this->service->generateWorkOrder($maintenancePlan);
        return redirect()->route('work-orders.show', $order)
            ->with('success', "Orden preventiva {$order->code} generada.");
    }
}
