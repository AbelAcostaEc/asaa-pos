@props(['title' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'app-shell-panel overflow-hidden rounded-[28px] transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_24px_60px_-28px_rgba(15,23,42,0.45)]']) }}>
    @if($title || isset($header))
        <div class="border-b border-slate-200/70 px-6 py-5 dark:border-slate-800/80">
            @if(isset($header))
                {{ $header }}
            @else
                <h3 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">{{ $title }}</h3>
            @endif
        </div>
    @endif

    <div class="px-6 py-5">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="border-t border-slate-200/70 bg-slate-50/70 px-6 py-4 dark:border-slate-800/80 dark:bg-slate-900/40">
            {{ $footer }}
        </div>
    @endif
</div>
