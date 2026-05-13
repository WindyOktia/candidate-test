<?php

namespace App\Http\Controllers;

use App\Models\Layer;

class LayerIndexController extends Controller
{
    public function index()
    {
        $layers = Layer::with(['layup.supplier'])
            ->orderBy('layup_id')
            ->orderBy('layer_order')
            ->paginate(20);

        return view('layers.index', compact('layers'));
    }
}
