<x-pos-layout>
    <x-slot name="header">{{ __('inventory::ui.entries.page_title') }}</x-slot>

    <div x-data="{ createSupplier: @js(old('create_supplier')) }" class="space-y-6">
        <x-pos.crud-toolbar
            title="{{ __('inventory::ui.entries.list_title') }}"
            subtitle="{{ __('inventory::ui.entries.list_subtitle') }}">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('inventory.products.index') }}">
                    <x-pos.button variant="secondary">
                        {{ __('inventory::ui.entries.view_products') }}
                    </x-pos.button>
                </a>
                <a href="{{ route('inventory.reports.purchases') }}">
                    <x-pos.button variant="outline">
                        {{ __('inventory::ui.entries.purchase_report') }}
                    </x-pos.button>
                </a>
                <a href="{{ route('inventory.reports.sales') }}">
                    <x-pos.button variant="outline">
                        {{ __('inventory::ui.entries.sales_report') }}
                    </x-pos.button>
                </a>
            </div>
        </x-pos.crud-toolbar>

        @if (session('success'))
            <div class="rounded-[24px] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-[24px] border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-300">
                <p class="font-semibold">{{ __('inventory::ui.entries.review_required') }}</p>
                <ul class="mt-2 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)]">
            <x-pos.card>
                <x-slot name="header">
                    <div>
                        <h3 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">{{ __('inventory::ui.entries.register_title') }}</h3>
                        <p class="mt-1 text-sm app-shell-muted">{{ __('inventory::ui.entries.register_body') }}</p>
                    </div>
                </x-slot>

                <form action="{{ route('inventory.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="product_id" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('inventory::ui.fields.name') }}</label>
                            <select id="product_id" name="product_id" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" required>
                                <option value="">{{ __('inventory::ui.entries.select_product') }}</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                        {{ __('inventory::ui.entries.product_option', ['name' => $product->name, 'code' => $product->code, 'stock' => $product->stock]) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2 rounded-[24px] border border-slate-200/80 bg-slate-50/80 p-4 dark:border-slate-800 dark:bg-slate-900/50">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('inventory::ui.entries.supplier_title') }}</h4>
                                    <p class="text-sm app-shell-muted">{{ __('inventory::ui.entries.supplier_body') }}</p>
                                </div>
                                <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                    <input type="checkbox" name="create_supplier" value="1" x-model="createSupplier" class="rounded border-slate-300 text-primary focus:ring-primary">
                                    {{ __('inventory::ui.entries.create_supplier_now') }}
                                </label>
                            </div>

                            <div class="mt-4" x-show="!createSupplier">
                                <label for="supplier_id" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('inventory::ui.entries.existing_supplier') }}</label>
                                <select id="supplier_id" name="supplier_id" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" :required="!createSupplier">
                                    <option value="">{{ __('inventory::ui.entries.select_supplier') }}</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-4 grid gap-4 md:grid-cols-2" x-show="createSupplier" x-cloak>
                                <div class="md:col-span-2">
                                    <x-pos.input label="{{ __('inventory::ui.entries.supplier_name') }}" name="supplier_name" value="{{ old('supplier_name') }}" x-bind:required="createSupplier" />
                                </div>
                                <div>
                                    <x-pos.input label="{{ __('inventory::ui.fields.phone') }}" name="supplier_phone" value="{{ old('supplier_phone') }}" />
                                </div>
                                <div>
                                    <x-pos.input label="{{ __('inventory::ui.fields.email') }}" name="supplier_email" type="email" value="{{ old('supplier_email') }}" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-pos.input label="{{ __('inventory::ui.fields.address') }}" name="supplier_address" value="{{ old('supplier_address') }}" />
                                </div>
                            </div>
                        </div>

                        <div>
                            <x-pos.input label="{{ __('inventory::ui.fields.quantity') }}" name="quantity" type="number" step="0.01" min="0.01" value="{{ old('quantity') }}" required />
                        </div>
                        <div>
                            <x-pos.input label="{{ __('inventory::ui.fields.cost_price') }}" name="cost_price" type="number" step="0.01" min="0" value="{{ old('cost_price') }}" required />
                        </div>
                        <div>
                            <x-pos.input label="{{ __('inventory::ui.fields.purchase_date') }}" name="purchase_date" type="date" value="{{ old('purchase_date', now()->toDateString()) }}" required />
                        </div>
                        <div>
                            <x-pos.input label="{{ __('inventory::ui.fields.invoice_number') }}" name="invoice_number" value="{{ old('invoice_number') }}" />
                        </div>
                        <div>
                            <x-pos.input label="{{ __('inventory::ui.fields.batch_code') }}" name="batch_code" value="{{ old('batch_code') }}" />
                        </div>
                        <div class="md:col-span-2">
                            <label for="observations" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('inventory::ui.fields.observations') }}</label>
                            <textarea id="observations" name="observations" rows="3" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">{{ old('observations') }}</textarea>
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-end gap-3 border-t border-slate-200/70 pt-5 dark:border-slate-800/80">
                        <a href="{{ route('inventory.products.index') }}">
                            <x-pos.button variant="outline" type="button">{{ __('common.cancel') }}</x-pos.button>
                        </a>
                        <x-pos.button type="submit" variant="primary">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('inventory::ui.entries.register_entry') }}
                        </x-pos.button>
                    </div>
                </form>
            </x-pos.card>

            <div class="space-y-6">
                <x-pos.card>
                    <x-slot name="header">
                        <div>
                            <h3 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">{{ __('inventory::ui.entries.how_it_works') }}</h3>
                            <p class="mt-1 text-sm app-shell-muted">{{ __('inventory::ui.entries.how_it_works_body') }}</p>
                        </div>
                    </x-slot>

                    <div class="space-y-4 text-sm text-slate-600 dark:text-slate-300">
                        <div class="rounded-2xl bg-slate-50 px-4 py-4 dark:bg-slate-900/60">
                            {{ __('inventory::ui.entries.step_1') }}
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-4 py-4 dark:bg-slate-900/60">
                            {{ __('inventory::ui.entries.step_2') }}
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-4 py-4 dark:bg-slate-900/60">
                            {{ __('inventory::ui.entries.step_3') }}
                        </div>
                    </div>
                </x-pos.card>

                <x-pos.card>
                    <x-slot name="header">
                        <div>
                            <h3 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">{{ __('inventory::ui.entries.recent_entries') }}</h3>
                            <p class="mt-1 text-sm app-shell-muted">{{ __('inventory::ui.entries.recent_entries_body') }}</p>
                        </div>
                    </x-slot>

                    <div class="space-y-3">
                        @forelse ($entries as $entry)
                            <div class="rounded-[22px] border border-slate-200/80 bg-white/70 p-4 dark:border-slate-800 dark:bg-slate-900/60">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                            {{ optional($entry->supplier)->name ?? __('inventory::ui.entries.without_supplier') }}
                                        </p>
                                        <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-400">{{ __('inventory::ui.entries.invoice_label', ['number' => $entry->invoice_number ?: __('inventory::ui.entries.not_available')]) }}</p>
                                    </div>
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                                        {{ $entry->date }}
                                    </span>
                                </div>

                                <div class="mt-3 space-y-2">
                                    @foreach ($entry->details as $detail)
                                        <div class="flex items-center justify-between gap-4 rounded-2xl bg-slate-50 px-3 py-2 text-sm dark:bg-slate-950/60">
                                            <div>
                                                <p class="font-medium text-slate-800 dark:text-slate-100">{{ optional($detail->product)->name ?? __('inventory::ui.entries.detail_product_fallback') }}</p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('inventory::ui.entries.batch', ['code' => optional($detail->batch)->code ?? __('inventory::ui.entries.not_available')]) }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold text-slate-900 dark:text-white">{{ $detail->quantity }} {{ __('inventory::ui.entries.units_suffix') }}</p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">${{ number_format((float) $detail->subtotal, 2) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-sm text-slate-500 dark:text-slate-400">{{ __('inventory::ui.entries.empty') }}</p>
                        @endforelse
                    </div>
                </x-pos.card>
            </div>
        </div>
    </div>
</x-pos-layout>
