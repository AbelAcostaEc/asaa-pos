@props([
    'title'    => 'Lista',
    'subtitle' => null,
])

<div class="app-shell-panel flex flex-col gap-4 rounded-[28px] px-5 py-5 lg:flex-row lg:items-center lg:justify-between lg:px-6">
    <div>
        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400 dark:text-slate-500">Workspace</p>
        <h2 class="mt-2 text-xl font-black tracking-tight text-slate-900 dark:text-white">{{ $title }}</h2>
        @if($subtitle)
            <p class="mt-1 text-sm app-shell-muted">{{ $subtitle }}</p>
        @endif
    </div>

    @if($slot->isNotEmpty())
        <div class="flex shrink-0 items-center">{{ $slot }}</div>
    @endif
</div>
