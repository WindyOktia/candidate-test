<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\LayupRepositoryInterface;
use App\Http\Requests\StoreLayupRequest;
use App\Http\Requests\UpdateLayupRequest;
use App\Models\Layup;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LayupController extends Controller
{
    public function __construct(
        private readonly LayupRepositoryInterface $layups,
    ) {}

    public function create(Supplier $supplier): View
    {
        return view('layups.create', compact('supplier'));
    }

    public function store(StoreLayupRequest $request, Supplier $supplier): RedirectResponse
    {
        $this->layups->create(array_merge(
            $request->validated(),
            ['supplier_id' => $supplier->id, 'status' => 'draft']
        ));

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'Layup created successfully.');
    }

    public function show(Supplier $supplier, Layup $layup): View
    {
        $layup->load(['layers' => fn ($q) => $q->orderBy('layer_order')]);

        return view('layups.show', compact('supplier', 'layup'));
    }

    public function edit(Supplier $supplier, Layup $layup): View
    {
        return view('layups.edit', compact('supplier', 'layup'));
    }

    public function update(UpdateLayupRequest $request, Supplier $supplier, Layup $layup): RedirectResponse
    {
        $old = $layup->only(['name', 'description']);
        $this->layups->update($layup, $request->validated());
        $layup->refresh();

        $changed = $layup->name !== $old['name'] || $layup->description !== $old['description'];
        if ($changed) {
            $layup->bumpRevision('Details updated');
        }

        return redirect()->route('suppliers.layups.show', [$supplier, $layup])
            ->with('success', 'Layup updated successfully.');
    }

    public function destroy(Supplier $supplier, Layup $layup): RedirectResponse
    {
        $this->layups->delete($layup);

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'Layup deleted successfully.');
    }

    public function activate(Supplier $supplier, Layup $layup): RedirectResponse
    {
        $layup->update(['status' => 'active']);

        return back()->with('success', 'Layup set to active.');
    }

    public function archive(Supplier $supplier, Layup $layup): RedirectResponse
    {
        $layup->update(['status' => 'archived']);

        return back()->with('success', 'Layup archived.');
    }

    public function duplicate(Supplier $supplier, Layup $layup): RedirectResponse
    {
        $newLayup = $layup->replicate();
        $newLayup->name = $layup->name . ' (Copy)';
        $newLayup->status = 'draft';
        $newLayup->revision_count = 0;
        $newLayup->revisions = null;
        $newLayup->save();

        foreach ($layup->layers()->orderBy('layer_order')->get() as $layer) {
            $newLayer = $layer->replicate();
            $newLayer->layup_id = $newLayup->id;
            $newLayer->save();
        }

        return redirect()->route('suppliers.layups.show', [$supplier, $newLayup])
            ->with('success', 'Layup duplicated successfully.');
    }
}
