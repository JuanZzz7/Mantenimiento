<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Spare;
use Illuminate\Http\Request;

class SpareController extends Controller
{
    public function index(Request $request)
    {
        $spares = Spare::query()
            ->when($request->search, fn($q) => $q->search($request->search))
            ->when($request->low_stock, fn($q) => $q->lowStock())
            ->paginate(20);

        return response()->json($spares);
    }

    public function show(Spare $spare)
    {
        return response()->json($spare);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'      => 'required|string|unique:spares,code',
            'name'      => 'required|string',
            'unit'      => 'required|string',
            'stock'     => 'required|integer|min:0',
            'stock_min' => 'required|integer|min:0',
            'price'     => 'required|numeric|min:0',
        ]);

        return response()->json(Spare::create($data), 201);
    }

    public function update(Request $request, Spare $spare)
    {
        $spare->update($request->only(['name', 'stock', 'stock_min', 'price', 'supplier', 'location']));
        return response()->json($spare->fresh());
    }

    public function destroy(Spare $spare)
    {
        $spare->delete();
        return response()->json(['message' => 'Repuesto eliminado.']);
    }
}
