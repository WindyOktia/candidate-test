<x-layouts.toolbox title="Layups" :breadcrumbs="[['label' => 'Layups', 'url' => route('layups.index')]]">

    <div class="mb-6 flex items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Layups</h1>
            <p class="text-sm text-gray-400 mt-0.5">All layup specifications across all suppliers.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
        @if($layups->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <svg class="w-10 h-10 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <p class="text-sm">No layups found.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-100">
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-400 w-2/5">Layup Name</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-400">Supplier</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-400">Layers</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-400">Total Thickness</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-400">Status</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-400">Revision</th>
                        <th class="px-5 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-50">
                    @foreach($layups as $layup)
                        @php
                            $statusStyles = [
                                'draft'    => ['bg' => '#f9fafb', 'text' => '#6b7280', 'dot' => '#9ca3af', 'border' => '#e5e7eb', 'label' => 'Draft'],
                                'active'   => ['bg' => '#f0fdf4', 'text' => '#15803d', 'dot' => '#15803d', 'border' => '#bbf7d0', 'label' => 'Active'],
                                'archived' => ['bg' => '#fffbeb', 'text' => '#92400e', 'dot' => '#d97706', 'border' => '#fde68a', 'label' => 'Archived'],
                            ];
                            $ss = $statusStyles[$layup->status ?? 'draft'] ?? $statusStyles['draft'];
                        @endphp
                        <tr class="hover:bg-neutral-50 transition">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background:#f0f7f4;">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="#2d6a4f" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $layup->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <a href="{{ route('suppliers.show', $layup->supplier) }}"
                                   class="text-sm font-medium hover:underline" style="color:#2d6a4f;">
                                    {{ $layup->supplier->name }}
                                </a>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-md" style="background:#fff7ed;color:#f97316;">
                                    {{ $layup->layers_count }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-500">
                                {{ $layup->total_thickness ? number_format($layup->total_thickness, 1).' mm' : '—' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full border"
                                      style="background:{{ $ss['bg'] }};color:{{ $ss['text'] }};border-color:{{ $ss['border'] }};">
                                    <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:{{ $ss['dot'] }};"></span>
                                    {{ $ss['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-500 text-xs">
                                {{ $layup->revisionLabel() }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($layup->status === 'draft')
                                    <form method="POST" action="{{ route('suppliers.layups.activate', [$layup->supplier, $layup]) }}">
                                        @csrf
                                        <button type="submit"
                                                class="text-xs font-semibold text-white px-3 py-1.5 rounded-lg hover:opacity-90 transition"
                                                style="background:#2d6a4f;">
                                            Set Active
                                        </button>
                                    </form>
                                    @endif
                                    <a href="{{ route('suppliers.layups.show', [$layup->supplier, $layup]) }}"
                                       class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg border transition hover:shadow-sm"
                                       style="border-color:#2d6a4f;color:#2d6a4f;">
                                        View
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($layups->hasPages())
                <div class="px-5 py-4 border-t border-neutral-100">
                    {{ $layups->links() }}
                </div>
            @endif
        @endif
    </div>

</x-layouts.toolbox>
