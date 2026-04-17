@props(['title' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-900 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-xl border border-gray-100 dark:border-gray-800']) }}>
    @if($title || isset($header))
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            @if(isset($header))
                {{ $header }}
            @else
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $title }}</h3>
            @endif
        </div>
    @endif

    <div class="px-6 py-4">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800">
            {{ $footer }}
        </div>
    @endif
</div>
