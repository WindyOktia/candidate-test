<?php

namespace App\Contracts\Repositories;

use App\Models\Layer;
use Illuminate\Database\Eloquent\Collection;

interface LayerRepositoryInterface
{
    public function allForLayup(int $layupId): Collection;
    public function find(int $id): Layer;
    public function create(array $data): Layer;
    public function update(Layer $layer, array $data): Layer;
    public function delete(Layer $layer): void;
    public function findByOrderAndLayup(int $layerOrder, int $layupId): ?Layer;
}
