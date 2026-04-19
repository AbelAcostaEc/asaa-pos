<x-pos-layout>
    <x-slot name="header">{{ __('inventory::ui.pos.page_title') }}</x-slot>

    @php
        $cartItems = collect($cart)->values();
        $cartQuantity = $cartItems->sum('quantity');
        $cartTotal = $cartItems->sum('subtotal');
        $productPayload = $products->map(fn ($product) => [
            'id' => $product->id,
            'name' => $product->name,
            'code' => $product->code,
            'category' => optional($product->category)->name ?? __('inventory::ui.fields.uncategorized'),
            'unit' => optional($product->unit)->name ?? __('inventory::ui.fields.no_unit'),
            'price' => (float) $product->price,
            'stock' => (float) $product->stock,
            'image' => $product->image,
        ])->values();
    @endphp

    <div
        x-data="posScreen({ products: {{ Js::from($productPayload) }} })"
        class="space-y-6"
    >
        <x-pos.crud-toolbar
            title="{{ __('inventory::ui.pos.toolbar_title') }}"
            subtitle="{{ __('inventory::ui.pos.toolbar_subtitle') }}"
        >
            <div class="flex flex-wrap gap-3">
                <div class="rounded-2xl border border-slate-200/70 bg-white/80 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-900/70">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-400">{{ __('inventory::ui.pos.items_in_cart') }}</p>
                    <p class="mt-1 text-lg font-black text-slate-900 dark:text-white">{{ number_format((float) $cartQuantity, 0) }}</p>
                </div>
                <div class="rounded-2xl border border-emerald-200/70 bg-emerald-50/80 px-4 py-3 text-sm dark:border-emerald-900/60 dark:bg-emerald-950/30">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-emerald-600 dark:text-emerald-300">{{ __('inventory::ui.pos.current_total') }}</p>
                    <p class="mt-1 text-lg font-black text-emerald-700 dark:text-emerald-200">${{ number_format($cartTotal, 2) }}</p>
                </div>
            </div>
        </x-pos.crud-toolbar>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="space-y-6">
                <x-pos.card>
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div class="space-y-2">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400">{{ __('inventory::ui.pos.product_finder') }}</p>
                            <h3 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">{{ __('inventory::ui.pos.finder_title') }}</h3>
                            <p class="max-w-2xl text-sm leading-7 text-slate-600 dark:text-slate-300">
                                {{ __('inventory::ui.pos.finder_body') }}
                            </p>
                        </div>

                        <div class="w-full lg:max-w-md">
                            <label for="pos-search" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('inventory::ui.pos.search_product') }}</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0Z" />
                                    </svg>
                                </span>
                                <input
                                    id="pos-search"
                                    type="text"
                                    x-model.debounce.200ms="search"
                                    placeholder="{{ __('inventory::ui.pos.search_placeholder') }}"
                                    class="block w-full rounded-2xl border border-slate-200 bg-white py-3 pl-12 pr-4 text-sm text-slate-700 shadow-sm transition focus:border-primary focus:outline-none focus:ring-primary dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                                >
                            </div>
                        </div>
                    </div>
                </x-pos.card>

                <x-pos.card>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400">{{ __('inventory::ui.pos.service_section_title') }}</p>
                            <h3 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">{{ __('inventory::ui.pos.service_section_heading') }}</h3>
                            <p class="max-w-2xl text-sm leading-7 text-slate-600 dark:text-slate-300">
                                {{ __('inventory::ui.pos.service_section_body') }}
                            </p>
                        </div>

                        <form action="{{ route('inventory.pos.addService') }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label for="service_name" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('inventory::ui.pos.service_name') }}</label>
                                <input
                                    id="service_name"
                                    name="service_name"
                                    type="text"
                                    value="{{ old('service_name') }}"
                                    class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-primary focus:outline-none focus:ring-primary dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                                >
                                @error('service_name')
                                    <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="service_description" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('inventory::ui.pos.service_description') }}</label>
                                <textarea
                                    id="service_description"
                                    name="service_description"
                                    rows="3"
                                    class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-primary focus:outline-none focus:ring-primary dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                                >{{ old('service_description') }}</textarea>
                                @error('service_description')
                                    <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label for="service_price" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('inventory::ui.pos.service_price') }}</label>
                                    <input
                                        id="service_price"
                                        name="service_price"
                                        type="number"
                                        step="0.01"
                                        min="0.01"
                                        value="{{ old('service_price') }}"
                                        class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-primary focus:outline-none focus:ring-primary dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                                    >
                                    @error('service_price')
                                        <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="quantity" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('inventory::ui.fields.quantity') }}</label>
                                    <input
                                        id="quantity"
                                        name="quantity"
                                        type="number"
                                        step="1"
                                        min="1"
                                        value="{{ old('quantity', 1) }}"
                                        class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-primary focus:outline-none focus:ring-primary dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                                    >
                                    @error('quantity')
                                        <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <x-pos.button type="submit" variant="secondary" class="w-full justify-center">
                                {{ __('inventory::ui.pos.add_service') }}
                            </x-pos.button>
                        </form>
                    </div>
                </x-pos.card>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    @foreach($products as $product)
                        <article
                            x-show="matchesProduct({
                                name: @js($product->name),
                                code: @js($product->code),
                                category: @js(optional($product->category)->name ?? __('inventory::ui.fields.uncategorized'))
                            })"
                            x-transition.opacity.duration.150ms
                            class="group"
                        >
                            <x-pos.card class="h-full overflow-hidden border border-slate-200/70 bg-white/95 dark:border-slate-800/80 dark:bg-slate-950/60">
                                <div class="space-y-5">
                                    @if($product->image)
                                        <img
                                            src="{{ $product->image }}"
                                            alt="{{ $product->name }}"
                                            class="h-44 w-full rounded-2xl object-cover"
                                        >
                                    @else
                                        <div class="flex h-44 items-center justify-center rounded-2xl bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.18),_transparent_55%),linear-gradient(135deg,rgba(15,23,42,0.98),rgba(30,41,59,0.94))] px-6 text-center">
                                            <div class="space-y-2">
                                                <span class="inline-flex rounded-full border border-white/10 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-emerald-100">
                                                    {{ optional($product->category)->name ?? __('inventory::ui.fields.uncategorized') }}
                                                </span>
                                                <p class="text-lg font-bold text-white">{{ $product->name }}</p>
                                                <p class="text-sm text-slate-300">{{ __('inventory::ui.fields.code') }}: {{ $product->code }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex items-start justify-between gap-4">
                                        <div class="space-y-2">
                                            <h4 class="text-xl font-black tracking-tight text-slate-900 dark:text-white">{{ $product->name }}</h4>
                                            <div class="flex flex-wrap gap-2">
                                                <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                                    {{ $product->code }}
                                                </span>
                                                <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                                    {{ optional($product->category)->name ?? __('inventory::ui.fields.uncategorized') }}
                                                </span>
                                            </div>
                                        </div>
                                        <span class="rounded-full bg-primary/10 px-4 py-2 text-sm font-bold text-primary">
                                            ${{ number_format((float) $product->price, 2) }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-900/80">
                                            <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-400">{{ __('inventory::ui.fields.stock') }}</p>
                                            <p class="mt-2 text-lg font-black text-slate-900 dark:text-white">{{ number_format((float) $product->stock, 2) }}</p>
                                        </div>
                                        <div class="rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-900/80">
                                            <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-400">{{ __('inventory::ui.fields.unit') }}</p>
                                            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">{{ optional($product->unit)->name ?? __('inventory::ui.fields.no_unit') }}</p>
                                        </div>
                                    </div>

                                    <form action="{{ route('inventory.pos.addProduct') }}" method="POST" class="grid gap-3 sm:grid-cols-[110px_1fr]">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <div>
                                            <label for="quantity_{{ $product->id }}" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('inventory::ui.fields.quantity') }}</label>
                                            <input
                                                id="quantity_{{ $product->id }}"
                                                type="number"
                                                name="quantity"
                                                value="1"
                                                min="1"
                                                max="{{ max(1, (int) ceil($product->stock)) }}"
                                                step="1"
                                                class="block w-full rounded-2xl border border-slate-200 bg-white px-3 py-3 text-sm text-slate-700 shadow-sm transition focus:border-primary focus:outline-none focus:ring-primary dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                                            >
                                        </div>
                                        <div class="flex items-end">
                                            <x-pos.button type="submit" variant="secondary" class="w-full justify-center">
                                                {{ __('inventory::ui.pos.add_to_cart') }}
                                            </x-pos.button>
                                        </div>
                                    </form>
                                </div>
                            </x-pos.card>
                        </article>
                    @endforeach
                </div>

                <x-pos.card x-show="filteredCount === 0" x-cloak>
                    <div class="py-8 text-center">
                        <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('inventory::ui.pos.no_products_found') }}</p>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ __('inventory::ui.pos.no_products_found_body') }}</p>
                    </div>
                </x-pos.card>
            </div>

            <div class="space-y-6">
                <x-pos.card>
                    <div class="space-y-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400">{{ __('inventory::ui.pos.current_cart') }}</p>
                                <h3 class="mt-2 text-2xl font-black tracking-tight text-slate-900 dark:text-white">{{ __('inventory::ui.pos.cart_title') }}</h3>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                {{ __('inventory::ui.pos.products_count', ['count' => $cartItems->count()]) }}
                            </span>
                        </div>

                        @if ($errors->has('cart'))
                            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/60 dark:bg-red-950/30 dark:text-red-300">
                                {{ $errors->first('cart') }}
                            </div>
                        @endif

                        <div class="space-y-3">
                            @forelse($cartItems as $cartKey => $item)
                                <div class="rounded-2xl border border-slate-200/70 bg-slate-50/80 p-4 dark:border-slate-700 dark:bg-slate-900/70">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="space-y-1">
                                            <p class="text-base font-bold text-slate-900 dark:text-white">{{ $item['name'] ?? __('inventory::ui.products.page_title') }}</p>
                                            @if(!empty($item['description']))
                                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $item['description'] }}</p>
                                            @endif
                                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                                {{ __('inventory::ui.pos.quantity_price', ['quantity' => number_format((float) $item['quantity'], 0), 'price' => '$' . number_format((float) $item['price'], 2)]) }}
                                            </p>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-base font-black text-slate-900 dark:text-white">${{ number_format((float) $item['subtotal'], 2) }}</p>
                                            <form action="{{ route('inventory.pos.removeProduct') }}" method="POST" class="mt-2">
                                                @csrf
                                                <input type="hidden" name="cart_key" value="{{ $cartKey }}">
                                                <button type="submit" class="text-sm font-semibold text-red-600 transition hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                                    {{ __('inventory::ui.pos.remove') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-10 text-center dark:border-slate-700">
                                    <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('inventory::ui.pos.cart_empty') }}</p>
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ __('inventory::ui.pos.cart_empty_body') }}</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="rounded-3xl bg-slate-950 px-5 py-5 text-white dark:bg-slate-900">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-300">{{ __('inventory::ui.pos.total_to_charge') }}</span>
                                <span class="text-3xl font-black">${{ number_format($cartTotal, 2) }}</span>
                            </div>
                        </div>

                        <form action="{{ route('inventory.pos.checkout') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="customer_mode" x-model="customerMode">

                            <div class="space-y-3">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('inventory::ui.pos.customer_data') }}</label>
                                    <div class="grid gap-2 sm:grid-cols-3">
                                        <button
                                            type="button"
                                            @click="customerMode = 'final'"
                                            :class="customerMode === 'final' ? 'border-primary bg-primary/10 text-primary' : 'border-slate-200 bg-white text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300'"
                                            class="rounded-2xl border px-4 py-3 text-sm font-semibold transition"
                                        >
                                            {{ __('inventory::ui.pos.final_consumer') }}
                                        </button>
                                        <button
                                            type="button"
                                            @click="customerMode = 'existing'"
                                            :class="customerMode === 'existing' ? 'border-primary bg-primary/10 text-primary' : 'border-slate-200 bg-white text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300'"
                                            class="rounded-2xl border px-4 py-3 text-sm font-semibold transition"
                                        >
                                            {{ __('inventory::ui.pos.existing_customer') }}
                                        </button>
                                        <button
                                            type="button"
                                            @click="customerMode = 'new'"
                                            :class="customerMode === 'new' ? 'border-primary bg-primary/10 text-primary' : 'border-slate-200 bg-white text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300'"
                                            class="rounded-2xl border px-4 py-3 text-sm font-semibold transition"
                                        >
                                            {{ __('inventory::ui.pos.new_customer') }}
                                        </button>
                                    </div>
                                </div>

                                <div x-show="customerMode === 'existing'" x-cloak>
                                    <label for="customer_id" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('inventory::ui.pos.select_customer') }}</label>
                                    <select
                                        name="customer_id"
                                        id="customer_id"
                                        class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-primary focus:outline-none focus:ring-primary dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                                    >
                                        <option value="">{{ __('inventory::ui.pos.select_customer') }}</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" @selected((string) old('customer_id') === (string) $customer->id)>
                                                {{ $customer->name }}@if($customer->phone) - {{ $customer->phone }}@endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div x-show="customerMode === 'new'" x-cloak class="grid gap-4 md:grid-cols-2">
                                    <div class="md:col-span-2">
                                        <label for="customer_name" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('inventory::ui.pos.customer_name') }}</label>
                                        <input
                                            id="customer_name"
                                            type="text"
                                            name="customer_name"
                                            value="{{ old('customer_name') }}"
                                            class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-primary focus:outline-none focus:ring-primary dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                                        >
                                        @error('customer_name')
                                            <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="customer_phone" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('inventory::ui.fields.phone') }}</label>
                                        <input
                                            id="customer_phone"
                                            type="text"
                                            name="customer_phone"
                                            value="{{ old('customer_phone') }}"
                                            class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-primary focus:outline-none focus:ring-primary dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                                        >
                                        @error('customer_phone')
                                            <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="customer_email" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('inventory::ui.fields.email') }}</label>
                                        <input
                                            id="customer_email"
                                            type="email"
                                            name="customer_email"
                                            value="{{ old('customer_email') }}"
                                            class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-primary focus:outline-none focus:ring-primary dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                                        >
                                        @error('customer_email')
                                            <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="customer_address" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('inventory::ui.fields.address') }}</label>
                                        <textarea
                                            id="customer_address"
                                            name="customer_address"
                                            rows="3"
                                            class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-primary focus:outline-none focus:ring-primary dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                                        >{{ old('customer_address') }}</textarea>
                                        @error('customer_address')
                                            <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div x-show="customerMode === 'final'" x-cloak class="rounded-2xl border border-slate-200/70 bg-slate-50/80 px-4 py-3 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-300">
                                    {{ __('inventory::ui.pos.customer_final_note') }}
                                </div>
                            </div>

                            <x-pos.button type="submit" variant="primary" class="w-full justify-center" :disabled="count($cart) === 0">
                                {{ __('inventory::ui.pos.confirm_sale') }}
                            </x-pos.button>
                        </form>
                    </div>
                </x-pos.card>
            </div>
        </section>
    </div>

    <script>
        function posScreen({ products }) {
            return {
                products,
                search: '',
                customerMode: @js(old('customer_mode', old('customer_name') ? 'new' : (old('customer_id') ? 'existing' : 'final'))),
                filteredCount: products.length,
                normalize(value) {
                    return String(value ?? '')
                        .toLowerCase()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '');
                },
                matchesProduct(product) {
                    const term = this.normalize(this.search).trim();

                    if (!term) {
                        return true;
                    }

                    const haystack = [
                        product.name,
                        product.code,
                        product.category,
                    ].map(value => this.normalize(value)).join(' ');

                    return haystack.includes(term);
                },
                refreshFilteredCount() {
                    const term = this.normalize(this.search).trim();

                    if (!term) {
                        this.filteredCount = this.products.length;
                        return;
                    }

                    this.filteredCount = this.products.filter((product) => {
                        const haystack = [
                            product.name,
                            product.code,
                            product.category,
                        ].map(value => this.normalize(value)).join(' ');

                        return haystack.includes(term);
                    }).length;
                },
                init() {
                    this.refreshFilteredCount();
                    this.$watch('search', () => this.refreshFilteredCount());
                },
            };
        }
    </script>
</x-pos-layout>
