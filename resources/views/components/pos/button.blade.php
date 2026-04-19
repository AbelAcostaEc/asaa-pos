@props([
    'variant' => 'primary',
    'type' => 'button',
    'size' => 'md',
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed rounded-xl active:scale-[0.98]';
    
    $variants = [
        'primary' => 'bg-primary text-white hover:bg-primary/90 focus:ring-primary shadow-sm shadow-[0_12px_30px_-16px_rgba(0,0,0,0.45)]',
        'secondary' => 'border border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50 focus:ring-slate-300 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-slate-600 dark:hover:bg-slate-800',
        'accent' => 'bg-accent text-white hover:bg-accent/90 focus:ring-accent shadow-sm',
        'outline' => 'border border-slate-200 bg-transparent text-slate-700 hover:bg-slate-50 focus:ring-slate-300 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800',
        'danger' => 'border border-red-200 bg-red-50 text-red-700 hover:bg-red-100 focus:ring-red-300 shadow-sm dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-300 dark:hover:bg-red-950/70',
        'ghost' => 'bg-transparent hover:bg-gray-100 text-gray-700 dark:text-gray-300 dark:hover:bg-gray-800',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        'xl' => 'px-8 py-4 text-lg',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
    {{ $slot }}
</button>
