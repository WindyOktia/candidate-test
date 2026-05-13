                        @php
    $avatarHex = ['#0d9488','#f43f5e','#f97316','#8b5cf6','#0ea5e9','#2d6a4f','#f59e0b','#ec4899','#6366f1','#14b8a6'];
    $avatarBg = $avatarHex[ord(strtoupper($supplier->name[0])) % count($avatarHex)];
@endphp

<x-layouts.toolbox :title="$supplier->name" :breadcrumbs="[
    ['label' => 'Suppliers', 'url' => route('suppliers.index')],
    ['label' => $supplier->name, 'url' => '#'],
]">

    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6 mb-5">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-lg font-bold shrink-0"
                     style="background:{{ $avatarBg }};">
                    {{ strtoupper(substr($supplier->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h1 class="text-xl font-bold text-gray-900">{{ $supplier->name }}</h1>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full border"
                              style="background:#f0f7f4;color:#2d6a4f;border-color:#bcdacc;">
                            <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#2d6a4f;"></span>
                            Active Partner
                        </span>
                    </div>
                    @if ($supplier->code)
                        <p class="text-xs font-mono text-gray-400">ID: {{ $supplier->code }}</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('suppliers.edit', $supplier) }}"
                   class="inline-flex items-center gap-1.5 text-sm font-semibold text-white px-3 py-2 rounded-lg transition hover:opacity-90"
                   style="background:#2d6a4f;">
                    Edit Supplier
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-neutral-100 mt-5 border border-neutral-100 rounded-xl overflow-hidden">
            <div class="p-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-1.5">Primary Contact</p>
                <div class="flex items-center gap-1.5 text-sm text-gray-700">
                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="truncate">{{ $supplier->email ?? $supplier->contact_person ?? '—' }}</span>
                </div>
                @if ($supplier->contact_person)
                    <p class="text-xs text-gray-400 mt-0.5 pl-5">{{ $supplier->contact_person }}</p>
                @endif
            </div>
            <div class="p-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-1.5">Location</p>
                <div class="flex items-center gap-1.5 text-sm text-gray-700">
                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="truncate">{{ $supplier->address ?? '—' }}</span>
                </div>
            </div>
            <div class="p-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-1.5">Material Certifications</p>
                <div class="flex items-center gap-1.5 text-sm text-gray-700">
                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    <span>{{ $supplier->material_certifications ?? '—' }}</span>
                </div>
            </div>
            <div class="p-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-1.5">Last Audit Date</p>
                <div class="flex items-center gap-1.5 text-sm text-gray-700">
                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ $supplier->last_audit_date ? $supplier->last_audit_date->format('M d, Y') : '—' }}</span>
                </div>
            </div>
        </div>
    </div>

    @if (session('dry_run_preview'))
    @php
        $drPreview    = session('dry_run_preview');
        $drImportData = $drPreview['import_data'];
        $drStrategy   = $drPreview['strategy'] ?? 'overwrite';
        $drRes        = $drPreview['resolutions'] ?? [];
        $drViaResolve = $drPreview['via_resolve'] ?? false;
        $drSummary    = $drPreview['summary'];
        $drTotalAdded   = array_sum(array_column($drSummary, 'layers_added'));
        $drTotalUpdated = array_sum(array_column($drSummary, 'layers_updated'));

        // Build existing DB layer map: layup_name -> layer_order -> layer
        $dbLMap = [];
        foreach ($supplier->layups as $lu) {
            $dbLMap[$lu->name] = [];
            foreach ($lu->layers->sortBy('layer_order') as $layer) {
                $dbLMap[$lu->name][$layer->layer_order] = [
                    'layer_order' => $layer->layer_order,
                    'thickness'   => (float) $layer->thickness,
                    'width'       => (float) $layer->width,
                    'angle'       => (float) $layer->angle,
                ];
            }
        }

        // Build import layer map: layup_name -> layer_order -> layer
        $impLMap = [];
        foreach ($drImportData['layups'] as $iLayup) {
            $impLMap[$iLayup['name']] = [];
            foreach ($iLayup['layers'] as $il) {
                $impLMap[$iLayup['name']][$il['layer_order']] = [
                    'layer_order' => (int)   $il['layer_order'],
                    'thickness'   => (float) $il['thickness'],
                    'width'       => (float) $il['width'],
                    'angle'       => (float) $il['angle'],
                ];
            }
        }

        // Build simulated layup list
        $simLayups = [];

        foreach ($drImportData['layups'] as $iLayup) {
            $name   = $iLayup['name'];
            $existL = $dbLMap[$name] ?? null;
            $isNew  = ($existL === null);

            if ($drStrategy === 'duplicate' && !$isNew) {
                $dupName   = $name . ' (imported)';
                $dupLayers = [];
                foreach ($iLayup['layers'] as $il) {
                    $dupLayers[] = [
                        'layer_order' => (int)   $il['layer_order'],
                        'thickness'   => (float) $il['thickness'],
                        'width'       => (float) $il['width'],
                        'angle'       => (float) $il['angle'],
                        'status'      => 'added',
                        'old'         => null,
                    ];
                }
                $simLayups[$dupName] = ['name' => $dupName, 'is_new' => true, 'has_changes' => true, 'layers' => $dupLayers];
                continue;
            }

            $simLayers = [];
            if ($isNew) {
                foreach ($iLayup['layers'] as $il) {
                    $simLayers[] = [
                        'layer_order' => (int)   $il['layer_order'],
                        'thickness'   => (float) $il['thickness'],
                        'width'       => (float) $il['width'],
                        'angle'       => (float) $il['angle'],
                        'status'      => 'added',
                        'old'         => null,
                    ];
                }
            } else {
                $impForName = $impLMap[$name];
                $allOrders  = array_unique(array_merge(array_keys($existL), array_keys($impForName)));
                sort($allOrders);

                foreach ($allOrders as $order) {
                    $exL  = $existL[$order]     ?? null;
                    $impL = $impForName[$order]  ?? null;

                    if (!$exL) {
                        $simLayers[] = array_merge($impL, ['status' => 'added', 'old' => null]);
                    } elseif (!$impL) {
                        $simLayers[] = array_merge($exL, ['status' => 'unchanged', 'old' => null]);
                    } else {
                        $diff = abs($exL['thickness'] - $impL['thickness']) > 0.001
                             || abs($exL['width']     - $impL['width'])     > 0.001
                             || abs($exL['angle']     - $impL['angle'])     > 0.001;

                        if ($drViaResolve) {
                            $key        = $name . '_' . $order;
                            $resolution = $drRes[$key] ?? 'skip';
                            if ($resolution === 'incoming') {
                                $simLayers[] = array_merge($impL, [
                                    'status' => $diff ? 'updated' : 'unchanged',
                                    'old'    => $diff ? $exL : null,
                                ]);
                            } else {
                                $simLayers[] = array_merge($exL, ['status' => 'kept', 'old' => null]);
                            }
                        } elseif ($drStrategy === 'overwrite') {
                            $simLayers[] = $diff
                                ? array_merge($impL, ['status' => 'updated', 'old' => $exL])
                                : array_merge($exL,  ['status' => 'unchanged', 'old' => null]);
                        } elseif ($drStrategy === 'skip') {
                            $simLayers[] = array_merge($exL, ['status' => 'kept', 'old' => null]);
                        } else {
                            $simLayers[] = array_merge($exL, ['status' => 'unchanged', 'old' => null]);
                        }
                    }
                }
            }

            $hasChanges = count(array_filter($simLayers, fn($l) => in_array($l['status'], ['added', 'updated']))) > 0;
            $simLayups[$name] = [
                'name'        => $name,
                'is_new'      => $isNew,
                'has_changes' => $hasChanges || $isNew,
                'layers'      => $simLayers,
            ];
        }

        // Add DB layups not touched by import (unchanged)
        foreach ($supplier->layups as $lu) {
            if (!isset($simLayups[$lu->name])) {
                $unchangedLayers = [];
                foreach ($lu->layers->sortBy('layer_order') as $layer) {
                    $unchangedLayers[] = [
                        'layer_order' => $layer->layer_order,
                        'thickness'   => (float) $layer->thickness,
                        'width'       => (float) $layer->width,
                        'angle'       => (float) $layer->angle,
                        'status'      => 'unchanged',
                        'old'         => null,
                    ];
                }
                $simLayups[$lu->name] = [
                    'name'        => $lu->name,
                    'is_new'      => false,
                    'has_changes' => false,
                    'layers'      => $unchangedLayers,
                ];
            }
        }

        $simLayups   = array_values($simLayups);
    @endphp
    @endif

    @if (session('conflicts') && session('import_data'))
    @php
        $rawConflicts   = session('conflicts', []);
        $importDataRaw  = session('import_data');
        $importDataArr  = is_array($importDataRaw) ? $importDataRaw : json_decode($importDataRaw, true);
        $importDataJson = is_string($importDataRaw) ? $importDataRaw : json_encode($importDataRaw);

        $incomingMap = [];
        foreach (($importDataArr['layups'] ?? []) as $iLayup) {
            $n = $iLayup['name'];
            $incomingMap[$n] = [];
            foreach (($iLayup['layers'] ?? []) as $il) {
                $incomingMap[$n][$il['layer_order']] = $il;
            }
        }

        $conflictsByLayup = [];
        foreach ($rawConflicts as $c) {
            $ln = $c['layup_name'];
            if (! isset($conflictsByLayup[$ln])) {
                $conflictsByLayup[$ln] = ['conflicts' => [], 'layup_obj' => null];
            }
            $conflictsByLayup[$ln]['conflicts'][] = $c;
        }
        foreach ($supplier->layups as $lu) {
            if (isset($conflictsByLayup[$lu->name])) {
                $conflictsByLayup[$lu->name]['layup_obj'] = $lu;
            }
        }

        $jsLayups = [];
        foreach ($conflictsByLayup as $layupName => $data) {
            $layupObj  = $data['layup_obj'];
            $conflicts = $data['conflicts'];

            $conflictingOrders = array_column($conflicts, 'layer_order');
            $conflictMap = [];
            foreach ($conflicts as $c) {
                $conflictMap[$c['layer_order']] = $c['differences'];
            }
            sort($conflictingOrders);
            $desc = 'Conflict in layer' . (count($conflictingOrders) > 1 ? 's ' : ' ') . implode(' & ', $conflictingOrders);

            $existingLayers = [];
            if ($layupObj) {
                foreach ($layupObj->layers as $l) {
                    $existingLayers[] = [
                        'layer_order' => $l->layer_order,
                        'thickness'   => $l->thickness,
                        'width'       => $l->width,
                        'angle'       => $l->angle,
                        'conflicted'  => in_array($l->layer_order, $conflictingOrders),
                        'differences' => $conflictMap[$l->layer_order] ?? [],
                    ];
                }
            }

            $incoming = $incomingMap[$layupName] ?? [];
            ksort($incoming);
            $incomingLayers = [];
            foreach ($incoming as $order => $il) {
                $incomingLayers[] = [
                    'layer_order' => (int) $order,
                    'thickness'   => $il['thickness'] ?? 0,
                    'width'       => $il['width']     ?? 0,
                    'angle'       => $il['angle']     ?? 0,
                    'conflicted'  => in_array((int) $order, $conflictingOrders),
                    'differences' => $conflictMap[(int) $order] ?? [],
                ];
            }

            $conflictKeys = [];
            foreach ($conflicts as $c) {
                $conflictKeys[] = $layupName . '_' . $c['layer_order'];
            }

            $jsLayups[] = [
                'name'           => $layupName,
                'description'    => $desc,
                'existingLayers' => $existingLayers,
                'incomingLayers' => $incomingLayers,
                'conflictKeys'   => $conflictKeys,
                'updatedAt'      => $layupObj ? $layupObj->updated_at->format('M d, Y') : '—',
            ];
        }
    @endphp

    <div id="conflict-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.55);">
        <div class="bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden" style="width:100%;max-width:1080px;max-height:90vh;">

            <div class="flex items-start justify-between px-6 py-4 border-b border-neutral-100 shrink-0">
                <div>
                    <div class="flex items-center gap-2.5 mb-0.5">
                        <h2 class="text-base font-bold text-gray-900">Conflict Resolution: Import</h2>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-orange-700 bg-orange-100 px-2.5 py-0.5 rounded-full">Needs Review</span>
                    </div>
                    <p class="text-xs text-gray-400">Please review discrepancies between incoming data and existing records.</p>
                </div>
                <button type="button" id="cr-close-x"
                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:bg-neutral-100 rounded-lg transition shrink-0 text-xl font-light leading-none">
                    &times;
                </button>
            </div>

            <div class="flex flex-1 overflow-hidden">

                <div class="w-72 shrink-0 border-r border-neutral-100 flex flex-col" style="max-height:calc(90vh - 65px);">
                    <div class="px-4 pt-4 pb-2 shrink-0">
                        <p class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                            Conflicting Layups (<span id="cr-count">{{ count($jsLayups) }}</span>)
                        </p>
                    </div>
                    <div id="cr-sidebar-content" class="flex-1 overflow-y-auto px-3 pb-3 space-y-1"></div>
                    <div class="p-4 border-t border-neutral-100 shrink-0">
                        <button type="button" id="cr-cancel"
                                class="w-full text-sm font-medium text-gray-700 border border-neutral-200 py-2 rounded-xl hover:bg-neutral-50 transition">
                            Cancel Import
                        </button>
                    </div>
                </div>

                <div class="flex-1 flex flex-col overflow-hidden">

                    <div class="flex items-center justify-between px-6 py-3 border-b border-neutral-100 shrink-0">
                        <div class="flex items-center gap-2.5">
                            <h3 id="cr-title" class="text-base font-bold text-gray-900">—</h3>
                            <span id="cr-layers-badge" class="text-[10px] font-bold uppercase tracking-widest text-gray-600 bg-neutral-100 px-2.5 py-0.5 rounded-full">0 LAYERS</span>
                        </div>
                        <p class="text-xs text-gray-400 shrink-0">&#9679; Differences highlighted in <strong class="text-red-500">Red</strong></p>
                    </div>

                    <div class="flex-1 overflow-y-auto p-5">
                        <div class="grid grid-cols-2 gap-4 h-full">

                            <div class="border border-neutral-200 rounded-xl flex flex-col overflow-hidden">
                                <div class="px-4 py-3 border-b border-neutral-100 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">&#9711; Existing Version</p>
                                        <p id="cr-existing-date" class="text-xs text-gray-400 mt-0.5">—</p>
                                    </div>
                                    <span class="w-2.5 h-2.5 rounded-full bg-gray-300 shrink-0"></span>
                                </div>
                                <div class="overflow-x-auto flex-1">
                                    <table class="w-full text-xs">
                                        <thead>
                                            <tr class="border-b border-neutral-100 bg-neutral-50">
                                                <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Order</th>
                                                <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Thickness<br><span class="font-normal normal-case">(mm)</span></th>
                                                <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Width<br><span class="font-normal normal-case">(mm)</span></th>
                                                <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Angle<br><span class="font-normal normal-case">(°)</span></th>
                                            </tr>
                                        </thead>
                                        <tbody id="cr-existing-tbody"></tbody>
                                    </table>
                                </div>
                                <div class="p-3 border-t border-neutral-100 shrink-0">
                                    <button id="btn-keep" type="button"
                                            class="w-full py-2.5 rounded-lg border-2 text-sm font-semibold transition hover:bg-neutral-50"
                                            style="border-color:#2d6a4f;color:#2d6a4f;">
                                        &#8635; Keep Existing
                                    </button>
                                </div>
                            </div>

                            <div class="border border-neutral-200 rounded-xl flex flex-col overflow-hidden">
                                <div class="px-4 py-3 border-b border-neutral-100 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">&#8679; Importing Version</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Source: Imported file</p>
                                    </div>
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:#2d6a4f;display:inline-block;"></span>
                                </div>
                                <div class="overflow-x-auto flex-1">
                                    <table class="w-full text-xs">
                                        <thead>
                                            <tr class="border-b border-neutral-100 bg-neutral-50">
                                                <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Order</th>
                                                <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Thickness<br><span class="font-normal normal-case">(mm)</span></th>
                                                <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Width<br><span class="font-normal normal-case">(mm)</span></th>
                                                <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Angle<br><span class="font-normal normal-case">(°)</span></th>
                                            </tr>
                                        </thead>
                                        <tbody id="cr-incoming-tbody"></tbody>
                                    </table>
                                </div>
                                <div class="p-3 border-t border-neutral-100 shrink-0">
                                    <button id="btn-accept" type="button"
                                            class="w-full py-2.5 rounded-lg text-sm font-semibold text-white transition hover:opacity-90"
                                            style="background:#2d6a4f;">
                                        &#10003; Accept New
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="flex items-center justify-between px-6 py-3 border-t border-neutral-100 shrink-0">
                        <button id="cr-prev" type="button"
                                class="text-sm font-medium text-gray-500 hover:text-gray-800 transition">
                            &larr; Previous Conflict
                        </button>
                        <span id="cr-progress" class="text-xs font-bold uppercase tracking-widest text-gray-500">
                            1 of {{ count($jsLayups) }} DISCREPANCIES
                        </span>
                        <button id="cr-next" type="button"
                                class="text-sm font-medium transition"
                                style="color:#2d6a4f;">
                            Next Conflict &rarr;
                        </button>
                    </div>

                </div>            </div>        </div>
    </div>

    <form id="cr-resolve-form" method="POST" action="{{ route('suppliers.resolve-conflicts', $supplier) }}" class="hidden">
        @csrf
        <input type="hidden" name="import_data" value="{{ $importDataJson }}">
        <input type="hidden" name="dry_run" value="{{ session('dry_run') ? '1' : '0' }}">
        <div id="cr-resolution-inputs"></div>
    </form>

    <script>
    (function () {
        const LAYUPS = @json($jsLayups);
        let pos = 0;
        const resolved = new Set();
        const resolutions = {};

        function unresolvedIndices() {
            return LAYUPS.map((_, i) => i).filter(i => !resolved.has(i));
        }

        function currentIdx() {
            return unresolvedIndices()[pos] ?? 0;
        }

        function renderSidebar() {
            const unresolved = unresolvedIndices();
            document.getElementById('cr-count').textContent = unresolved.length;

            let html = '';
            unresolved.forEach((ai, p) => {
                const l = LAYUPS[ai];
                const active = p === pos;
                html += `<div class="cursor-pointer flex items-start gap-2.5 px-3 py-2.5 rounded-xl border transition-colors ${
                    active ? 'border-[#2d6a4f] bg-[#f0f7f4]' : 'border-transparent hover:bg-neutral-50'
                }" onclick="crGoTo(${p})">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">${l.name}</p>
                        <p class="text-xs text-gray-400 truncate">${l.description}</p>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-red-500 mt-1.5 shrink-0"></span>
                </div>`;
            });

            const resolvedItems = LAYUPS.filter((_, i) => resolved.has(i));
            if (resolvedItems.length) {
                html += `<div class="mt-3 mb-1 px-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">Resolved</div>`;
                resolvedItems.forEach(l => {
                    html += `<div class="flex items-center gap-2 px-3 py-2 rounded-xl">
                        <p class="flex-1 text-sm font-medium text-gray-400 line-through truncate">${l.name}</p>
                        <svg class="w-4 h-4 shrink-0" style="color:#2d6a4f" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>`;
                });
            }

            document.getElementById('cr-sidebar-content').innerHTML = html;
        }

        function renderTable(tbodyId, layers) {
            document.getElementById(tbodyId).innerHTML = layers.map(l => {
                const rowBg = l.conflicted ? 'background:#fff1f2;' : '';
                const cells = ['thickness','width','angle'].map(f => {
                    const diff = l.differences && l.differences[f];
                    return `<td class="px-4 py-2.5" style="${diff ? 'color:#ef4444;font-weight:600;' : 'color:#374151;'}">${l[f]}</td>`;
                }).join('');
                const orderStyle = l.conflicted ? 'color:#ef4444;font-weight:600;' : 'color:#374151;';
                return `<tr style="${rowBg}border-bottom:1px solid #f3f4f6;">
                    <td class="px-4 py-2.5" style="${orderStyle}">${l.layer_order}</td>${cells}
                </tr>`;
            }).join('');
        }

        function renderMain() {
            const unresolved = unresolvedIndices();
            if (!unresolved.length) { submitAll(); return; }

            const l = LAYUPS[currentIdx()];
            document.getElementById('cr-title').textContent         = l.name + ' Comparison';
            document.getElementById('cr-layers-badge').textContent  = l.existingLayers.length + ' LAYERS';
            document.getElementById('cr-existing-date').textContent = 'Last updated: ' + l.updatedAt;

            renderTable('cr-existing-tbody', l.existingLayers);
            renderTable('cr-incoming-tbody', l.incomingLayers);

            document.getElementById('cr-progress').textContent = (pos + 1) + ' of ' + unresolved.length + ' DISCREPANCIES';
            ['cr-prev','cr-next'].forEach((id, isNext) => {
                const btn = document.getElementById(id);
                const disabled = isNext ? pos >= unresolved.length - 1 : pos === 0;
                btn.style.opacity       = disabled ? '0.3' : '1';
                btn.style.pointerEvents = disabled ? 'none' : '';
            });
        }

        window.crGoTo = function(p) {
            pos = Math.max(0, Math.min(p, unresolvedIndices().length - 1));
            renderSidebar(); renderMain();
        };

        function resolve(choice) {
            const ai = currentIdx();
            LAYUPS[ai].conflictKeys.forEach(k => { resolutions[k] = choice; });
            resolved.add(ai);
            const rem = unresolvedIndices().length;
            if (!rem) { renderSidebar(); submitAll(); return; }
            pos = Math.min(pos, rem - 1);
            renderSidebar(); renderMain();
        }

        function submitAll() {
            const container = document.getElementById('cr-resolution-inputs');
            container.innerHTML = Object.entries(resolutions)
                .map(([k, v]) => `<input type="hidden" name="resolutions[${k}]" value="${v}">`)
                .join('');
            document.getElementById('cr-resolve-form').submit();
        }

        document.getElementById('btn-keep').addEventListener('click',   () => resolve('existing'));
        document.getElementById('btn-accept').addEventListener('click',  () => resolve('incoming'));
        document.getElementById('cr-prev').addEventListener('click',     () => crGoTo(pos - 1));
        document.getElementById('cr-next').addEventListener('click',     () => crGoTo(pos + 1));
        document.getElementById('cr-cancel').addEventListener('click',   () => document.getElementById('conflict-modal').classList.add('hidden'));
        document.getElementById('cr-close-x').addEventListener('click',  () => document.getElementById('conflict-modal').classList.add('hidden'));

        renderSidebar();
        renderMain();
    })();
    </script>
    @endif

    <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden" @isset($simLayups) style="border-color:#2d6a4f;" @endisset>
        <div class="flex items-center justify-between px-5 py-4 border-b border-neutral-100">
            <div class="flex items-center gap-2.5">
                <h2 class="font-semibold text-gray-900">Associated Layups</h2>
                @isset($simLayups)
                <span class="text-[9px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-full text-white" style="background:#2d6a4f;">Dry Run Preview</span>
                @endisset
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="document.getElementById('import-modal').classList.remove('hidden')"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 border border-neutral-200 hover:bg-neutral-50 px-3 py-1.5 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Import
                </button>
                <a href="{{ route('suppliers.export', $supplier) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 border border-neutral-200 hover:bg-neutral-50 px-3 py-1.5 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export
                </a>
                <a href="{{ route('suppliers.layups.create', $supplier) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-white px-3 py-1.5 rounded-lg hover:opacity-90 transition"
                   style="background:#2d6a4f;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Layup
                </a>
            </div>
        </div>

        @isset($simLayups)

            {{-- Dry Run: simulated layup list with highlights and links to real layup pages --}}
            @if (empty($simLayups))
                <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                    <p class="text-sm font-medium">No layups in simulation</p>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-neutral-100 bg-neutral-50">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Name</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Plies</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Thickness</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Changes</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-50">
                        @foreach ($simLayups as $simLayup)
                            @php
                                $simPlyCount  = count($simLayup['layers']);
                                $simThickness = array_sum(array_column($simLayup['layers'], 'thickness'));
                                $simAdded     = count(array_filter($simLayup['layers'], fn($l) => $l['status'] === 'added'));
                                $simUpdated   = count(array_filter($simLayup['layers'], fn($l) => $l['status'] === 'updated'));
                                // Find matching existing layup for the link (new layups have no existing record)
                                $matchLayup   = $supplier->layups->firstWhere('name', $simLayup['name']);
                            @endphp
                            <tr class="transition hover:brightness-95"
                                @if ($simLayup['is_new'])   style="background:#f0fdf4;"
                                @elseif ($simLayup['has_changes']) style="background:#fffbeb;"
                                @endif>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        @if ($simLayup['is_new'])
                                            <span class="text-[9px] font-bold uppercase tracking-widest px-1.5 py-0.5 rounded shrink-0" style="background:#bcdacc;color:#1b4332;">New</span>
                                        @elseif ($simLayup['has_changes'])
                                            <span class="text-[9px] font-bold uppercase tracking-widest px-1.5 py-0.5 rounded shrink-0" style="background:#fef3c7;color:#92400e;">Changed</span>
                                        @endif
                                        @if ($matchLayup)
                                            <a href="{{ route('suppliers.layups.show', [$supplier, $matchLayup]) }}"
                                               class="font-semibold text-gray-900 hover:underline">{{ $simLayup['name'] }}</a>
                                        @else
                                            <span class="font-semibold text-gray-900">{{ $simLayup['name'] }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[1.75rem] h-6 px-2 rounded-md text-xs font-semibold" style="background:#f0f7f4;color:#2d6a4f;">
                                        {{ $simPlyCount }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-xs text-gray-500">{{ $simThickness ? number_format($simThickness, 0).'mm' : '—' }}</td>
                                <td class="px-5 py-3.5 text-xs">
                                    @if ($simAdded || $simUpdated)
                                        @if ($simAdded)<span style="color:#2d6a4f;" class="font-medium">+{{ $simAdded }} layer{{ $simAdded !== 1 ? 's' : '' }}</span>@endif
                                        @if ($simAdded && $simUpdated)<span class="text-gray-300 mx-1">·</span>@endif
                                        @if ($simUpdated)<span class="text-amber-600 font-medium">{{ $simUpdated }} updated</span>@endif
                                    @else
                                        <span class="text-gray-400">No changes</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    @if ($matchLayup)
                                        <a href="{{ route('suppliers.layups.show', [$supplier, $matchLayup]) }}"
                                           class="text-xs font-medium text-gray-400 hover:text-gray-700 transition">View →</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-5 py-3.5 border-t border-neutral-100">
                    <p class="text-xs text-gray-400">{{ count($simLayups) }} layup(s) — <span style="color:#2d6a4f;">Dry Run preview, click a row to inspect layer changes</span></p>
                </div>
            @endif

        @else

            @if ($supplier->layups->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                    <svg class="w-10 h-10 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <p class="text-sm font-medium">No layups yet</p>
                    <p class="text-xs mt-1">Add the first CLT layup for this supplier.</p>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-neutral-100">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Layup ID</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Name</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Thickness</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Ply Count</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Species/Grade</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Revision</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-50">
                        @foreach ($supplier->layups as $layup)
                            @php
                                $layupId = 'L-' . str_pad($supplier->id, 3, '0', STR_PAD_LEFT) . '-' . chr(64 + $loop->iteration);
                                $thickness = $layup->layers->sum('thickness');
                                $plyCount  = $layup->layers->count();
                                $statusStyles = [
                                    'draft'    => ['bg' => '#f9fafb', 'text' => '#6b7280', 'dot' => '#9ca3af', 'border' => '#e5e7eb', 'label' => 'Draft'],
                                    'active'   => ['bg' => '#f0fdf4', 'text' => '#15803d', 'dot' => '#15803d', 'border' => '#bbf7d0', 'label' => 'Active'],
                                    'archived' => ['bg' => '#fffbeb', 'text' => '#92400e', 'dot' => '#d97706', 'border' => '#fde68a', 'label' => 'Archived'],
                                ];
                                $ss = $statusStyles[$layup->status ?? 'draft'] ?? $statusStyles['draft'];
                                // Unique species/grades across layers
                                $layupSpecies = $layup->layers->pluck('species')->filter()->unique()->values();
                                $layupGrades  = $layup->layers->pluck('grade')->filter()->unique()->values();
                            @endphp
                            <tr class="hover:bg-neutral-50 transition">
                                <td class="px-5 py-3.5 font-mono text-xs text-gray-500">{{ $layupId }}</td>
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('suppliers.layups.show', [$supplier, $layup]) }}"
                                       class="font-semibold text-gray-900 hover:underline">
                                        {{ $layup->name }}
                                    </a>
                                </td>
                                <td class="px-5 py-3.5 text-gray-600 font-mono text-xs">
                                    {{ $thickness ? number_format($thickness, 0).'mm' : '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[1.75rem] h-6 px-2 rounded-md text-xs font-semibold"
                                          style="background:#f0f7f4;color:#2d6a4f;">
                                        {{ $plyCount }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-gray-600">
                                    @if ($layupGrades->isNotEmpty() || $layupSpecies->isNotEmpty())
                                        <span class="font-medium">{{ $layupGrades->implode(', ') ?: '—' }}</span>
                                        @if ($layupSpecies->isNotEmpty())
                                            <span class="block text-gray-400 text-[10px]">{{ $layupSpecies->implode(', ') }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $layup->revisionLabel() }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full border"
                                          style="background:{{ $ss['bg'] }};color:{{ $ss['text'] }};border-color:{{ $ss['border'] }};">
                                        <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:{{ $ss['dot'] }};"></span>
                                        {{ $ss['label'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1">
                                        @if ($layup->status === 'draft')
                                        <form method="POST" action="{{ route('suppliers.layups.activate', [$supplier, $layup]) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-1.5 text-xs font-semibold text-white rounded-lg hover:opacity-90 transition"
                                                    style="background:#2d6a4f;">
                                                Set Active
                                            </button>
                                        </form>
                                        @endif
                                        <a href="{{ route('suppliers.layups.show', [$supplier, $layup]) }}"
                                           class="px-3 py-1.5 text-xs font-medium text-gray-500 hover:text-gray-800 rounded-lg hover:bg-neutral-100 transition">
                                            View
                                        </a>
                                        <a href="{{ route('suppliers.layups.edit', [$supplier, $layup]) }}"
                                           class="px-3 py-1.5 text-xs font-medium text-gray-500 hover:text-gray-800 rounded-lg hover:bg-neutral-100 transition">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('suppliers.layups.destroy', [$supplier, $layup]) }}"
                                              onsubmit="return confirm('Delete layup {{ addslashes($layup->name) }}?')">
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
                <div class="px-5 py-3.5 border-t border-neutral-100">
                    <p class="text-xs text-gray-400">Showing {{ $supplier->layups->count() }} of {{ $supplier->layups->count() }} layups</p>
                </div>
            @endif

        @endisset
    </div>

    <div id="import-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.5);">
        <div class="bg-white rounded-2xl shadow-2xl w-full overflow-hidden" style="max-width:520px;">

            <div class="flex items-start justify-between px-6 py-5 border-b border-neutral-100">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Import Layup Data</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Upload an Excel file to import layups into <strong class="text-gray-600">{{ $supplier->name }}</strong>.</p>
                </div>
                <button type="button" onclick="closeImportModal()"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:bg-neutral-100 transition text-xl font-light leading-none shrink-0">
                    &times;
                </button>
            </div>

            <form method="POST" action="{{ route('suppliers.import', $supplier) }}" enctype="multipart/form-data" id="import-form">
                @csrf
                <input type="hidden" name="dry_run" id="dry-run-input" value="0">

                <div class="px-6 pt-5 pb-2 space-y-4">

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Upload File <span class="text-red-500">*</span></label>

                        <div id="import-dropzone"
                             class="border-2 border-dashed rounded-xl p-8 text-center cursor-pointer transition"
                             style="border-color:#d1d5db;"
                             onclick="document.getElementById('file-input').click()"
                             ondragover="event.preventDefault();this.style.borderColor='#2d6a4f';"
                             ondragleave="this.style.borderColor='#d1d5db';"
                             ondrop="event.preventDefault();this.style.borderColor='#d1d5db';const f=event.dataTransfer.files[0];if(f){assignFile(f);}">
                            <svg class="w-10 h-10 mx-auto mb-3" fill="none" stroke="#2d6a4f" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-sm font-semibold" style="color:#2d6a4f;">Click to upload <span class="font-normal text-gray-400">or drag and drop</span></p>
                            <p class="text-xs text-gray-400 mt-1">Excel (.xlsx) &mdash;
                                <a href="{{ route('suppliers.import-template') }}" class="underline hover:no-underline" style="color:#2d6a4f;" onclick="event.stopPropagation()">download template</a>
                            </p>
                        </div>

                        <div id="file-selected-bar" class="hidden w-full flex items-center gap-3 px-4 py-3.5 rounded-xl border-2" style="border-color:#2d6a4f;background:#f0f7f4;">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="#2d6a4f" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span id="file-selected-name" class="flex-1 text-sm font-medium truncate" style="color:#2d6a4f;"></span>
                            <button type="button" onclick="clearImportFile()" class="text-gray-400 hover:text-gray-600 transition shrink-0" title="Remove file">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <input id="file-input" type="file" name="file" accept=".xlsx,.xls" required class="hidden"
                               onchange="if(this.files[0])assignFile(this.files[0]);">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Conflict Strategy</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach([
                                ['overwrite', 'Overwrite',       'Replace existing with imported data',      '↑'],
                                ['skip',      'Skip Conflicts',  'Keep existing, ignore incoming changes',   '→'],
                                ['duplicate', 'Duplicate Layup', 'Create new layup with "(imported)" suffix','⊕'],
                                ['resolve',   'Review & Resolve','Manually choose per-layer conflict winner','⚖'],
                            ] as [$val, $label, $desc, $icon])
                            <label class="flex items-start gap-2.5 p-3 border rounded-xl cursor-pointer transition hover:bg-neutral-50 has-[:checked]:border-[#2d6a4f] has-[:checked]:bg-[#f0f7f4]"
                                   style="border-color:#e5e7eb;">
                                <input type="radio" name="strategy" value="{{ $val }}" {{ $val === 'resolve' ? 'checked' : '' }} class="mt-0.5 shrink-0 accent-[#2d6a4f]">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-800">{{ $icon }} {{ $label }}</p>
                                    <p class="text-[11px] text-gray-400 leading-snug mt-0.5">{{ $desc }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-neutral-50 border border-neutral-200">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Run as Dry Run</p>
                                <p class="text-xs text-gray-400 leading-snug mt-0.5">Simulate the import without saving changes to the database.</p>
                            </div>
                        </div>
                        <button type="button" id="dry-run-toggle" onclick="toggleDryRun()"
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none"
                                style="background:#d1d5db;" role="switch" aria-checked="false">
                            <span id="dry-run-thumb"
                                  class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform transition duration-200 translate-x-0"></span>
                        </button>
                    </div>

                    <div id="conflict-warning" class="hidden flex gap-3 px-4 py-3.5 rounded-xl border" style="border-color:#fcd34d;background:#fffbeb;">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="#d97706" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold" style="color:#92400e;">Potential Conflicts Detected</p>
                            <p class="text-xs mt-0.5" style="color:#b45309;"><span id="conflict-count">0</span> layup(s) differ from current data in the database.</p>
                        </div>
                    </div>

                </div>

                <div class="flex gap-3 px-6 py-4 border-t border-neutral-100 mt-2">
                    <button type="button" onclick="closeImportModal()"
                            class="px-5 text-sm font-medium text-gray-600 border border-neutral-200 py-2.5 rounded-xl hover:bg-neutral-50 transition">
                        Cancel
                    </button>
                    <button type="submit" id="import-submit-btn" disabled
                            class="flex-1 flex items-center justify-center gap-2 text-sm font-bold text-white py-2.5 rounded-xl transition disabled:opacity-40 disabled:cursor-not-allowed hover:opacity-90"
                            style="background:#2d6a4f;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span id="import-btn-label">Confirm Import</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    (function () {
        const DETECT_URL = '{{ route("suppliers.import-detect", $supplier) }}';
        const CSRF       = document.querySelector('meta[name="csrf-token"]')?.content
                        || document.querySelector('input[name="_token"]')?.value
                        || '';

        function closeImportModal() {
            document.getElementById('import-modal').classList.add('hidden');
            clearImportFile();
        }
        window.closeImportModal = closeImportModal;

        function clearImportFile() {
            document.getElementById('file-input').value = '';
            document.getElementById('file-selected-bar').classList.add('hidden');
            document.getElementById('import-dropzone').classList.remove('hidden');
            document.getElementById('conflict-warning').classList.add('hidden');
            document.getElementById('import-submit-btn').disabled = true;
        }
        window.clearImportFile = clearImportFile;

        function assignFile(file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('file-input').files = dt.files;

            document.getElementById('file-selected-name').textContent = file.name;
            document.getElementById('file-selected-bar').classList.remove('hidden');
            document.getElementById('import-dropzone').classList.add('hidden');
            document.getElementById('import-submit-btn').disabled = false;

            detectConflicts(file);
        }
        window.assignFile = assignFile;

        function detectConflicts(file) {
            const fd = new FormData();
            fd.append('file', file);
            fd.append('_token', CSRF);

            fetch(DETECT_URL, { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    const warn = document.getElementById('conflict-warning');
                    if (data.conflict_count > 0) {
                        document.getElementById('conflict-count').textContent = data.conflict_count;
                        warn.classList.remove('hidden');
                    } else {
                        warn.classList.add('hidden');
                    }
                })
                .catch(() => {});
        }

        let dryRunActive = false;
        function toggleDryRun() {
            dryRunActive = !dryRunActive;
            document.getElementById('dry-run-input').value = dryRunActive ? '1' : '0';
            document.getElementById('dry-run-toggle').style.background = dryRunActive ? '#2d6a4f' : '#d1d5db';
            document.getElementById('dry-run-toggle').setAttribute('aria-checked', dryRunActive);
            document.getElementById('dry-run-thumb').style.transform = dryRunActive ? 'translateX(20px)' : 'translateX(0)';
            document.getElementById('import-btn-label').textContent = dryRunActive ? 'Run Dry Run' : 'Confirm Import';
        }
        window.toggleDryRun = toggleDryRun;

        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfMeta) {
            const t = document.querySelector('#import-form input[name="_token"]');
            if (t) {
                const m = document.createElement('meta');
                m.name = 'csrf-token';
                m.content = t.value;
                document.head.appendChild(m);
            }
        }
    })();
    </script>
</x-layouts.toolbox>
