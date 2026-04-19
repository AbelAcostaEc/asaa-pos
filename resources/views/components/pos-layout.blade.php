@php
    $supportedLocales = config('app.supported_locales', ['es' => 'Espanol', 'en' => 'English']);
    $navGroups = [
        [
            'label' => __('layout.group_general'),
            'items' => [
                [
                    'label' => __('layout.nav_dashboard'),
                    'route' => route('administration.dashboard'),
                    'active' => request()->routeIs('administration.dashboard'),
                    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                ],
            ],
        ],
        [
            'label' => __('layout.group_administration'),
            'items' => [
                [
                    'label' => __('layout.nav_users'),
                    'route' => route('administration.users.index'),
                    'active' => request()->routeIs('administration.users.*'),
                    'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                ],
                [
                    'label' => __('layout.nav_roles'),
                    'route' => route('administration.roles.index'),
                    'active' => request()->routeIs('administration.roles.*'),
                    'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                ],
            ],
        ],
        [
            'label' => __('layout.group_inventory'),
            'items' => [
                [
                    'label' => 'Entradas',
                    'route' => route('inventory.index'),
                    'active' => request()->routeIs('inventory.index'),
                    'icon' => 'M12 4v16m8-8H4m14-5l-3-3m3 3l-3 3M7 17l-3-3m3 3l-3-3',
                ],
                [
                    'label' => __('layout.nav_units'),
                    'route' => route('inventory.units.index'),
                    'active' => request()->routeIs('inventory.units.*'),
                    'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z',
                ],
                [
                    'label' => __('layout.nav_categories'),
                    'route' => route('inventory.categories.index'),
                    'active' => request()->routeIs('inventory.categories.*'),
                    'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
                ],
                [
                    'label' => __('layout.nav_products'),
                    'route' => route('inventory.products.index'),
                    'active' => request()->routeIs('inventory.products.*'),
                    'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                ],
                [
                    'label' => __('layout.nav_suppliers'),
                    'route' => route('inventory.suppliers.index'),
                    'active' => request()->routeIs('inventory.suppliers.*'),
                    'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
                ],
                [
                    'label' => __('layout.nav_customers'),
                    'route' => route('inventory.customers.index'),
                    'active' => request()->routeIs('inventory.customers.*'),
                    'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                ],
                [
                    'label' => __('layout.nav_pos'),
                    'route' => route('inventory.pos.index'),
                    'active' => request()->routeIs('inventory.pos.*'),
                    'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 4m0 0l-1.1 5M7 13l-1.1 5M7 13h10m0 0v2a2 2 0 01-2 2H9a2 2 0 01-2-2v-2m10 0H7m0 0l1.1-5M17 13l1.1-5M17 13H7',
                ],
                [
                    'label' => __('layout.nav_purchase_reports'),
                    'route' => route('inventory.reports.purchases'),
                    'active' => request()->routeIs('inventory.reports.purchases'),
                    'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16',
                ],
                [
                    'label' => __('layout.nav_sales_reports'),
                    'route' => route('inventory.reports.sales'),
                    'active' => request()->routeIs('inventory.reports.sales'),
                    'icon' => 'M4 6h16M4 14h16M4 10h8m0 0v8m0-8l-3 3m3-3l3 3',
                ],
            ],
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true', darkMode: localStorage.getItem('darkMode') === 'true', profileOpen: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'POS System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Theme Variables Injection -->
    @include('layouts.partials.theme-vars')

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="antialiased text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div class="app-orb app-orb-primary -left-24 top-0 h-72 w-72"></div>
    <div class="app-orb app-orb-accent right-0 top-24 h-80 w-80"></div>

    <div class="flex min-h-screen overflow-hidden p-3 lg:p-4">
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-30 bg-slate-950/45 backdrop-blur-sm lg:hidden"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               :style="sidebarCollapsed ? 'width: 104px; min-width: 104px;' : 'width: 290px; min-width: 290px;'"
               class="app-shell-panel fixed inset-y-3 left-3 z-40 flex w-[290px] transform flex-col rounded-[28px] transition-all duration-300 lg:static lg:inset-auto lg:translate-x-0">
            <div class="border-b border-slate-200/70 px-6 py-6 dark:border-slate-800/80">
                <div class="flex items-center gap-3" :class="{ 'justify-center': sidebarCollapsed }">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[rgb(var(--color-primary))] text-base font-black text-white shadow-lg shadow-[rgb(var(--color-primary))]/25">AP</div>
                    <div x-show="!sidebarCollapsed" x-transition.opacity>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400 dark:text-slate-500">{{ __('layout.template_badge') }}</p>
                        <h1 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">{{ config('app.name', 'ASAA POS') }}</h1>
                    </div>
                </div>
                <p x-show="!sidebarCollapsed" x-transition.opacity class="mt-4 text-sm leading-6 app-shell-muted">{{ __('layout.template_description') }}</p>
                <button type="button"
                        @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)"
                        :title="sidebarCollapsed ? '{{ __('layout.expand_sidebar') }}' : '{{ __('layout.collapse_sidebar') }}'"
                        class="mt-4 flex h-11 w-full items-center justify-center gap-2 rounded-2xl bg-slate-100 text-sm font-semibold text-slate-600 transition hover:bg-slate-900 hover:text-white dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-100 dark:hover:text-slate-900">
                    <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-180': sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-transition.opacity>{{ __('layout.collapse_sidebar') }}</span>
                </button>
            </div>

            <nav class="flex-1 space-y-6 overflow-y-auto px-4 py-5">
                @foreach($navGroups as $group)
                    <div>
                        <p x-show="!sidebarCollapsed" x-transition.opacity class="px-3 text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">{{ $group['label'] }}</p>
                        <div class="mt-2 space-y-1.5">
                            @foreach($group['items'] as $item)
                                <a href="{{ $item['route'] }}"
                                   :title="sidebarCollapsed ? '{{ $item['label'] }}' : ''"
                                   class="group flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-medium transition-all {{ $item['active'] ? 'bg-[rgb(var(--color-primary))]/12 text-[rgb(var(--color-primary))] shadow-sm ring-1 ring-[rgb(var(--color-primary))]/15' : 'text-slate-600 hover:bg-white/70 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white' }}"
                                   :class="{ 'justify-center': sidebarCollapsed }">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl {{ $item['active'] ? 'bg-[rgb(var(--color-primary))] text-white shadow-lg shadow-[rgb(var(--color-primary))]/20' : 'bg-slate-100 text-slate-500 group-hover:bg-slate-900 group-hover:text-white dark:bg-slate-900 dark:text-slate-400 dark:group-hover:bg-slate-100 dark:group-hover:text-slate-900' }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}"></path>
                                        </svg>
                                    </span>
                                    <span x-show="!sidebarCollapsed" x-transition.opacity class="flex-1">{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>

            <div x-show="!sidebarCollapsed" x-transition.opacity class="border-t border-slate-200/70 p-4 dark:border-slate-800/80">
                <div class="rounded-3xl bg-slate-950 px-4 py-4 text-white dark:bg-white dark:text-slate-900">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-white/55 dark:text-slate-500">{{ __('layout.quick_tip') }}</p>
                    <p class="mt-2 text-sm leading-6">{{ __('layout.quick_tip_body') }}</p>
                </div>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col lg:pl-4 transition-all duration-300">
            <header class="app-shell-panel sticky top-3 z-20 mb-4 flex min-h-[84px] items-center justify-between rounded-[28px] px-4 py-4 lg:px-6">
                <div class="flex min-w-0 items-center gap-4">
                    <button @click="sidebarOpen = true" class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-600 transition hover:bg-slate-900 hover:text-white dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-100 dark:hover:text-slate-900 lg:hidden">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="min-w-0">
                        @isset($header)
                            <h2 class="truncate text-2xl font-black tracking-tight text-slate-900 dark:text-white">{{ $header }}</h2>
                        @endisset
                        <p class="mt-1 hidden text-sm app-shell-muted md:block">{{ __('layout.header_subtitle') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 lg:gap-3">
                    <form method="POST" action="{{ route('locale.update') }}" class="hidden md:block">
                        @csrf
                        <label class="sr-only" for="locale-select">{{ __('layout.language') }}</label>
                        <div class="flex items-center gap-2 rounded-2xl border border-slate-200/70 bg-white/70 px-3 py-2.5 text-sm text-slate-600 dark:border-slate-800 dark:bg-slate-900/70 dark:text-slate-300">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9A18.022 18.022 0 016.5 20c-1.207 0-2.378-.119-3.5-.347m13.5-6.653c1.207 0 2.378.119 3.5.347M14 5l7 7-7 7M6 12h.01"></path>
                            </svg>
                            <select id="locale-select" name="locale" onchange="this.form.submit()" class="bg-transparent pr-6 text-sm font-medium outline-none">
                                @foreach($supportedLocales as $localeCode => $localeLabel)
                                    <option value="{{ $localeCode }}" @selected(app()->getLocale() === $localeCode)>{{ $localeLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    <div class="hidden items-center gap-3 rounded-2xl border border-slate-200/70 bg-white/70 px-4 py-2.5 text-sm text-slate-500 dark:border-slate-800 dark:bg-slate-900/70 dark:text-slate-400 xl:flex">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <span>{{ __('layout.search_placeholder') }}</span>
                    </div>

                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-600 transition hover:bg-slate-900 hover:text-white dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-100 dark:hover:text-slate-900">
                        <svg x-show="!darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9h-1m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707"></path></svg>
                    </button>

                    <div class="relative" @keydown.escape.window="profileOpen = false" @click.outside="profileOpen = false">
                        <button @click="profileOpen = !profileOpen" class="flex items-center gap-3 rounded-[22px] border border-slate-200/70 bg-white/75 px-3 py-2 transition hover:border-[rgb(var(--color-primary))]/25 hover:bg-white dark:border-slate-800 dark:bg-slate-900/75 dark:hover:bg-slate-900">
                            <div class="hidden text-right sm:block">
                                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ auth()->user()->name ?? 'Visitante' }}</p>
                                <p class="text-xs app-shell-muted">{{ __('layout.admin_role') }}</p>
                            </div>
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[rgb(var(--color-primary))]/12 font-bold text-[rgb(var(--color-primary))] ring-1 ring-[rgb(var(--color-primary))]/10">
                                {{ strtoupper(substr(auth()->user()->name ?? 'V', 0, 1)) }}
                            </div>
                            <svg class="hidden h-4 w-4 text-slate-400 transition sm:block" :class="{ 'rotate-180': profileOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="profileOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             class="app-shell-panel absolute right-0 top-[calc(100%+0.75rem)] z-30 w-64 rounded-[24px] p-2"
                             style="display: none;">
                            <div class="rounded-[20px] bg-slate-50/80 px-4 py-3 dark:bg-slate-900/80">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name ?? 'Visitante' }}</p>
                                <p class="mt-1 text-xs app-shell-muted">{{ auth()->user()->email ?? 'sin-email' }}</p>
                            </div>

                            <div class="mt-2 space-y-1">
                                <a href="{{ route('profile.edit') }}"
                                   @click="profileOpen = false"
                                   class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-white">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-500 dark:bg-slate-900 dark:text-slate-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </span>
                                    <span>{{ __('layout.profile') }}</span>
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-left text-sm font-medium text-red-600 transition hover:bg-red-50 dark:text-red-300 dark:hover:bg-red-500/10">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-red-500/10 text-red-600 dark:text-red-300">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                        </span>
                                        <span>{{ __('layout.logout') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="min-h-0 flex-1 overflow-x-hidden overflow-y-auto pb-4">
                <div class="space-y-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    <x-pos.toast-hub />
    @stack('scripts')
</body>
</html>
