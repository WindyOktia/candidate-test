@php
$navItems = [
    ['label' => 'Overview',  'route' => 'overview',       'match' => 'overview',                          'exclude' => ''],
    ['label' => 'Suppliers', 'route' => 'suppliers.index', 'match' => 'suppliers.*',                       'exclude' => 'suppliers.layups.*'],
    ['label' => 'Layups',    'route' => 'layups.index',    'match' => 'layups.*|suppliers.layups.*',        'exclude' => 'suppliers.layups.layers.*'],
    ['label' => 'Layers',    'route' => 'layers.index',    'match' => 'layers.*|suppliers.layups.layers.*', 'exclude' => ''],
    ['label' => 'Settings',  'route' => 'settings',        'match' => 'settings|profile.*',                'exclude' => ''],
];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CLT Manager') }} — {{ $title ?? 'Dashboard' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased" style="background-color:#f5f4ef; color:#1a1a1a;">

<nav class="bg-white border-b border-neutral-200 sticky top-0 z-40">
    <div class="max-w-screen-xl mx-auto px-6 flex items-center h-16 gap-8">

        <a href="{{ route('suppliers.index') }}" class="flex items-center gap-2 shrink-0">
            <svg class="w-7 h-7 shrink-0" fill="currentColor" viewBox="0 0 20 20" style="color:#2d6a4f;">
                <path d="M10 2a1 1 0 01.894.553l3 6A1 1 0 0113 10h-1v1h1a1 1 0 110 2h-1v1h1a1 1 0 110 2H7a1 1 0 110-2h1v-1H7a1 1 0 110-2h1v-1H7a1 1 0 01-.894-1.447l3-6A1 1 0 0110 2z"/>
            </svg>
            <span class="text-sm font-bold text-gray-900 tracking-tight">CLT <span style="color:#2d6a4f;">Manager</span></span>
        </a>

        <div class="flex items-center gap-0 flex-1">
            @foreach($navItems as $item)
                @php
                    $active = collect(explode('|', $item['match']))->contains(fn($p) => request()->routeIs($p))
                        && (!$item['exclude'] || !request()->routeIs($item['exclude']));
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="px-4 h-16 flex items-center text-sm font-medium border-b-2 transition"
                   style="{{ $active ? 'border-color:#2d6a4f;color:#2d6a4f;' : 'border-color:transparent;color:#6b7280;' }}"
                   onmouseover="{{ $active ? '' : "this.style.color='#374151';this.style.borderColor='#d1d5db'" }}"
                   onmouseout="{{ $active ? '' : "this.style.color='#6b7280';this.style.borderColor='transparent'" }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <button class="w-9 h-9 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </button>

            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
                     style="background:#2d6a4f;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="hidden sm:block text-right leading-tight">
                    <p class="text-xs font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-400">Administrator</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition"
                        title="Log out">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="max-w-screen-xl mx-auto px-6 py-6">

    @isset($breadcrumbs)
        @if(count($breadcrumbs) > 1)
            <nav class="flex items-center gap-1.5 text-xs text-gray-400 mb-5">
                @foreach($breadcrumbs as $crumb)
                    @if(! $loop->last)
                        <a href="{{ $crumb['url'] }}" class="hover:text-gray-700 transition">{{ $crumb['label'] }}</a>
                        <span>/</span>
                    @else
                        <span class="text-gray-600 font-medium">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @endif
    @endisset

    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm">
            <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session('dry_run_preview'))
        @php
            $drP = session('dry_run_preview');
            $drS = $drP['summary'];
            $drAdded   = array_sum(array_column($drS, 'layers_added'));
            $drUpdated = array_sum(array_column($drS, 'layers_updated'));
            $drSupId   = $drP['supplier_id'] ?? null;
        @endphp
        <div class="mb-5 rounded-xl border-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-3" style="border-color:#2d6a4f;background:#f0f7f4;">
            <div class="flex items-center gap-3 min-w-0 flex-wrap">
                <div class="flex items-center gap-1.5 shrink-0">
                    <span class="w-2 h-2 rounded-full animate-pulse inline-block" style="background:#2d6a4f;"></span>
                    <span class="text-[10px] font-bold uppercase tracking-widest" style="color:#2d6a4f;">Dry Run</span>
                </div>
                <span class="text-neutral-300 shrink-0 hidden sm:inline">|</span>
                <p class="text-sm font-semibold text-gray-800">Simulation Active <span class="font-normal text-gray-500">&mdash; nothing has been saved yet</span></p>
                <span class="text-xs text-gray-400 shrink-0">
                    {{ $drAdded }} layer{{ $drAdded !== 1 ? 's' : '' }} would be added
                    &middot; {{ $drUpdated }} updated
                </span>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                @if ($drSupId)
                    <form method="POST" action="{{ route('suppliers.import-discard', $drSupId) }}">@csrf
                        <button type="submit" class="text-xs font-semibold px-3 py-1.5 rounded-lg border-2 transition hover:bg-white" style="border-color:#2d6a4f;color:#2d6a4f;">Discard</button>
                    </form>
                    <form method="POST" action="{{ route('suppliers.import-commit', $drSupId) }}">@csrf
                        <button type="submit" class="text-xs font-semibold px-3 py-1.5 rounded-lg text-white transition hover:opacity-90" style="background:#2d6a4f;">Save Changes</button>
                    </form>
                @endif
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{ $slot }}
</div>
</body>
</html>

