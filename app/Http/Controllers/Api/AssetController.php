<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $assets = Asset::query()
            ->when($request->search, fn($q) => $q->search($request->search))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->withCount('workOrders')
            ->paginate(20);

        return response()->json($assets);
    }

    public function show(Asset $asset)
    {
        return response()->json($asset->load('maintenancePlans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'             => 'required|string|unique:assets,code',
            'name'             => 'required|string',
            'location'         => 'required|string',
            'status'           => 'required|in:activo,inactivo,en_mantenimiento',
            'acquisition_date' => 'nullable|date',
            'brand'            => 'nullable|string',
            'model'            => 'nullable|string',
            'serial_number'    => 'nullable|string',
            'category'         => 'nullable|string',
            'description'      => 'nullable|string',
        ]);

        return response()->json(Asset::create($data), 201);
    }

    public function update(Request $request, Asset $asset)
    {
        $data = $request->validate([
            'code'     => 'sometimes|string|unique:assets,code,' . $asset->id,
            'name'     => 'sometimes|string',
            'location' => 'sometimes|string',
            'status'   => 'sometimes|in:activo,inactivo,en_mantenimiento',
        ]);

        $asset->update($data);
        return response()->json($asset->fresh());
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return response()->json(['message' => 'Activo eliminado.']);
    }
}
