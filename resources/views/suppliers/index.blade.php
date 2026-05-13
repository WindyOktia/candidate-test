@php
    $avatarHex = ['#0d9488','#f43f5e','#f97316','#8b5cf6','#0ea5e9','#2d6a4f','#f59e0b','#ec4899','#6366f1','#14b8a6'];
    $supplierColor = fn(string $name) => $avatarHex[ord(strtoupper($name[0])) % count($avatarHex)];
@endphp

<x-layouts.toolbox title="Suppliers" :breadcrumbs="[['label' => 'Suppliers', 'url' => route('suppliers.index')]]">

    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Suppliers</h1>
            <p class="text-sm text-gray-400 mt-0.5">Manage timber suppliers and material sourcing.</p>
        </div>
        <a href="{{ route('suppliers.create') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-white px-4 py-2 rounded-lg shadow-sm transition hover:opacity-90"
           style="background:#2d6a4f;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Add Supplier
        </a>
    </div>

    <div class="flex items-center gap-3 mb-4">
        <div class="relative flex-1 max-w-sm">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
            </svg>
            <input type="text" placeholder="Search suppliers by name…"
                   class="w-full pl-9 pr-4 py-2 text-sm bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 shadow-sm"
                   style="--tw-ring-color:#2d6a4f4d;"
                   oninput="filterTable(this.value)">
        </div>

        <div class="flex items-center gap-2 ml-auto">
            <button class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 bg-white border border-neutral-200 px-3.5 py-2 rounded-lg shadow-sm hover:bg-neutral-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M7 8h10M11 12h2M13 16h-2"/>
                </svg>
                Filter
            </button>
            <a href="{{ route('suppliers.export-all') }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 bg-white border border-neutral-200 px-3.5 py-2 rounded-lg shadow-sm hover:bg-neutral-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export
            </a>
            
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
        @if ($suppliers->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
                <p class="text-sm font-medium">No suppliers yet</p>
                <p class="text-xs mt-1">Get started by adding your first supplier.</p>
            </div>
        @else
            <table class="w-full text-sm" id="supplier-table">
                <thead>
                    <tr class="border-b border-neutral-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Layups</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Created At</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-50" id="supplier-tbody">
                    @foreach ($suppliers as $supplier)
                        @php $bg = $supplierColor($supplier->name); @endphp
                        <tr class="hover:bg-neutral-50 transition supplier-row">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
                                         style="background:{{ $bg }};">
                                        {{ strtoupper(substr($supplier->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('suppliers.show', $supplier) }}"
                                           class="font-semibold text-gray-900 hover:underline supplier-name">
                                            {{ $supplier->name }}
                                        </a>
                                        @if ($supplier->code)
                                            <p class="text-xs text-gray-400">ID: {{ $supplier->code }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center justify-center min-w-[2rem] h-7 px-2 rounded-lg text-sm font-semibold"
                                      style="background:#f0f7f4;color:#2d6a4f;">
                                    {{ $supplier->layups_count }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $supplier->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('suppliers.show', $supplier) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-gray-500 hover:text-gray-800 rounded-lg hover:bg-neutral-100 transition">
                                        View
                                    </a>
                                    <a href="{{ route('suppliers.edit', $supplier) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-gray-500 hover:text-gray-800 rounded-lg hover:bg-neutral-100 transition">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}"
                                          onsubmit="return confirm('Delete {{ addslashes($supplier->name) }}? This will also remove all associated layups and layers.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 text-xs font-medium text-red-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($suppliers->hasPages())
                <div class="flex items-center justify-between px-5 py-4 border-t border-neutral-100">
                    <p class="text-xs text-gray-400">
                        Showing {{ $suppliers->firstItem() }} to {{ $suppliers->lastItem() }} of {{ $suppliers->total() }} results
                    </p>
                    <div class="flex items-center gap-1">
                        @if ($suppliers->onFirstPage())
                            <span class="px-3 py-1.5 text-xs text-gray-300 border border-neutral-200 rounded-lg cursor-not-allowed">← Prev</span>
                        @else
                            <a href="{{ $suppliers->previousPageUrl() }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-neutral-200 rounded-lg hover:bg-neutral-50 transition">← Prev</a>
                        @endif

                        @foreach ($suppliers->getUrlRange(1, $suppliers->lastPage()) as $page => $url)
                            @if ($page == $suppliers->currentPage())
                                <span class="px-3 py-1.5 text-xs font-semibold text-white rounded-lg" style="background:#2d6a4f;">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-neutral-200 rounded-lg hover:bg-neutral-50 transition">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($suppliers->hasMorePages())
                            <a href="{{ $suppliers->nextPageUrl() }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 border border-neutral-200 rounded-lg hover:bg-neutral-50 transition">Next →</a>
                        @else
                            <span class="px-3 py-1.5 text-xs text-gray-300 border border-neutral-200 rounded-lg cursor-not-allowed">Next →</span>
                        @endif
                    </div>
                </div>
            @else
                <div class="px-5 py-3 border-t border-neutral-100">
                    <p class="text-xs text-gray-400">Showing {{ $suppliers->total() }} {{ Str::plural('result', $suppliers->total()) }}</p>
                </div>
            @endif
        @endif
    </div>

    <script>
        function filterTable(q) {
            q = q.toLowerCase();
            document.querySelectorAll('.supplier-row').forEach(row => {
                const name = row.querySelector('.supplier-name')?.textContent.toLowerCase() ?? '';
                row.style.display = name.includes(q) ? '' : 'none';
            });
        }
    </script>
</x-layouts.toolbox>

