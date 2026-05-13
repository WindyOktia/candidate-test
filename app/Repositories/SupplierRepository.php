<?php

namespace App\Repositories;

use App\Contracts\Repositories\SupplierRepositoryInterface;
use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function all(): Collection
    {
        return Supplier::withCount('layups')->latest()->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Supplier::withCount('layups')->latest()->paginate($perPage);
    }

    public function find(int $id): Supplier
    {
        return Supplier::findOrFail($id);
    }

    public function create(array $data): Supplier
    {
        return Supplier::create($data);
    }

    public function update(Supplier $supplier, array $data): Supplier
    {
        $supplier->update($data);

        return $supplier->fresh();
    }

    public function delete(Supplier $supplier): void
    {
        $supplier->delete();
    }

    public function findWithLayupsAndLayers(int $id): Supplier
    {
        return Supplier::with(['layups.layers' => fn ($q) => $q->orderBy('layer_order')])->findOrFail($id);
    }
}
