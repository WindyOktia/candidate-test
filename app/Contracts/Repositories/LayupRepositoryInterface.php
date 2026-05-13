<?php

namespace App\Contracts\Repositories;

use App\Models\Layup;
use Illuminate\Database\Eloquent\Collection;

interface LayupRepositoryInterface
{
    public function allForSupplier(int $supplierId): Collection;
    public function find(int $id): Layup;
    public function create(array $data): Layup;
    public function update(Layup $layup, array $data): Layup;
    public function delete(Layup $layup): void;
    public function findByNameAndSupplier(string $name, int $supplierId): ?Layup;
}
