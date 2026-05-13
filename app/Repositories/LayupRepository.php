<?php

namespace App\Repositories;

use App\Contracts\Repositories\LayupRepositoryInterface;
use App\Models\Layup;
use Illuminate\Database\Eloquent\Collection;

class LayupRepository implements LayupRepositoryInterface
{
    public function allForSupplier(int $supplierId): Collection
    {
        return Layup::withCount('layers')->where('supplier_id', $supplierId)->latest()->get();
    }

    public function find(int $id): Layup
    {
        return Layup::findOrFail($id);
    }

    public function create(array $data): Layup
    {
        return Layup::create($data);
    }

    public function update(Layup $layup, array $data): Layup
    {
        $layup->update($data);

        return $layup->fresh();
    }

    public function delete(Layup $layup): void
    {
        $layup->delete();
    }

    public function findByNameAndSupplier(string $name, int $supplierId): ?Layup
    {
        return Layup::where('supplier_id', $supplierId)->where('name', $name)->first();
    }
}
