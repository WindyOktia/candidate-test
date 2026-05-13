@php
    $totalThickness = $layup->layers->sum('thickness');
    $layerCount = $layup->layers->count();

    $longiColor = '#d4a76a';
    $transColor = '#b8864e';
    $angleColor = fn(float $a) => (abs((int)$a) === 90) ? '#b8864e' : '#d4a76a';

    $angleBadge = [
        0   => ['bg' => '#f0f7f4', 'text' => '#2d6a4f'],
        90  => ['bg' => '#fff7ed', 'text' => '#c2410c'],
        -90 => ['bg' => '#fff7ed', 'text' => '#c2410c'],
        45  => ['bg' => '#faf5ff', 'text' => '#7c3aed'],
        -45 => ['bg' => '#faf5ff', 'text' => '#7c3aed'],
    ];
    $defaultBadge = ['bg' => '#f3f4f6', 'text' => '#374151'];

    $statusStyles = [
        'draft'    => ['bg' => '#f9fafb', 'text' => '#6b7280', 'dot' => '#9ca3af', 'border' => '#e5e7eb', 'label' => 'Draft'],
        'active'   => ['bg' => '#f0fdf4', 'text' => '#15803d', 'dot' => '#15803d', 'border' => '#bbf7d0', 'label' => 'Active'],
        'archived' => ['bg' => '#fffbeb', 'text' => '#92400e', 'dot' => '#d97706', 'border' => '#fde68a', 'label' => 'Archived'],
    ];
    $ss = $statusStyles[$layup->status ?? 'draft'] ?? $statusStyles['draft'];

    // Dry run: find this layup's simulated layers if a preview is in session
    $drSimLayers = null;
    if (session('dry_run_preview')) {
        $drP = session('dry_run_preview');
        foreach ($drP['import_data']['layups'] ?? [] as $iLayup) {
            if ($iLayup['name'] === $layup->name) {
                $drSimLayers = $iLayup['layers'] ?? [];
                break;
            }
        }
    }
    $drTotalThickness = $drSimLayers !== null ? array_sum(array_column($drSimLayers, 'thickness')) : null;
    $drLayerCount     = $drSimLayers !== null ? count($drSimLayers) : null;
@endphp

<x-layouts.toolbox :title="$layup->name" :breadcrumbs="[
    ['label' => 'Suppliers', 'url' => route('suppliers.index')],
    ['label' => $supplier->name, 'url' => route('suppliers.show', $supplier)],
    ['label' => $layup->name, 'url' => '#'],
]">

    <div class="flex items-center justify-end gap-2 mb-4">
        @if ($layup->status === 'draft')
        <form method="POST" action="{{ route('suppliers.layups.activate', [$supplier, $layup]) }}">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-white px-4 py-2 rounded-lg shadow-sm transition hover:opacity-90"
                    style="background:#2d6a4f;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Set to Active
            </button>
        </form>
        @elseif ($layup->status === 'active')
        <form method="POST" action="{{ route('suppliers.layups.archive', [$supplier, $layup]) }}">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 bg-white border border-neutral-200 shadow-sm px-4 py-2 rounded-lg hover:bg-neutral-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                Set to Archived
            </button>
        </form>
        @endif

        <form method="POST" action="{{ route('suppliers.layups.duplicate', [$supplier, $layup]) }}">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 bg-white border border-neutral-200 shadow-sm px-4 py-2 rounded-lg hover:bg-neutral-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Duplicate
            </button>
        </form>

        <form id="reorder-form" method="POST" action="{{ route('suppliers.layups.layers.reorder', [$supplier, $layup]) }}">
            @csrf @method('PATCH')
            <div id="order-inputs"></div>
            <button type="submit"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-white px-4 py-2 rounded-lg shadow-sm transition hover:opacity-90"
                    style="background:#2d6a4f;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                </svg>
                Save Changes
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6 mb-5">
        <div class="flex items-start gap-6">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-2xl font-bold text-gray-900">Layup Specification: {{ $layup->name }}</h1>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full border shrink-0"
                          style="background:{{ $ss['bg'] }};color:{{ $ss['text'] }};border-color:{{ $ss['border'] }};">
                        <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:{{ $ss['dot'] }};"></span>
                        {{ $ss['label'] }}
                    </span>
                </div>
                <p class="text-sm text-gray-400">{{ $layup->description ?: 'Standard CLT panel — ' . $supplier->name }}</p>
            </div>
            <div class="flex items-stretch divide-x divide-neutral-100 border border-neutral-100 rounded-xl overflow-hidden shrink-0">
                <div class="px-5 py-3">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-1">Created By</p>
                    <p class="text-sm font-semibold text-gray-800">Engineering</p>
                </div>
                <div class="px-5 py-3">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-1">Last Modified</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $layup->updated_at->format('M d, Y') }}</p>
                </div>
                <div class="px-5 py-3">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-1">Revision</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $layup->revisionLabel() }}</p>
                </div>
                <div class="px-5 py-3">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-1">Total Thickness</p>
                    @if ($drSimLayers !== null)
                        <p class="text-xl font-bold" style="color:#2d6a4f;">{{ number_format($drTotalThickness, 0) }}mm</p>
                        @if ($drTotalThickness != $totalThickness)
                            <p class="text-[10px] text-gray-400 line-through">was {{ number_format($totalThickness, 0) }}mm</p>
                        @endif
                    @else
                        <p class="text-xl font-bold" style="color:#2d6a4f;">{{ number_format($totalThickness, 0) }}mm</p>
                    @endif
                </div>
                <div class="px-5 py-3">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-1">Total Layers</p>
                    @if ($drSimLayers !== null)
                        <p class="text-xl font-bold" style="color:#2d6a4f;">{{ $drLayerCount }} {{ Str::plural('Layer', $drLayerCount) }}</p>
                        @if ($drLayerCount != $layerCount)
                            <p class="text-[10px] text-gray-400 line-through">was {{ $layerCount }}</p>
                        @endif
                    @else
                        <p class="text-xl font-bold" style="color:#2d6a4f;">{{ $layerCount }} {{ Str::plural('Layer', $layerCount) }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        <div class="flex flex-col gap-5">

        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-neutral-100">
                <h2 class="font-semibold text-gray-900">Layer Composition</h2>
                <a href="{{ route('suppliers.layups.layers.create', [$supplier, $layup]) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-white px-3 py-1.5 rounded-lg hover:opacity-90 transition"
                   style="background:#2d6a4f;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Layer
                </a>
            </div>

            @if ($layup->layers->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                    <svg class="w-10 h-10 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <p class="text-sm font-medium">No layers yet</p>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-neutral-100">
                            <th class="w-8 px-3 py-3"></th>                            <th class="text-left px-3 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Order</th>
                            <th class="text-left px-3 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Thickness</th>
                            <th class="text-left px-3 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Width</th>
                            <th class="text-left px-3 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Angle</th>
                            <th class="text-left px-3 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Grade</th>
                            <th class="px-3 py-3"></th>
                        </tr>
                    </thead>
                    <tbody id="layer-tbody" class="divide-y divide-neutral-50">
                        @if ($drSimLayers !== null)
                            {{-- Dry Run: show simulated layers with status highlights --}}
                            @foreach ($drSimLayers as $drLayer)
                                @php
                                    $badge = $angleBadge[(int)$drLayer['angle']] ?? $defaultBadge;
                                    $isTransverse = abs((int)$drLayer['angle']) === 90;
                                    $drStatus = $drLayer['status'] ?? 'unchanged';
                                    $drRowBg = match($drStatus) {
                                        'added'   => 'background:#f0fdf4;',
                                        'updated' => 'background:#fffbeb;',
                                        default   => '',
                                    };
                                @endphp
                                <tr class="transition layer-row"
                                    style="{{ $drRowBg }}"
                                    data-layer-id="dr-{{ $loop->iteration }}"
                                    data-thickness="{{ $drLayer['thickness'] }}"
                                    data-width="{{ $drLayer['width'] }}"
                                    data-angle="{{ $drLayer['angle'] }}"
                                    data-color="{{ $angleColor($drLayer['angle']) }}">
                                    <td class="px-3 py-3 text-gray-200">
                                        <svg class="w-4 h-4 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M7 2a2 2 0 110 4 2 2 0 010-4zm6 0a2 2 0 110 4 2 2 0 010-4zM7 8a2 2 0 110 4 2 2 0 010-4zm6 0a2 2 0 110 4 2 2 0 010-4zM7 14a2 2 0 110 4 2 2 0 010-4zm6 0a2 2 0 110 4 2 2 0 010-4z"/>
                                        </svg>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="layer-order-num text-xs font-semibold text-gray-500">{{ $drLayer['layer_order'] }}</span>
                                    </td>
                                    <td class="px-3 py-3 font-mono text-xs text-gray-700">
                                        @if ($drStatus === 'updated' && isset($drLayer['old']['thickness']) && $drLayer['old']['thickness'] != $drLayer['thickness'])
                                            <span class="text-gray-400 line-through mr-1">{{ number_format($drLayer['old']['thickness'], 0) }}mm</span>
                                        @endif
                                        {{ number_format($drLayer['thickness'], 0) }}mm
                                    </td>
                                    <td class="px-3 py-3 font-mono text-xs text-gray-700">
                                        @if ($drStatus === 'updated' && isset($drLayer['old']['width']) && $drLayer['old']['width'] != $drLayer['width'])
                                            <span class="text-gray-400 line-through mr-1">{{ number_format($drLayer['old']['width'], 0) }}mm</span>
                                        @endif
                                        {{ number_format($drLayer['width'], 0) }}mm
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-1.5">
                                            @if ($drStatus === 'updated' && isset($drLayer['old']['angle']) && $drLayer['old']['angle'] != $drLayer['angle'])
                                                <span class="text-gray-400 line-through text-xs">{{ $drLayer['old']['angle'] }}°</span>
                                            @endif
                                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-md"
                                                  style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};">
                                                @if($isTransverse)
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                                    </svg>
                                                @endif
                                                {{ $drLayer['angle'] }}°
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-xs">
                                        @if ($drStatus === 'added')
                                            <span class="text-[9px] font-bold uppercase tracking-widest px-1.5 py-0.5 rounded" style="background:#d1fae5;color:#065f46;">New</span>
                                        @elseif ($drStatus === 'updated')
                                            <span class="text-[9px] font-bold uppercase tracking-widest px-1.5 py-0.5 rounded" style="background:#fef3c7;color:#92400e;">Updated</span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-xs text-gray-300 text-right italic">preview</td>
                                </tr>
                            @endforeach
                        @else
                            {{-- Real DB layers --}}
                            @foreach ($layup->layers as $layer)
                                @php
                                    $badge = $angleBadge[(int)$layer->angle] ?? $defaultBadge;
                                    $isTransverse = abs((int)$layer->angle) === 90;
                                @endphp
                                <tr class="hover:bg-neutral-50 transition layer-row"
                                    draggable="true"
                                    data-layer-id="{{ $layer->id }}"
                                    data-thickness="{{ $layer->thickness }}"
                                    data-width="{{ $layer->width }}"
                                    data-angle="{{ $layer->angle }}"
                                    data-color="{{ $angleColor($layer->angle) }}">
                                    <td class="px-3 py-3 cursor-grab active:cursor-grabbing">
                                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M7 2a2 2 0 110 4 2 2 0 010-4zm6 0a2 2 0 110 4 2 2 0 010-4zM7 8a2 2 0 110 4 2 2 0 010-4zm6 0a2 2 0 110 4 2 2 0 010-4zM7 14a2 2 0 110 4 2 2 0 010-4zm6 0a2 2 0 110 4 2 2 0 010-4z"/>
                                        </svg>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="layer-order-num text-xs font-semibold text-gray-500">{{ $layer->layer_order }}</span>
                                    </td>
                                    <td class="px-3 py-3 font-mono text-xs text-gray-700">{{ number_format($layer->thickness, 0) }}mm</td>
                                    <td class="px-3 py-3 font-mono text-xs text-gray-700">{{ number_format($layer->width, 0) }}mm</td>
                                    <td class="px-3 py-3">
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-md"
                                              style="background:{{ $badge['bg'] }};color:{{ $badge['text'] }};">
                                            @if($isTransverse)
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                            @else
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                                </svg>
                                            @endif
                                            {{ $layer->angle }}°
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 text-xs text-gray-600">
                                        @if ($layer->grade || $layer->species)
                                            <span class="font-medium">{{ $layer->grade ?? '—' }}</span>
                                            @if ($layer->species)
                                                <span class="block text-gray-400 text-[10px]">{{ $layer->species }}</span>
                                            @endif
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('suppliers.layups.layers.edit', [$supplier, $layup, $layer]) }}"
                                               class="px-2 py-1 text-xs font-medium text-gray-500 hover:text-gray-800 rounded hover:bg-neutral-100 transition">Edit</a>
                                            <form method="POST" action="{{ route('suppliers.layups.layers.destroy', [$supplier, $layup, $layer]) }}"
                                                  onsubmit="return confirm('Delete Layer #{{ $layer->layer_order }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="px-2 py-1 text-xs font-medium text-red-400 hover:text-red-600 rounded hover:bg-red-50 transition">Del</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="border-t border-neutral-200 bg-neutral-50">
                            <td colspan="4" class="px-3 py-3 text-xs font-medium text-gray-500">
                                @if ($drSimLayers !== null)
                                    Showing {{ $drLayerCount }} {{ Str::plural('layer', $drLayerCount) }} <span style="color:#2d6a4f;">(simulation)</span>
                                @else
                                    Showing {{ $layerCount }} {{ Str::plural('layer', $layerCount) }}
                                @endif
                            </td>
                            <td colspan="3" class="px-3 py-3 text-xs font-semibold text-gray-700 text-right">
                                @if ($drSimLayers !== null)
                                    Simulated Sum: &nbsp;<span style="color:#2d6a4f;">{{ number_format($drTotalThickness, 2) }} mm</span>
                                @else
                                    Calculated Sum: &nbsp;<span style="color:#2d6a4f;">{{ number_format($totalThickness, 2) }} mm</span>
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm px-5 py-4 flex items-start gap-3">
            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 border-2" style="border-color:#d4a76a;color:#b8864e;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Engineering Note</p>
                <p class="text-xs text-gray-500 mt-1">Drag rows to reorder layers, then click <strong>Save Changes</strong> to persist the new layer order. Ensure bonding pressure is adjusted for varying layer grades and verify alignment of transverse layers.</p>
            </div>
        </div>

    </div>
        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-neutral-100">
                <h2 class="font-semibold text-gray-900">Structure Visualizer</h2>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5 text-xs text-gray-500">
                        <span class="w-3 h-3 rounded-sm inline-block" style="background:#d4a76a;"></span>
                        Longitudinal (0°)
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-500">
                        <span class="w-3 h-3 rounded-sm inline-block" style="background:#b8864e;"></span>
                        Transverse (90°)
                    </div>
                </div>
            </div>

            <div class="p-5">
                @if ($drSimLayers !== null)
                    @if (empty($drSimLayers))
                        <div class="flex items-center justify-center h-40 text-gray-300 text-sm">No layers to visualize</div>
                    @else
                        @php
                            $maxT = max(array_column($drSimLayers, 'thickness')) ?: 1;
                            $maxW = max(array_column($drSimLayers, 'width')) ?: 1;
                        @endphp
                        <div class="flex gap-0">
                            <div class="flex flex-col items-end shrink-0 w-20 relative">
                                <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 text-right leading-tight mb-2">TOP<br><span class="font-normal">(OUTSIDE)</span></p>
                                <div class="flex-1 border-r-2 border-dashed border-neutral-300 mr-0 w-full"></div>
                                <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 text-right leading-tight mt-2">BOTTOM<br><span class="font-normal">(INSIDE)</span></p>
                            </div>
                            <div id="visualizer-layers" class="flex-1 flex flex-col gap-0.5 pl-4 py-1">
                                @foreach ($drSimLayers as $drVisFi => $drVLayer)
                                    @php
                                        $isT  = abs((int)$drVLayer['angle']) === 90;
                                        $bg   = $isT ? $transColor : $longiColor;
                                        $hPx  = max(28, (int)(sqrt($drVLayer['thickness'] / $maxT) * 100));
                                        $wPct = max(40, (int)(($drVLayer['width'] / $maxW) * 100));
                                        $icon = $isT ? '↺' : '↑';
                                        $drVisStatus = $drVLayer['status'] ?? 'unchanged';
                                        $visBorder = match($drVisStatus) {
                                            'added'   => 'outline:2px solid #22c55e;outline-offset:-2px;',
                                            'updated' => 'outline:2px solid #f59e0b;outline-offset:-2px;',
                                            default   => '',
                                        };
                                    @endphp
                                    <div class="flex justify-center">
                                        <div class="flex items-center justify-between rounded px-3 text-white text-xs font-medium select-none"
                                             style="height:{{ $hPx }}px; width:{{ $wPct }}%; background:{{ $bg }};{{ $visBorder }}">
                                            <span>L{{ $drVisFi + 1 }} ({{ number_format($drVLayer['thickness'], 0) }}mm)</span>
                                            <span class="text-base opacity-90">{{ $icon }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <p class="text-center text-[10px] text-gray-400 mt-5">Cross-Laminated Structural Assembly <span style="color:#2d6a4f;">(Simulated)</span></p>
                        <p class="text-center text-[9px] text-gray-300 mt-0.5 italic">Note: 3D orientation is for schematic purposes. All layers bonded with industrial-grade adhesives.</p>
                    @endif
                @elseif ($layup->layers->isEmpty())
                    <div class="flex items-center justify-center h-40 text-gray-300 text-sm">
                        No layers to visualize
                    </div>
                @else
                    @php
                        $maxT = $layup->layers->max('thickness') ?: 1;
                        $maxW = $layup->layers->max('width') ?: 1;
                    @endphp

                    <div class="flex gap-0">
                        <div class="flex flex-col items-end shrink-0 w-20 relative">
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 text-right leading-tight mb-2">TOP<br><span class="font-normal">(OUTSIDE)</span></p>
                            <div class="flex-1 border-r-2 border-dashed border-neutral-300 mr-0 w-full"></div>
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 text-right leading-tight mt-2">BOTTOM<br><span class="font-normal">(INSIDE)</span></p>
                        </div>

                        <div id="visualizer-layers" class="flex-1 flex flex-col gap-0.5 pl-4 py-1">
                            @foreach ($layup->layers as $layer)
                                @php
                                    $isT = abs((int)$layer->angle) === 90;
                                    $bg = $isT ? $transColor : $longiColor;
                                    $hPx = max(28, (int)(sqrt($layer->thickness / $maxT) * 100));
                                    $wPct = max(40, (int)(($layer->width / $maxW) * 100));
                                    $icon = $isT ? '↺' : '↑';
                                @endphp
                                <div class="flex justify-center"
                                     data-vis-id="{{ $layer->id }}"
                                     data-vis-thickness="{{ $layer->thickness }}"
                                     data-vis-width="{{ $layer->width }}"
                                     data-vis-angle="{{ $layer->angle }}">
                                    <div class="flex items-center justify-between rounded px-3 text-white text-xs font-medium select-none"
                                         style="height:{{ $hPx }}px; width:{{ $wPct }}%; background:{{ $bg }};">
                                        <span>L{{ $loop->iteration }} ({{ number_format($layer->thickness, 0) }}mm)</span>
                                        <span class="text-base opacity-90">{{ $icon }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p class="text-center text-[10px] text-gray-400 mt-5">Cross-Laminated Structural Assembly</p>
                    <p class="text-center text-[9px] text-gray-300 mt-0.5 italic">Note: 3D orientation is for schematic purposes. All layers bonded with industrial-grade adhesives.</p>
                @endif
            </div>
        </div>
    </div>

    <script>
    (function() {
        const tbody = document.getElementById('layer-tbody');
        if (!tbody) return;

        let dragSrc = null;

        tbody.querySelectorAll('.layer-row').forEach(row => {
            row.addEventListener('dragstart', e => {
                dragSrc = row;
                e.dataTransfer.effectAllowed = 'move';
                setTimeout(() => row.classList.add('opacity-40'), 0);
            });
            row.addEventListener('dragend', () => {
                row.classList.remove('opacity-40');
                updateAll();
            });
            row.addEventListener('dragover', e => {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
            });
            row.addEventListener('drop', e => {
                e.preventDefault();
                if (dragSrc && dragSrc !== row) {
                    const rows = [...tbody.querySelectorAll('.layer-row')];
                    const srcIdx = rows.indexOf(dragSrc);
                    const tgtIdx = rows.indexOf(row);
                    if (srcIdx < tgtIdx) tbody.insertBefore(dragSrc, row.nextSibling);
                    else tbody.insertBefore(dragSrc, row);
                }
            });
        });

        function updateAll() {
            const rows = [...tbody.querySelectorAll('.layer-row')];

            rows.forEach((row, i) => {
                const span = row.querySelector('.layer-order-num');
                if (span) span.textContent = i + 1;
            });

            const container = document.getElementById('order-inputs');
            container.innerHTML = '';
            rows.forEach((row, i) => {
                const id = row.dataset.layerId;
                const inp1 = document.createElement('input');
                inp1.type = 'hidden'; inp1.name = 'layers[' + i + '][id]'; inp1.value = id;
                const inp2 = document.createElement('input');
                inp2.type = 'hidden'; inp2.name = 'layers[' + i + '][order]'; inp2.value = i + 1;
                container.appendChild(inp1);
                container.appendChild(inp2);
            });

            rebuildVisualizer(rows);
        }

        function rebuildVisualizer(rows) {
            const container = document.getElementById('visualizer-layers');
            if (!container) return;

            const data = rows.map((row, i) => ({
                id: row.dataset.layerId,
                thickness: parseFloat(row.dataset.thickness),
                width: parseFloat(row.dataset.width),
                angle: parseFloat(row.dataset.angle),
                idx: i + 1,
            }));

            const maxT = Math.max(...data.map(l => l.thickness)) || 1;
            const maxW = Math.max(...data.map(l => l.width)) || 1;

            container.innerHTML = data.map(layer => {
                const isT = Math.abs(layer.angle) === 90;
                const bg = isT ? '#b8864e' : '#d4a76a';
                const hPx = Math.max(28, Math.round(Math.sqrt(layer.thickness / maxT) * 100));
                const wPct = Math.max(40, Math.round((layer.width / maxW) * 100));
                const icon = isT ? '↺' : '↑';
                return `<div class="flex justify-center">
                    <div class="flex items-center justify-between rounded px-3 text-white text-xs font-medium select-none"
                         style="height:${hPx}px;width:${wPct}%;background:${bg};">
                        <span>L${layer.idx} (${Math.round(layer.thickness)}mm)</span>
                        <span style="font-size:1rem;opacity:0.9">${icon}</span>
                    </div>
                </div>`;
            }).join('<div style="height:2px"></div>');
        }

        updateAll();
    })();
    </script>

</x-layouts.toolbox>
