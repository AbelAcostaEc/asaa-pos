<x-pos-layout>
    <x-slot name="header">{{ __('inventory::ui.products.page_title') }}</x-slot>

    <div x-data="productCrud()" class="space-y-6">

        {{-- ── Toolbar ─────────────────────────────────────────────── --}}
        <x-pos.crud-toolbar
            title="{{ __('inventory::ui.products.list_title') }}"
            subtitle="{{ __('inventory::ui.products.list_subtitle') }}">
            <x-pos.button @click="openCreateModal()" variant="primary">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('inventory::ui.products.new') }}
            </x-pos.button>
            <a href="{{ route('inventory.products.catalog') }}" class="ml-2">
                <x-pos.button variant="secondary">
                    {{ __('inventory::ui.products.view_catalog') }}
                </x-pos.button>
            </a>
            <a href="{{ route('inventory.index') }}" class="ml-2">
                <x-pos.button variant="secondary">
                    {{ __('inventory::ui.entries.page_title') }}
                </x-pos.button>
            </a>
        </x-pos.crud-toolbar>

        {{-- ── Filtros ──────────────────────────────────────────────── --}}
        <x-pos.crud-filters search-placeholder="{{ __('inventory::ui.products.search') }}" />

        {{-- ═══════════════════════════════════════════════════════════
             DESKTOP (lg+): Tabla
        ═══════════════════════════════════════════════════════════ --}}
        <div class="hidden lg:block">
            <x-pos.card>
                <x-pos.table :headers="[__('inventory::ui.fields.id'), __('inventory::ui.fields.name'), __('inventory::ui.fields.code'), __('inventory::ui.fields.category'), __('inventory::ui.fields.unit'), __('inventory::ui.fields.price'), __('inventory::ui.fields.stock'), __('inventory::ui.fields.actions')]">
                    @forelse($products as $product)
                        <tr class="transition-colors hover:bg-gray-50/50 dark:hover:bg-gray-800/50">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                #{{ $product->id }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $product->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $product->code }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $product->category->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $product->unit->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">${{ $product->price }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $product->stock }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                <x-pos.button @click="openEditModal({{ $product->id }})" variant="secondary" size="sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.586-9.414a2 2 0 112.828 2.828L12 15l-4 1 1-4 8.414-8.414z" />
                                    </svg>
                                    {{ __('common.edit') }}
                                </x-pos.button>
                                <form action="{{ route('inventory.products.destroy', $product) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <x-pos.button type="submit" variant="danger" size="sm">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8" />
                                        </svg>
                                        {{ __('common.delete') }}
                                    </x-pos.button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                {{ __('inventory::ui.products.no_results') }}
                            </td>
                        </tr>
                    @endforelse
                </x-pos.table>
            </x-pos.card>
        </div>

        {{-- ═══════════════════════════════════════════════════════════
             MOBILE (lg-): Cards
        ═══════════════════════════════════════════════════════════ --}}
        <div class="block lg:hidden space-y-4">
            @forelse($products as $product)
                <x-pos.card>
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->code }} - ${{ $product->price }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('inventory::ui.fields.stock') }}: {{ $product->stock }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <x-pos.button @click="openEditModal({{ $product->id }})" variant="secondary" size="sm">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.586-9.414a2 2 0 112.828 2.828L12 15l-4 1 1-4 8.414-8.414z" />
                                </svg>
                                {{ __('common.edit') }}
                            </x-pos.button>
                            <form action="{{ route('inventory.products.destroy', $product) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <x-pos.button type="submit" variant="danger" size="sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8" />
                                    </svg>
                                    {{ __('common.delete') }}
                                </x-pos.button>
                            </form>
                        </div>
                    </div>
                </x-pos.card>
            @empty
                <x-pos.card>
                    <p class="text-center text-gray-500 dark:text-gray-400">{{ __('inventory::ui.products.no_results') }}</p>
                </x-pos.card>
            @endforelse
        </div>

        {{-- ── Modal ────────────────────────────────────────────────── --}}
        <x-pos.modal name="product-modal" focusable>
            <div class="p-6">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white" x-text="editingId ? @js(__('inventory::ui.products.modal_edit')) : @js(__('inventory::ui.products.modal_create'))"></h2>
                    <button type="button" @click="$dispatch('close-modal', 'product-modal')" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm()" class="space-y-4">
                    <div>
                        <x-pos.input label="{{ __('inventory::ui.fields.name') }}" name="name" x-model="formData.name" required />
                        <template x-if="errors.name">
                            <p class="mt-1 text-sm text-danger" x-text="errors.name[0]"></p>
                        </template>
                    </div>

                    <div>
                        <x-pos.input label="{{ __('inventory::ui.fields.code') }}" name="code" x-model="formData.code" required />
                        <template x-if="errors.code">
                            <p class="mt-1 text-sm text-danger" x-text="errors.code[0]"></p>
                        </template>
                    </div>

                    <div>
                        <x-pos.select label="{{ __('inventory::ui.fields.category') }}" name="category_id" x-model="formData.category_id" :options="$categories" required />
                        <template x-if="errors.category_id">
                            <p class="mt-1 text-sm text-danger" x-text="errors.category_id[0]"></p>
                        </template>
                    </div>

                    <div>
                        <x-pos.select label="{{ __('inventory::ui.fields.unit') }}" name="unit_id" x-model="formData.unit_id" :options="$units" required />
                        <template x-if="errors.unit_id">
                            <p class="mt-1 text-sm text-danger" x-text="errors.unit_id[0]"></p>
                        </template>
                    </div>

                    <div>
                        <x-pos.input label="{{ __('inventory::ui.fields.price') }}" name="price" type="number" step="0.01" x-model="formData.price" required />
                        <template x-if="errors.price">
                            <p class="mt-1 text-sm text-danger" x-text="errors.price[0]"></p>
                        </template>
                    </div>

                    <div>
                        <x-pos.input label="{{ __('inventory::ui.fields.image_url') }}" name="image" x-model="formData.image" />
                        <template x-if="errors.image">
                            <p class="mt-1 text-sm text-danger" x-text="errors.image[0]"></p>
                        </template>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-300">
                        {!! __('inventory::ui.products.stock_managed_notice', ['link' => '<a href="' . route('inventory.index') . '" class="font-semibold text-primary hover:underline">' . e(__('inventory::ui.entries.page_title')) . '</a>']) !!}
                    </div>

                    <div class="flex justify-end gap-3 pt-6">
                        <x-pos.button variant="outline" @click="$dispatch('close-modal', 'product-modal')" x-bind:disabled="loading">
                            {{ __('common.cancel') }}
                        </x-pos.button>
                        <x-pos.button type="submit" variant="primary" x-bind:disabled="loading">
                            <span x-show="!loading" x-text="editingId ? @js(__('common.update')) : @js(__('common.save'))"></span>
                            <span x-show="loading" class="flex items-center">
                                <svg class="-ml-1 mr-3 h-5 w-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ __('common.processing') }}
                            </span>
                        </x-pos.button>
                    </div>
                </form>
            </div>
        </x-pos.modal>

    </div>

    <script>
        function productCrud() {
            return {
                loading: false,
                editingId: null,
                errors: {},
                categories: @json($categories),
                units: @json($units),
                filters: {
                    search: '',
                    per_page: 10,
                },
                formData: {},
                init() {
                    this.resetFormData();
                },
                getSingleOptionValue(options) {
                    const values = Object.keys(options ?? {});
                    return values.length === 1 ? String(values[0]) : '';
                },
                buildEmptyFormData() {
                    return {
                        name: '',
                        code: '',
                        category_id: this.getSingleOptionValue(this.categories),
                        unit_id: this.getSingleOptionValue(this.units),
                        price: '',
                        image: ''
                    };
                },
                normalizeFormData(data) {
                    return {
                        ...this.buildEmptyFormData(),
                        ...data,
                        category_id: data.category_id ? String(data.category_id) : this.getSingleOptionValue(this.categories),
                        unit_id: data.unit_id ? String(data.unit_id) : this.getSingleOptionValue(this.units),
                        price: data.price ?? '',
                        image: data.image ?? ''
                    };
                },
                resetFormData() {
                    this.formData = this.buildEmptyFormData();
                },
                applyFilters() {
                    // Placeholder for filtering logic
                },
                openCreateModal() {
                    this.editingId = null;
                    this.errors = {};
                    this.resetFormData();
                    this.$dispatch('open-modal', 'product-modal');
                },
                async openEditModal(id) {
                    this.editingId = id;
                    this.errors = {};
                    try {
                        const response = await fetch(`{{ url('inventory/products') }}/${id}/edit`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        this.formData = this.normalizeFormData({
                            name: data.product.name,
                            code: data.product.code,
                            category_id: data.product.category_id,
                            unit_id: data.product.unit_id,
                            price: data.product.price,
                            image: data.product.image
                        });
                        this.$dispatch('open-modal', 'product-modal');
                    } catch (error) {
                        console.error('Error fetching product:', error);
                    }
                },
                async submitForm() {
                    this.loading = true;
                    this.errors = {};
                    try {
                        this.formData = this.normalizeFormData(this.formData);
                        const url = this.editingId ? `{{ url('inventory/products') }}/${this.editingId}` : '{{ route('inventory.products.store') }}';
                        const method = this.editingId ? 'PUT' : 'POST';
                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify(this.formData)
                        });
                        const data = await response.json();
                        if (response.ok && data.success) {
                            this.$dispatch('close-modal', 'product-modal');
                            location.reload();
                            return;
                        }
                        if (response.status === 422) {
                            this.errors = data.errors || {};
                        } else {
                            console.error('Unexpected response', data);
                        }
                    } catch (error) {
                        console.error('Error submitting form:', error);
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</x-pos-layout>
