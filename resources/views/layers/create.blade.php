<x-layouts.toolbox title="Add Layer" :breadcrumbs="[
    ['label' => 'Suppliers', 'url' => route('suppliers.index')],
    ['label' => $supplier->name, 'url' => route('suppliers.show', $supplier)],
    ['label' => $layup->name, 'url' => route('suppliers.layups.show', [$supplier, $layup])],
    ['label' => 'Add Layer', 'url' => '#'],
]">

    <div class="max-w-md">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Add Layer</h1>
            <p class="text-sm text-gray-400 mt-0.5">Adding layer to <span class="font-medium text-gray-600">{{ $layup->name }}</span>.</p>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6">
            <form method="POST" action="{{ route('suppliers.layups.layers.store', [$supplier, $layup]) }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-2 gap-5">
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Layer Order <span class="text-red-500">*</span></label>
                        <input type="number" name="layer_order" id="layer-order-input" value="{{ old('layer_order') }}" min="1" required
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('layer_order') border-red-400 @enderror"
                               style="border-color:#e5e7eb;--tw-ring-color:#2d6a4f4d;"
                               autocomplete="off">
                        @error('layer_order') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        <p id="order-hint" class="mt-1 text-xs text-gray-400">Must be unique within this layup. Next available: #{{ ($layup->layers->max('layer_order') ?? 0) + 1 }}</p>
                        <p id="order-taken" class="hidden mt-1 text-xs font-medium text-red-500">&#9888; Layer order <span id="order-taken-val"></span> is already used in this layup — choose a different number.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Thickness (mm) <span class="text-red-500">*</span></label>
                        <input type="number" name="thickness" value="{{ old('thickness') }}" step="0.01" min="0" required
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('thickness') border-red-400 @enderror"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('thickness') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Width (mm) <span class="text-red-500">*</span></label>
                        <input type="number" name="width" value="{{ old('width') }}" step="0.01" min="0" required
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('width') border-red-400 @enderror"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('width') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Angle (°) <span class="text-red-500">*</span></label>
                        <input type="number" name="angle" value="{{ old('angle') }}" step="0.01" required
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('angle') border-red-400 @enderror"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('angle') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        <p class="mt-1 text-xs text-gray-400">Common values: 0°, 90°, 45°, -45°</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Species</label>
                        <input type="text" name="species" value="{{ old('species') }}" placeholder="e.g. Douglas Fir"
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('species') border-red-400 @enderror"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('species') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Grade</label>
                        <input type="text" name="grade" value="{{ old('grade') }}" placeholder="e.g. C24"
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('grade') border-red-400 @enderror"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('grade') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2 border-t border-neutral-100">
                    <button type="submit" id="layer-submit-btn"
                            class="text-sm font-semibold text-white px-5 py-2 rounded-lg hover:opacity-90 transition"
                            style="background:#2d6a4f;">
                        Add Layer
                    </button>
                    <a href="{{ route('suppliers.layups.show', [$supplier, $layup]) }}"
                       class="text-sm font-medium text-gray-400 hover:text-gray-600 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        @if ($layup->status === 'draft')
        <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex items-center justify-between gap-3">
            <div>
                <p class="text-xs font-semibold text-amber-800">This layup is still a Draft</p>
                <p class="text-xs text-amber-600 mt-0.5">Activate it to make it visible as a production-ready spec.</p>
            </div>
            <form method="POST" action="{{ route('suppliers.layups.activate', [$supplier, $layup]) }}">
                @csrf
                <button type="submit"
                        class="text-xs font-semibold text-white px-4 py-1.5 rounded-lg hover:opacity-90 transition whitespace-nowrap"
                        style="background:#2d6a4f;">
                    Set to Active
                </button>
            </form>
        </div>
        @endif
    </div>

    <script>
    (function () {
        const taken = @json($existingOrders);
        const input  = document.getElementById('layer-order-input');
        const hint   = document.getElementById('order-hint');
        const errEl  = document.getElementById('order-taken');
        const errVal = document.getElementById('order-taken-val');
        const btn    = document.getElementById('layer-submit-btn');

        function check() {
            const val = parseInt(input.value, 10);
            if (!isNaN(val) && taken.includes(val)) {
                input.style.borderColor = '#ef4444';
                input.style.setProperty('--tw-ring-color', '#ef44444d');
                hint.classList.add('hidden');
                errVal.textContent = val;
                errEl.classList.remove('hidden');
                btn.disabled = true;
                btn.style.opacity = '0.5';
                btn.style.cursor = 'not-allowed';
                return true;
            }
            input.style.borderColor = '#e5e7eb';
            input.style.setProperty('--tw-ring-color', '#2d6a4f4d');
            hint.classList.remove('hidden');
            errEl.classList.add('hidden');
            btn.disabled = false;
            btn.style.opacity = '';
            btn.style.cursor = '';
            return false;
        }

        input.addEventListener('input', check);
        input.addEventListener('blur',  check);
        input.closest('form').addEventListener('submit', function (e) {
            if (check()) e.preventDefault();
        });
    })();
    </script>
</x-layouts.toolbox>
