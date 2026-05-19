<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\WorkOrder;
use App\Models\Spare;
use App\Models\User;
use App\Models\MaintenancePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ── Stats comunes ──────────────────────────────────────────────────
        $stats = [
            'total_assets'      => Asset::count(),
            'assets_active'     => Asset::where('status', 'activo')->count(),
            'assets_maintenance'=> Asset::where('status', 'en_mantenimiento')->count(),
            'wo_pending'        => WorkOrder::where('status', 'pendiente')->count(),
            'wo_in_progress'    => WorkOrder::where('status', 'en_proceso')->count(),
            'wo_completed'      => WorkOrder::where('status', 'completada')->count(),
            'low_stock_spares'  => Spare::whereColumn('stock', '<=', 'stock_min')->count(),
        ];

        if ($user->isAdmin()) {
            return $this->adminDashboard($stats);
        }

        return $this->tecnicoDashboard($user, $stats);
    }

    private function adminDashboard(array $stats)
    {
        // Órdenes por estado (para gráfica donut)
        $ordersByStatus = WorkOrder::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->get()->pluck('total', 'status');

        // Activos críticos (en mantenimiento o con OTs críticas abiertas)
        $criticalAssets = Asset::whereHas('workOrders', function ($q) {
            $q->where('priority', 'critica')->whereIn('status', ['pendiente', 'en_proceso']);
        })->with(['workOrders' => fn($q) => $q->where('priority', 'critica')])->limit(5)->get();

        // Técnicos más activos (últimos 30 días)
        $activeTecnicos = User::where('role', 'tecnico')
            ->withCount(['assignedWorkOrders as completed_count' => fn($q) =>
                $q->where('status', 'completada')
                  ->where('completed_at', '>=', now()->subDays(30))
            ])
            ->orderByDesc('completed_count')->limit(5)->get();

        // Órdenes recientes
        $recentOrders = WorkOrder::with(['asset', 'assignedUser'])
            ->latest()->limit(8)->get();

        // Datos para gráfica de barras (OTs por mes últimos 6 meses)
        $monthlyOrders = WorkOrder::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get();

        // ── Regla 5-3-2-1 (Tactical Maintenance Distribution) ──────────────────
        $totalOrders = WorkOrder::count() ?: 1;
        $tacticalRule = [
            'preventive'  => round((WorkOrder::whereNotNull('maintenance_plan_id')->count() / $totalOrders) * 10),
            'corrective'  => round((WorkOrder::whereNull('maintenance_plan_id')->whereIn('priority', ['media','alta'])->count() / $totalOrders) * 10),
            'predictive'  => round((WorkOrder::whereIn('priority', ['critica'])->count() / $totalOrders) * 10),
            'improvement' => round((WorkOrder::where('priority', 'baja')->count() / $totalOrders) * 10),
        ];

        // Planes próximos a vencer
        $duePlans = MaintenancePlan::with('asset')
            ->active()
            ->where('next_run_at', '<=', now()->addDays(7))
            ->orderBy('next_run_at')
            ->limit(5)->get();

        return view('dashboard', compact(
            'stats', 'ordersByStatus', 'criticalAssets',
            'activeTecnicos', 'recentOrders', 'monthlyOrders', 'duePlans', 'tacticalRule'
        ));
    }

    private function tecnicoDashboard(User $user, array $stats)
    {
        $myOrders = WorkOrder::with(['asset'])
            ->forTecnico($user->id)
            ->whereIn('status', ['pendiente', 'en_proceso'])
            ->orderByRaw("FIELD(priority,'critica','alta','media','baja')")
            ->get();

        $myCompleted = WorkOrder::forTecnico($user->id)
            ->where('status', 'completada')
            ->whereMonth('completed_at', now()->month)
            ->count();

        $myStats = [
            'pending'     => WorkOrder::forTecnico($user->id)->where('status', 'pendiente')->count(),
            'in_progress' => WorkOrder::forTecnico($user->id)->where('status', 'en_proceso')->count(),
            'completed'   => $myCompleted,
        ];

        return view('dashboard', compact('stats', 'myOrders', 'myStats'));
    }
}
