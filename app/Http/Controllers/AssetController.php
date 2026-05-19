<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::query();

        if ($search = $request->get('search')) {
            $query->search($search);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        $assets = $query->withCount('workOrders')->latest()->paginate(12)->withQueryString();
        $categories = Asset::select('category')->distinct()->whereNotNull('category')->pluck('category');

        return view('assets.index', compact('assets', 'categories'));
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(StoreAssetRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('assets', 'public');
        }

        Asset::create($data);

        return redirect()->route('assets.index')
            ->with('success', 'Activo registrado correctamente.');
    }

    public function show(Asset $asset)
    {
        $asset->load('maintenancePlans');
        $workOrders = $asset->workOrders()
            ->with(['assignedUser', 'createdByUser'])
            ->latest()
            ->paginate(10);

        return view('assets.show', compact('asset', 'workOrders'));
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($asset->image) {
                Storage::disk('public')->delete($asset->image);
            }
            $data['image'] = $request->file('image')->store('assets', 'public');
        }

        $asset->update($data);

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Activo actualizado correctamente.');
    }

    public function destroy(Asset $asset)
    {
        // Verificar que no tenga OTs abiertas
        $openOrders = $asset->workOrders()
            ->whereIn('status', ['pendiente', 'en_proceso'])->count();

        if ($openOrders > 0) {
            return back()->with('error', "No se puede eliminar: el activo tiene {$openOrders} orden(es) de trabajo abiertas.");
        }

        if ($asset->image) {
            Storage::disk('public')->delete($asset->image);
        }

        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'Activo eliminado correctamente.');
    }
}
