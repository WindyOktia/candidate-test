<x-layouts.toolbox title="Layers" :breadcrumbs="[['label' => 'Layers', 'url' => route('layers.index')]]">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Layers</h1>
        <p class="text-sm text-gray-400 mt-0.5">All individual layers across all layups.</p>
    </div>

    @php
        $angleColors = [
            0   => ['bg' => '#dbeafe', 'text' => '#1d4ed8'],
            90  => ['bg' => '#fce7f3', 'text' => '#be185d'],
            45  => ['bg' => '#fef9c3', 'text' => '#92400e'],
            -45 => ['bg' => '#dcfce7', 'text' => '#166534'],
        ];
        $defaultAngleColor = ['bg' => '#f3f4f6', 'text' => '#374151'];
    @endphp

    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
        @if($layers->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <svg class="w-10 h-10 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                </svg>
                <p class="text-sm">No layers found.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-100">
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-400">#</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-400">Thickness</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-400">Width</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-400">Angle</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-400">Layup</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-400">Supplier</th>
                        <th class="px-5 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-50">
                    @foreach($layers as $layer)
                        @php
                            $color = $angleColors[(int)$layer->grain_angle] ?? $defaultAngleColor;
                        @endphp
                        <tr class="hover:bg-neutral-50 transition">
                            <td class="px-5 py-3.5 text-gray-400 text-xs font-medium">
                                {{ $layer->layer_order }}
                            </td>
                            <td class="px-5 py-3.5 text-gray-900 font-medium">
                                {{ number_format($layer->thickness, 1) }} mm
                            </td>
                            <td class="px-5 py-3.5 text-gray-500">
                                {{ $layer->width ? number_format($layer->width, 0).' mm' : '—' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-md"
                                      style="background:{{ $color['bg'] }};color:{{ $color['text'] }};">
                                    {{ $layer->grain_angle }}°
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <a href="{{ route('suppliers.layups.show', [$layer->layup->supplier, $layer->layup]) }}"
                                   class="text-sm font-medium hover:underline" style="color:#2d6a4f;">
                                    {{ $layer->layup->name }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5 text-gray-500 text-xs">
                                {{ $layer->layup->supplier->name }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('suppliers.layups.layers.edit', [$layer->layup->supplier, $layer->layup, $layer]) }}"
                                   class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg text-gray-600 border border-neutral-200 hover:bg-neutral-50 transition">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($layers->hasPages())
                <div class="px-5 py-4 border-t border-neutral-100">
                    {{ $layers->links() }}
                </div>
            @endif
        @endif
    </div>

</x-layouts.toolbox>
