<x-layouts.toolbox title="Settings" :breadcrumbs="[['label' => 'Settings', 'url' => route('settings')]]">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="text-sm text-gray-400 mt-0.5">Manage your account and preferences.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <aside>
            <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
                <nav class="divide-y divide-neutral-50">
                    @foreach([
                        ['label' => 'Profile',  'hash' => '#profile'],
                        ['label' => 'Password', 'hash' => '#password'],
                        ['label' => 'Danger Zone', 'hash' => '#danger'],
                    ] as $item)
                    <a href="{{ $item['hash'] }}"
                       class="flex items-center justify-between px-4 py-3.5 text-sm font-medium text-gray-600 hover:bg-neutral-50 transition group">
                        {{ $item['label'] }}
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endforeach
                </nav>
            </div>
        </aside>

        <div class="lg:col-span-2 space-y-5">

            <section id="profile" class="bg-white rounded-2xl border border-neutral-200 shadow-sm">
                <div class="px-6 py-5 border-b border-neutral-100">
                    <h2 class="font-semibold text-gray-900">Profile Information</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Update your name and email address.</p>
                </div>
                <form method="POST" action="{{ route('profile.update') }}" class="px-6 py-5 space-y-4">
                    @csrf
                    @method('patch')

                    @if (session('status') === 'profile-updated')
                        <div class="flex items-center gap-2 text-sm text-green-700 bg-green-50 border border-green-200 px-3 py-2 rounded-lg">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Profile updated successfully.
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <p class="text-sm text-yellow-600 bg-yellow-50 border border-yellow-200 px-3 py-2 rounded-lg">
                            Your email is unverified.
                            <button form="send-verification" class="underline font-semibold">Resend verification</button>
                        </p>
                        <form id="send-verification" method="POST" action="{{ route('verification.send') }}">@csrf</form>
                    @endif

                    <div class="pt-1">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg"
                                style="background:#2d6a4f;">
                            Save Changes
                        </button>
                    </div>
                </form>
            </section>

            <section id="password" class="bg-white rounded-2xl border border-neutral-200 shadow-sm">
                <div class="px-6 py-5 border-b border-neutral-100">
                    <h2 class="font-semibold text-gray-900">Change Password</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Ensure your account uses a strong password.</p>
                </div>
                <form method="POST" action="{{ route('password.update') }}" class="px-6 py-5 space-y-4">
                    @csrf
                    @method('put')

                    @if (session('status') === 'password-updated')
                        <div class="flex items-center gap-2 text-sm text-green-700 bg-green-50 border border-green-200 px-3 py-2 rounded-lg">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Password updated successfully.
                        </div>
                    @endif

                    @foreach([
                        ['name' => 'current_password', 'label' => 'Current Password', 'bag' => 'updatePassword'],
                        ['name' => 'password',          'label' => 'New Password',      'bag' => 'updatePassword'],
                        ['name' => 'password_confirmation', 'label' => 'Confirm Password', 'bag' => null],
                    ] as $field)
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">{{ $field['label'] }}</label>
                        <input type="password" name="{{ $field['name'] }}" autocomplete="{{ $field['name'] }}"
                               class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                               style="--tw-ring-color:#2d6a4f4d;">
                        @if($field['bag'])
                            @error($field['name'], $field['bag']) <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        @endif
                    </div>
                    @endforeach

                    <div class="pt-1">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg"
                                style="background:#2d6a4f;">
                            Update Password
                        </button>
                    </div>
                </form>
            </section>

            <section id="danger" class="bg-white rounded-2xl border border-red-100 shadow-sm">
                <div class="px-6 py-5 border-b border-red-100">
                    <h2 class="font-semibold text-red-700">Delete Account</h2>
                    <p class="text-xs text-red-400 mt-0.5">This action is permanent and cannot be undone.</p>
                </div>
                <div class="px-6 py-5">
                    <p class="text-sm text-gray-500 mb-4">
                        Once your account is deleted, all data will be permanently removed.
                        Before deleting, please download any data you wish to keep.
                    </p>
                    <button
                        onclick="document.getElementById('deleteAccountModal').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg bg-red-600 hover:bg-red-700 transition">
                        Delete Account
                    </button>
                </div>
            </section>

        </div>
    </div>

    <div id="deleteAccountModal"
         class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center px-4">
        <div class="bg-white rounded-2xl shadow-xl border border-neutral-200 w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Confirm Account Deletion</h3>
            <p class="text-sm text-gray-500 mb-5">Enter your password to confirm you want to permanently delete your account.</p>
            <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf
                @method('delete')
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Password</label>
                    <input type="password" name="password"
                           class="w-full px-3 py-2 text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2"
                           style="--tw-ring-color:#ef44444d;">
                    @error('password', 'userDeletion') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-3 pt-1">
                    <button type="submit"
                            class="flex-1 py-2 text-sm font-semibold text-white rounded-lg bg-red-600 hover:bg-red-700 transition">
                        Delete Account
                    </button>
                    <button type="button"
                            onclick="document.getElementById('deleteAccountModal').classList.add('hidden')"
                            class="flex-1 py-2 text-sm font-semibold text-gray-600 rounded-lg border border-neutral-200 hover:bg-neutral-50 transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.toolbox>
