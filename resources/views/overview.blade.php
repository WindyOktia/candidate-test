<x-layouts.toolbox title="Overview" :breadcrumbs="[['label' => 'Overview', 'url' => route('overview')]]">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Overview</h1>
        <p class="text-sm text-gray-400 mt-0.5">Summary of your CLT Layup Manager data.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        @foreach([
            ['label' => 'Total Suppliers', 'value' => $stats['suppliers'], 'route' => 'suppliers.index',
             'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
             'color' => '#2d6a4f', 'bg' => '#f0f7f4'],
            ['label' => 'Total Layups', 'value' => $stats['layups'], 'route' => 'layups.index',
             'icon' => 'M4 6h16M4 12h16M4 18h16',
             'color' => '#0ea5e9', 'bg' => '#f0f9ff'],
            ['label' => 'Total Layers', 'value' => $stats['layers'], 'route' => 'layers.index',
             'icon' => 'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2',
             'color' => '#f97316', 'bg' => '#fff7ed'],
        ] as $stat)
        <a href="{{ route($stat['route']) }}"
           class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-5 flex items-center gap-4 hover:shadow-md transition group">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                 style="background:{{ $stat['bg'] }};">
                <svg class="w-6 h-6" fill="none" stroke="{{ $stat['color'] }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $stat['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($stat['value']) }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $stat['label'] }}</p>
            </div>
        </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-neutral-100">
                <h2 class="font-semibold text-gray-900">Recent Suppliers</h2>
                <a href="{{ route('suppliers.index') }}" class="text-xs font-medium hover:underline" style="color:#2d6a4f;">View all →</a>
            </div>
            @php
                $avatarHex = ['#0d9488','#f43f5e','#f97316','#8b5cf6','#0ea5e9','#2d6a4f','#f59e0b','#ec4899','#6366f1','#14b8a6'];
                $avatarColor = fn($name) => $avatarHex[ord(strtoupper($name[0])) % count($avatarHex)];
            @endphp
            <div class="divide-y divide-neutral-50">
                @forelse($recentSuppliers as $supplier)
                    <a href="{{ route('suppliers.show', $supplier) }}"
                       class="flex items-center gap-3 px-5 py-3.5 hover:bg-neutral-50 transition">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
                             style="background:{{ $avatarColor($supplier->name) }};">
                            {{ strtoupper(substr($supplier->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $supplier->name }}</p>
                            <p class="text-xs text-gray-400">{{ $supplier->code ?? 'No code' }}</p>
                        </div>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-md shrink-0"
                              style="background:#f0f7f4;color:#2d6a4f;">
                            {{ $supplier->layups_count }} {{ Str::plural('layup', $supplier->layups_count) }}
                        </span>
                    </a>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-gray-400">No suppliers yet.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-neutral-100">
                <h2 class="font-semibold text-gray-900">Recent Layups</h2>
                <a href="{{ route('layups.index') }}" class="text-xs font-medium hover:underline" style="color:#2d6a4f;">View all →</a>
            </div>
            <div class="divide-y divide-neutral-50">
                @forelse($recentLayups as $layup)
                    <a href="{{ route('suppliers.layups.show', [$layup->supplier, $layup]) }}"
                       class="flex items-center gap-3 px-5 py-3.5 hover:bg-neutral-50 transition">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                             style="background:#f0f7f4;">
                            <svg class="w-4 h-4" fill="none" stroke="#2d6a4f" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $layup->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $layup->supplier->name }}</p>
                        </div>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-md shrink-0"
                              style="background:#fff7ed;color:#f97316;">
                            {{ $layup->layers_count }} {{ Str::plural('layer', $layup->layers_count) }}
                        </span>
                    </a>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-gray-400">No layups yet.</div>
                @endforelse
            </div>
        </div>
    </div>

</x-layouts.toolbox>
