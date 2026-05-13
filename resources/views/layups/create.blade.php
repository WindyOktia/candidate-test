<x-layouts.toolbox title="Add Layup" :breadcrumbs="[
    ['label' => 'Suppliers', 'url' => route('suppliers.index')],
    ['label' => $supplier->name, 'url' => route('suppliers.show', $supplier)],
    ['label' => 'Add Layup', 'url' => '#'],
]">

    <div class="max-w-md">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Add CLT Layup</h1>
            <p class="text-sm text-gray-400 mt-0.5">Adding layup to <span class="font-medium text-gray-600">{{ $supplier->name }}</span>.</p>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6">
            <form method="POST" action="{{ route('suppliers.layups.store', $supplier) }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Layup Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="e.g. CLT-5L-90"
                           class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('name') border-red-400 @enderror"
                           style="--tw-ring-color:#2d6a4f4d;">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-gray-400">Must be unique within this supplier.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                              placeholder="e.g. Standard 5-layer panel for residential structural walls."
                              class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 resize-none @error('description') border-red-400 @enderror"
                              style="--tw-ring-color:#2d6a4f4d;">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 pt-2 border-t border-neutral-100">
                    <button type="submit"
                            class="text-sm font-semibold text-white px-5 py-2 rounded-lg hover:opacity-90 transition"
                            style="background:#2d6a4f;">
                        Create Layup
                    </button>
                    <a href="{{ route('suppliers.show', $supplier) }}"
                       class="text-sm font-medium text-gray-400 hover:text-gray-600 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.toolbox>
