<x-pos-layout>
    <x-slot name="header">{{ __('inventory::ui.products.catalog_page_title') }}</x-slot>

    <div class="space-y-8">
        @php
            $productCount = $products->count();
            $stockUnits = $products->sum('stock');
            $inventoryValue = $products->sum(fn ($product) => (float) $product->price * (float) $product->stock);
        @endphp

        <x-pos.crud-toolbar
            title="{{ __('inventory::ui.products.catalog_title') }}"
            subtitle="{{ __('inventory::ui.products.catalog_subtitle') }}">
            <a href="{{ route('inventory.products.index') }}">
                <x-pos.button variant="secondary">
                    {{ __('inventory::ui.products.back_to_products') }}
                </x-pos.button>
            </a>
        </x-pos.crud-toolbar>

        <section class="grid grid-cols-1 gap-4 lg:grid-cols-12">
            <x-pos.card class="lg:col-span-7">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl space-y-3">
                        <span class="inline-flex w-fit rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-primary">
                            {{ __('inventory::ui.products.catalog_badge') }}
                        </span>
                        <div class="space-y-2">
                            <h3 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                                {{ __('inventory::ui.products.catalog_headline') }}
                            </h3>
                            <p class="max-w-xl text-sm leading-7 text-slate-600 dark:text-slate-300">
                                {{ __('inventory::ui.products.catalog_description') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:min-w-[340px]">
                        <div class="rounded-2xl border border-slate-200/70 bg-slate-50/80 p-4 dark:border-slate-700/70 dark:bg-slate-900/70">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ __('inventory::ui.products.catalog_items') }}</p>
                            <p class="mt-3 text-2xl font-black text-slate-900 dark:text-white">{{ $productCount }}</p>
                        </div>
                        <div class="rounded-2xl border border-emerald-200/70 bg-emerald-50/80 p-4 dark:border-emerald-900/60 dark:bg-emerald-950/30">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-600 dark:text-emerald-300">{{ __('inventory::ui.fields.stock') }}</p>
                            <p class="mt-3 text-2xl font-black text-emerald-700 dark:text-emerald-200">{{ number_format((float) $stockUnits, 0) }}</p>
                        </div>
                        <div class="col-span-2 rounded-2xl border border-amber-200/70 bg-amber-50/80 p-4 sm:col-span-1 dark:border-amber-900/60 dark:bg-amber-950/30">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-amber-600 dark:text-amber-300">{{ __('inventory::ui.products.catalog_value') }}</p>
                            <p class="mt-3 text-xl font-black text-amber-700 dark:text-amber-200">${{ number_format($inventoryValue, 2) }}</p>
                        </div>
                    </div>
                </div>
            </x-pos.card>

            <x-pos.card class="lg:col-span-5">
                <div class="space-y-5">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">{{ __('inventory::ui.products.catalog_reading_guide') }}</p>
                        <h3 class="text-xl font-black tracking-tight text-slate-900 dark:text-white">{{ __('inventory::ui.products.catalog_comfort_title') }}</h3>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200/70 p-4 dark:border-slate-700/70">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('inventory::ui.products.catalog_highlight_price') }}</p>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('inventory::ui.products.catalog_highlight_price_body') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200/70 p-4 dark:border-slate-700/70">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('inventory::ui.products.catalog_compact_metadata') }}</p>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('inventory::ui.products.catalog_compact_metadata_body') }}</p>
                        </div>
                    </div>
                </div>
            </x-pos.card>
        </section>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 2xl:grid-cols-3">
            @forelse($products as $product)
                <x-pos.card class="overflow-hidden border border-slate-200/60 bg-white/95 dark:border-slate-800/80 dark:bg-slate-950/60">
                    @if($product->image)
                        <img
                            src="{{ $product->image }}"
                            alt="{{ $product->name }}"
                            class="h-56 w-full object-cover"
                        >
                    @else
                        <div class="flex h-56 items-center justify-center bg-[radial-gradient(circle_at_top,_rgba(45,212,191,0.18),_transparent_55%),linear-gradient(135deg,rgba(15,23,42,0.98),rgba(30,41,59,0.94))] px-6 text-center">
                            <div class="space-y-2">
                                <span class="inline-flex rounded-full border border-white/10 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-teal-100">
                                    {{ __('inventory::ui.products.catalog_product_card') }}
                                </span>
                                <p class="text-lg font-bold text-white">{{ $product->name }}</p>
                                <p class="text-sm text-slate-300">{{ optional($product->category)->name ?? __('inventory::ui.fields.uncategorized') }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="space-y-2">
                                <h3 class="text-xl font-black tracking-tight text-slate-900 dark:text-white">{{ $product->name }}</h3>
                                <p class="text-sm leading-6 text-slate-500 dark:text-slate-400">
                                    {{ __('inventory::ui.products.catalog_product_blurb') }}
                                </p>
                            </div>
                            <span class="shrink-0 rounded-full bg-primary/10 px-4 py-2 text-sm font-bold text-primary">
                                ${{ number_format((float) $product->price, 2) }}
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                {{ __('inventory::ui.fields.code') }}: {{ $product->code }}
                            </span>
                            <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                {{ optional($product->category)->name ?? __('inventory::ui.fields.uncategorized') }}
                            </span>
                            <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                {{ __('inventory::ui.fields.unit') }}: {{ optional($product->unit)->name ?? __('inventory::ui.fields.no_unit') }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-900/80">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ __('inventory::ui.fields.stock') }}</p>
                                <p class="mt-2 text-lg font-bold text-slate-900 dark:text-white">{{ number_format((float) $product->stock, 2) }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-900/80">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ __('inventory::ui.products.catalog_total_value') }}</p>
                                <p class="mt-2 text-lg font-bold text-slate-900 dark:text-white">
                                    ${{ number_format((float) $product->price * (float) $product->stock, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </x-pos.card>
            @empty
                <x-pos.card class="md:col-span-2 2xl:col-span-3">
                    <div class="py-8 text-center">
                        <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('inventory::ui.products.catalog_empty') }}</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ __('inventory::ui.products.catalog_subtitle') }}</p>
                    </div>
                </x-pos.card>
            @endforelse
        </div>
    </div>
</x-pos-layout>
