<?php

namespace App\Http\Controllers;

use App\Models\Layer;
use App\Models\Layup;
use App\Models\Supplier;

class OverviewController extends Controller
{
    public function index()
    {
        $stats = [
            'suppliers' => Supplier::count(),
            'layups'    => Layup::count(),
            'layers'    => Layer::count(),
        ];

        $recentSuppliers = Supplier::withCount('layups')
            ->latest()
            ->limit(5)
            ->get();

        $recentLayups = Layup::with('supplier')
            ->withCount('layers')
            ->latest()
            ->limit(5)
            ->get();

        return view('overview', compact('stats', 'recentSuppliers', 'recentLayups'));
    }
}
