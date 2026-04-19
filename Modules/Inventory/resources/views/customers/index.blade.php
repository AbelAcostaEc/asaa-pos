<x-pos-layout>
    <x-slot name="header">{{ __('inventory::ui.customers.page_title') }}</x-slot>

    <div x-data="customerCrud()" class="space-y-6">

        {{-- ── Toolbar ─────────────────────────────────────────────── --}}
        <x-pos.crud-toolbar
            title="{{ __('inventory::ui.customers.list_title') }}"
            subtitle="{{ __('inventory::ui.customers.list_subtitle') }}">
            <x-pos.button @click="openCreateModal()" variant="primary">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('inventory::ui.customers.new') }}
            </x-pos.button>
        </x-pos.crud-toolbar>

        {{-- ── Filtros ──────────────────────────────────────────────── --}}
        <x-pos.crud-filters search-placeholder="{{ __('inventory::ui.customers.search') }}" />

        {{-- ═══════════════════════════════════════════════════════════
             DESKTOP (lg+): Tabla
        ═══════════════════════════════════════════════════════════ --}}
        <div class="hidden lg:block">
            <x-pos.card>
                <x-pos.table :headers="[__('inventory::ui.fields.id'), __('inventory::ui.fields.name'), __('inventory::ui.fields.phone'), __('inventory::ui.fields.email'), __('inventory::ui.fields.address'), __('inventory::ui.fields.actions')]">
                    @forelse($customers as $customer)
                        <tr class="transition-colors hover:bg-gray-50/50 dark:hover:bg-gray-800/50">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                #{{ $customer->id }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $customer->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $customer->phone }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $customer->email }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $customer->address }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                <x-pos.button @click="openEditModal({{ $customer->id }})" variant="secondary" size="sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.586-9.414a2 2 0 112.828 2.828L12 15l-4 1 1-4 8.414-8.414z" />
                                    </svg>
                                    {{ __('common.edit') }}
                                </x-pos.button>
                                <form action="{{ route('inventory.customers.destroy', $customer) }}" method="POST" class="inline">
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
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                {{ __('inventory::ui.customers.no_results') }}
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
            @forelse($customers as $customer)
                <x-pos.card>
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ $customer->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->phone }} - {{ $customer->email }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->address }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <x-pos.button @click="openEditModal({{ $customer->id }})" variant="secondary" size="sm">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.586-9.414a2 2 0 112.828 2.828L12 15l-4 1 1-4 8.414-8.414z" />
                                </svg>
                                {{ __('common.edit') }}
                            </x-pos.button>
                            <form action="{{ route('inventory.customers.destroy', $customer) }}" method="POST" class="inline">
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
                    <p class="text-center text-gray-500 dark:text-gray-400">{{ __('inventory::ui.customers.no_results') }}</p>
                </x-pos.card>
            @endforelse
        </div>

        {{-- ── Modal ────────────────────────────────────────────────── --}}
        <x-pos.modal name="customer-modal" focusable>
            <div class="p-6">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white" x-text="editingId ? @js(__('inventory::ui.customers.modal_edit')) : @js(__('inventory::ui.customers.modal_create'))"></h2>
                    <button type="button" @click="$dispatch('close-modal', 'customer-modal')" class="text-gray-400 hover:text-gray-500">
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
                        <x-pos.input label="{{ __('inventory::ui.fields.phone') }}" name="phone" x-model="formData.phone" />
                        <template x-if="errors.phone">
                            <p class="mt-1 text-sm text-danger" x-text="errors.phone[0]"></p>
                        </template>
                    </div>
                    <div>
                        <x-pos.input label="{{ __('inventory::ui.fields.email') }}" name="email" type="email" x-model="formData.email" />
                        <template x-if="errors.email">
                            <p class="mt-1 text-sm text-danger" x-text="errors.email[0]"></p>
                        </template>
                    </div>
                    <div>
                        <x-pos.input label="{{ __('inventory::ui.fields.address') }}" name="address" x-model="formData.address" />
                        <template x-if="errors.address">
                            <p class="mt-1 text-sm text-danger" x-text="errors.address[0]"></p>
                        </template>
                    </div>
                    <div class="flex justify-end gap-3 pt-6">
                        <x-pos.button variant="outline" @click="$dispatch('close-modal', 'customer-modal')" x-bind:disabled="loading">
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
        function customerCrud() {
            return {
                loading: false,
                editingId: null,
                errors: {},
                formData: {
                    name: '',
                    phone: '',
                    email: '',
                    address: ''
                },
                openCreateModal() {
                    this.editingId = null;
                    this.errors = {};
                    this.formData = { name: '', phone: '', email: '', address: '' };
                    this.$dispatch('open-modal', 'customer-modal');
                },
                async openEditModal(id) {
                    this.editingId = id;
                    this.errors = {};
                    try {
                        const response = await fetch(`{{ url('inventory/customers') }}/${id}/edit`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        this.formData = {
                            name: data.customer.name,
                            phone: data.customer.phone,
                            email: data.customer.email,
                            address: data.customer.address
                        };
                        this.$dispatch('open-modal', 'customer-modal');
                    } catch (error) {
                        console.error('Error fetching customer:', error);
                    }
                },
                async submitForm() {
                    this.loading = true;
                    this.errors = {};
                    try {
                        const url = this.editingId ? `{{ url('inventory/customers') }}/${this.editingId}` : '{{ route('inventory.customers.store') }}';
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
                            this.$dispatch('close-modal', 'customer-modal');
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
