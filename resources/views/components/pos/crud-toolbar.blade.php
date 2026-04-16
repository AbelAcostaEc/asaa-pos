@props([
    'title'    => 'Lista',
    'subtitle' => null,
])

<div class="flex items-center justify-between">
    <div>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $title }}</h2>
        @if($subtitle)
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
        @endif
    </div>

    {{-- Slot: primary action button --}}
    @if($slot->isNotEmpty())
        <div>{{ $slot }}</div>
    @endif
</div>
