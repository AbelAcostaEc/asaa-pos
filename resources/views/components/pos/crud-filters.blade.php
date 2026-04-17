@props([
    'searchModel' => 'filters.search',
    'perPageModel' => 'filters.per_page',
    'applyAction' => 'applyFilters()',
    'searchPlaceholder' => null,
    'showLabel' => null,
    'recordsLabel' => null,
    'perPageOptions' => [5, 10, 20, 30, 50, 100],
])

<div class="flex flex-wrap items-center gap-3">
    <div class="relative flex-1 min-w-[200px] max-w-md">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </span>
        <input type="text" x-model="{{ $searchModel }}" @keydown.enter="{{ $applyAction }}"
            placeholder="{{ $searchPlaceholder ?? __('common.search') }}"
            class="w-full pl-9 pr-9 py-2.5 text-sm rounded-xl
                   border border-gray-200 dark:border-gray-700
                   bg-gray-50 dark:bg-gray-900
                   text-gray-700 dark:text-gray-300
                   placeholder-gray-400
                   focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                   transition-all">
        {{-- Clear button --}}
        <button x-show="{{ $searchModel }}" @click="{{ $searchModel }} = ''; {{ $applyAction }}"
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
            style="display:none;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- ── Per-Page Selector ────────────────────────── --}}
    <div
        class="flex items-center gap-2 px-3 py-2.5 rounded-xl
                border border-gray-200 dark:border-gray-700
                bg-gray-50 dark:bg-gray-900 shadow-sm shrink-0">
        <span class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $showLabel ?? __('common.show') }}</span>
        <select x-model="{{ $perPageModel }}" @change="{{ $applyAction }}"
            class="text-sm font-medium bg-white dark:bg-gray-800
                   border border-gray-200 dark:border-gray-700
                   rounded-lg px-2 py-1
                   focus:outline-none focus:ring-2 focus:ring-primary/30
                   cursor-pointer">
            @foreach ($perPageOptions as $opt)
                <option value="{{ $opt }}">{{ $opt }}</option>
            @endforeach
        </select>
        <span class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $recordsLabel ?? __('common.records') }}</span>
    </div>
</div>
