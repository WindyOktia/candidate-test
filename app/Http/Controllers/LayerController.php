<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\LayerRepositoryInterface;
use App\Http\Requests\StoreLayerRequest;
use App\Http\Requests\UpdateLayerRequest;
use App\Models\Layer;
use App\Models\Layup;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LayerController extends Controller
{
    public function __construct(
        private readonly LayerRepositoryInterface $layers,
    ) {}

    public function create(Supplier $supplier, Layup $layup): View
    {
        $existingOrders = $layup->layers->pluck('layer_order')->sort()->values()->toArray();
        return view('layers.create', compact('supplier', 'layup', 'existingOrders'));
    }

    public function store(StoreLayerRequest $request, Supplier $supplier, Layup $layup): RedirectResponse
    {
        $this->layers->create(array_merge($request->validated(), ['layup_id' => $layup->id]));
        $layup->bumpRevision('Layer added');

        return redirect()->route('suppliers.layups.show', [$supplier, $layup])
            ->with('success', 'Layer created successfully.');
    }

    public function edit(Supplier $supplier, Layup $layup, Layer $layer): View
    {
        return view('layers.edit', compact('supplier', 'layup', 'layer'));
    }

    public function update(UpdateLayerRequest $request, Supplier $supplier, Layup $layup, Layer $layer): RedirectResponse
    {
        $this->layers->update($layer, $request->validated());
        $layup->bumpRevision('Layer updated');

        return redirect()->route('suppliers.layups.show', [$supplier, $layup])
            ->with('success', 'Layer updated successfully.');
    }

    public function destroy(Supplier $supplier, Layup $layup, Layer $layer): RedirectResponse
    {
        $this->layers->delete($layer);
        $layup->bumpRevision('Layer removed');

        return redirect()->route('suppliers.layups.show', [$supplier, $layup])
            ->with('success', 'Layer deleted successfully.');
    }

    public function reorder(Request $request, Supplier $supplier, Layup $layup): RedirectResponse
    {
        $layers = $request->input('layers', []);

        \DB::transaction(function () use ($layers, $layup) {
            $offset = $layup->layers()->count() + 1000;
            foreach ($layers as $i => $item) {
                $layup->layers()->where('id', (int) $item['id'])
                    ->update(['layer_order' => $offset + $i]);
            }
            foreach ($layers as $item) {
                $layup->layers()->where('id', (int) $item['id'])
                    ->update(['layer_order' => (int) $item['order']]);
            }
        });

        $layup->bumpRevision('Layer order changed');

        return redirect()->route('suppliers.layups.show', [$supplier, $layup])
            ->with('success', 'Layer order saved.');
    }
}
