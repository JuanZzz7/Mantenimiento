<?php

namespace App\Http\Controllers;

use App\Models\Spare;
use App\Http\Requests\StoreSpareRequest;
use Illuminate\Http\Request;

class SpareController extends Controller
{
    // ── Lista principal ────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Spare::query();

        if ($search = $request->get('search')) {
            $query->search($search);
        }
        if ($request->get('low_stock')) {
            $query->lowStock();
        }
        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }
        if ($request->get('out_of_stock')) {
            $query->where('stock', 0);
        }

        // Ordenamiento
        $sort = $request->get('sort', 'updated_at');
        $dir  = $request->get('dir', 'desc');
        $allowedSorts = ['name', 'code', 'stock', 'price', 'updated_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $dir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $spares       = $query->paginate(16)->withQueryString();
        $lowStockCount = Spare::lowStock()->count();
        $categories   = Spare::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->orderBy('category')
            ->pluck('category');

        // Estadísticas de resumen
        $stats = [
            'total'      => Spare::count(),
            'value'      => Spare::selectRaw('COALESCE(SUM(stock * price), 0) as v')->value('v'),
            'low_stock'  => $lowStockCount,
            'out_stock'  => Spare::where('stock', 0)->count(),
        ];

        return view('spares.index', compact('spares', 'lowStockCount', 'categories', 'stats'));
    }

    // ── Detalle de un repuesto ─────────────────────────────────────────────────
    public function show(Spare $spare)
    {
        // Historial de uso: últimas 10 órdenes de trabajo donde se usó
        $usageHistory = $spare->workOrders()
            ->with(['asset', 'assignedUser'])
            ->orderByPivot('created_at', 'desc')
            ->take(10)
            ->get();

        $totalUsed = (int) $spare->workOrders()->sum('work_order_spares.quantity');

        return view('spares.show', compact('spare', 'usageHistory', 'totalUsed'));
    }

    // ── Formulario de creación ─────────────────────────────────────────────────
    public function create()
    {
        $categories = Spare::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->orderBy('category')
            ->pluck('category');
        return view('spares.create', compact('categories'));
    }

    // ── Guardar nuevo repuesto ─────────────────────────────────────────────────
    public function store(StoreSpareRequest $request)
    {
        Spare::create($request->validated());
        return redirect()->route('spares.index')
            ->with('success', 'Repuesto registrado correctamente.');
    }

    // ── Formulario de edición ──────────────────────────────────────────────────
    public function edit(Spare $spare)
    {
        $categories = Spare::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->orderBy('category')
            ->pluck('category');
        return view('spares.edit', compact('spare', 'categories'));
    }

    // ── Actualizar repuesto ────────────────────────────────────────────────────
    public function update(StoreSpareRequest $request, Spare $spare)
    {
        $spare->update($request->validated());
        return redirect()->route('spares.show', $spare)
            ->with('success', 'Repuesto actualizado correctamente.');
    }

    // ── Eliminar repuesto ──────────────────────────────────────────────────────
    public function destroy(Spare $spare)
    {
        if ($spare->workOrderSpares()->exists()) {
            return back()->with('error', 'No se puede eliminar: el repuesto está asociado a órdenes de trabajo.');
        }
        $spare->delete();
        return redirect()->route('spares.index')
            ->with('success', 'Repuesto eliminado del inventario.');
    }

    // ── Ajuste manual de stock ─────────────────────────────────────────────────
    public function adjustStock(Request $request, Spare $spare)
    {
        $request->validate([
            'adjustment' => 'required|integer|not_in:0',
            'reason'     => 'nullable|string|max:255',
        ], [
            'adjustment.not_in' => 'El ajuste no puede ser cero.',
        ]);

        $adj = $request->integer('adjustment');

        if ($adj > 0) {
            $spare->increaseStock($adj);
            $msg = "Ingreso de {$adj} {$spare->unit}(s). Stock actual: " . $spare->fresh()->stock;
        } elseif ($spare->stock >= abs($adj)) {
            $spare->decreaseStock(abs($adj));
            $msg = "Retiro de " . abs($adj) . " {$spare->unit}(s). Stock actual: " . $spare->fresh()->stock;
        } else {
            return back()->with('error', "Stock insuficiente. Solo hay {$spare->stock} {$spare->unit}(s) disponibles.");
        }

        return back()->with('success', $msg);
    }

    // ── Helper de autorización ─────────────────────────────────────────────
    // Ambos roles (admin y técnico) pueden gestionar el inventario
    // Solo se bloquea si por alguna razón el rol no es reconocido
    private function authorizeAdmin(): void
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isTecnico()) {
            abort(403, 'No tienes permisos para gestionar el inventario.');
        }
    }
}
