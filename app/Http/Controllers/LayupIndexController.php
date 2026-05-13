<?php

namespace App\Http\Controllers;

use App\Models\Layup;

class LayupIndexController extends Controller
{
    public function index()
    {
        $layups = Layup::with('supplier')
            ->withCount('layers')
            ->latest()
            ->paginate(15);

        return view('layups.index', compact('layups'));
    }
}
