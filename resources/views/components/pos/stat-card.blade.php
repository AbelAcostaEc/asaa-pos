@props([
    'label',
    'value',
    'hint' => null,
    'tone' => 'primary',
])

@php
    $tones = [
        'primary' => 'bg-[rgb(var(--color-primary))]/12 text-[rgb(var(--color-primary))]',
        'accent' => 'bg-[rgb(var(--color-accent))]/12 text-[rgb(var(--color-accent))]',
        'secondary' => 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-200',
        'success' => 'bg-emerald-500/12 text-emerald-600 dark:text-emerald-300',
        'warning' => 'bg-amber-500/12 text-amber-600 dark:text-amber-300',
    ];
@endphp

<x-pos.card {{ $attributes }}>
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400 dark:text-slate-500">{{ $label }}</p>
            <p class="mt-3 text-3xl font-black tracking-tight text-slate-900 dark:text-white">{{ $value }}</p>
            @if($hint)
                <p class="mt-3 text-sm app-shell-muted">{{ $hint }}</p>
            @endif
        </div>
        <div class="flex h-14 w-14 items-center justify-center rounded-3xl {{ $tones[$tone] ?? $tones['primary'] }}">
            {{ $slot }}
        </div>
    </div>
</x-pos.card>
