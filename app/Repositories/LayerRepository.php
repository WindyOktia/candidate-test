<?php

namespace App\Repositories;

use App\Contracts\Repositories\LayerRepositoryInterface;
use App\Models\Layer;
use Illuminate\Database\Eloquent\Collection;

class LayerRepository implements LayerRepositoryInterface
{
    public function allForLayup(int $layupId): Collection
    {
        return Layer::where('layup_id', $layupId)->orderBy('layer_order')->get();
    }

    public function find(int $id): Layer
    {
        return Layer::findOrFail($id);
    }

    public function create(array $data): Layer
    {
        return Layer::create($data);
    }

    public function update(Layer $layer, array $data): Layer
    {
        $layer->update($data);

        return $layer->fresh();
    }

    public function delete(Layer $layer): void
    {
        $layer->delete();
    }

    public function findByOrderAndLayup(int $layerOrder, int $layupId): ?Layer
    {
        return Layer::where('layup_id', $layupId)->where('layer_order', $layerOrder)->first();
    }
}
