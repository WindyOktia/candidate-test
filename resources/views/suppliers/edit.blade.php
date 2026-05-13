<x-layouts.toolbox title="Edit Supplier" :breadcrumbs="[
    ['label' => 'Suppliers', 'url' => route('suppliers.index')],
    ['label' => $supplier->name, 'url' => route('suppliers.show', $supplier)],
    ['label' => 'Edit', 'url' => '#'],
]">

    <div class="max-w-2xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Supplier</h1>
            <p class="text-sm text-gray-400 mt-0.5">Update details for <span class="font-medium text-gray-600">{{ $supplier->name }}</span>.</p>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6">
            <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="space-y-5">
                @csrf @method('PATCH')

                <div class="grid grid-cols-2 gap-5">
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('name') border-red-400 @enderror"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Code</label>
                        <input type="text" name="code" value="{{ old('code', $supplier->code) }}"
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 @error('code') border-red-400 @enderror"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('code') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2"
                               style="--tw-ring-color:#2d6a4f4d;">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Contact Person</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}"
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2"
                               style="--tw-ring-color:#2d6a4f4d;">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $supplier->email) }}"
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 @error('email') border-red-400 @enderror"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Address</label>
                        <textarea name="address" rows="2"
                                  class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                  style="--tw-ring-color:#2d6a4f4d;">{{ old('address', $supplier->address) }}</textarea>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Material Certifications</label>
                        <input type="text" name="material_certifications" value="{{ old('material_certifications', $supplier->material_certifications) }}"
                               placeholder="e.g. FSC, PEFC, ISO 9001"
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('material_certifications') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Last Audit Date</label>
                        <input type="date" name="last_audit_date" value="{{ old('last_audit_date', $supplier->last_audit_date?->format('Y-m-d')) }}"
                               class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('last_audit_date') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2 border-t border-neutral-100">
                    <button type="submit"
                            class="text-sm font-semibold text-white px-5 py-2 rounded-lg hover:opacity-90 transition"
                            style="background:#2d6a4f;">
                        Save Changes
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
