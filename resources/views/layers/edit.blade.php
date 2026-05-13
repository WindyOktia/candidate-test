<x-layouts.toolbox title="Edit Layer" :breadcrumbs="[
    ['label' => 'Suppliers', 'url' => route('suppliers.index')],
    ['label' => $supplier->name, 'url' => route('suppliers.show', $supplier)],
    ['label' => $layup->name, 'url' => route('suppliers.layups.show', [$supplier, $layup])],
    ['label' => 'Edit Layer #' . $layer->layer_order, 'url' => '#'],
]">

    <div class="max-w-md">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Layer #{{ $layer->layer_order }}</h1>
            <p class="text-sm text-gray-400 mt-0.5">Part of layup <span class="font-medium text-gray-600">{{ $layup->name }}</span>.</p>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6">
            <form method="POST" action="{{ route('suppliers.layups.layers.update', [$supplier, $layup, $layer]) }}" class="space-y-5">
                @csrf @method('PATCH')

                <div class="grid grid-cols-2 gap-5">
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Layer Order <span class="text-red-500">*</span></label>
                        <input type="number" name="layer_order" value="{{ old('layer_order', $layer->layer_order) }}" min="1" required
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('layer_order') border-red-400 @enderror"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('layer_order') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Thickness (mm) <span class="text-red-500">*</span></label>
                        <input type="number" name="thickness" value="{{ old('thickness', $layer->thickness) }}" step="0.01" min="0" required
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('thickness') border-red-400 @enderror"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('thickness') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Width (mm) <span class="text-red-500">*</span></label>
                        <input type="number" name="width" value="{{ old('width', $layer->width) }}" step="0.01" min="0" required
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('width') border-red-400 @enderror"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('width') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Angle (°) <span class="text-red-500">*</span></label>
                        <input type="number" name="angle" value="{{ old('angle', $layer->angle) }}" step="0.01" required
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('angle') border-red-400 @enderror"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('angle') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Species</label>
                        <input type="text" name="species" value="{{ old('species', $layer->species) }}" placeholder="e.g. Douglas Fir"
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('species') border-red-400 @enderror"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('species') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Grade</label>
                        <input type="text" name="grade" value="{{ old('grade', $layer->grade) }}" placeholder="e.g. C24"
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('grade') border-red-400 @enderror"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('grade') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2 border-t border-neutral-100">
                    <button type="submit"
                            class="text-sm font-semibold text-white px-5 py-2 rounded-lg hover:opacity-90 transition"
                            style="background:#2d6a4f;">
                        Save Changes
                    </button>
                    <a href="{{ route('suppliers.layups.show', [$supplier, $layup]) }}"
                       class="text-sm font-medium text-gray-400 hover:text-gray-600 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.toolbox>
