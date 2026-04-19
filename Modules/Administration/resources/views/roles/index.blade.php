<x-pos-layout>
    <x-slot name="header">{{ __('administration::roles.page_title') }}</x-slot>

    <div x-data="roleCrud()" class="space-y-6">

        {{-- ── Toolbar ─────────────────────────────────────────────── --}}
        <x-pos.crud-toolbar
            title="{{ __('administration::roles.list_title') }}"
            :subtitle="trans_choice('administration::roles.found', $roles->total(), ['count' => $roles->total()])"
        >
            <x-pos.button @click="openCreateModal()" variant="primary">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('administration::roles.new_role') }}
            </x-pos.button>
        </x-pos.crud-toolbar>

        {{-- ── Filtros ──────────────────────────────────────────────── --}}
        <x-pos.crud-filters search-placeholder="{{ __('administration::roles.search_ph') }}" />

        {{-- ═══════════════════════════════════════════════════════════
             DESKTOP (lg+): Tabla
        ═══════════════════════════════════════════════════════════ --}}
        <div class="hidden lg:block">
            <x-pos.card>
                <x-pos.table :headers="[
                    __('administration::roles.col_id'),
                    __('administration::roles.col_name'),
                    __('administration::roles.col_permissions'),
                    __('administration::roles.col_updated'),
                    __('administration::roles.col_actions'),
                ]">
                    @forelse($roles as $role)
                        <tr class="transition-colors hover:bg-gray-50/50 dark:hover:bg-gray-800/50">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                #{{ $role->id }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $role->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($role->permissions->take(5) as $perm)
                                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                            {{ $perm->name }}
                                        </span>
                                    @endforeach
                                    @if($role->permissions->count() > 5)
                                        <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                            +{{ $role->permissions->count() - 5 }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $role->updated_at->diffForHumans() }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-left text-sm font-medium">
                                <div class="flex gap-3">
                                    <button @click="openEditModal(@js($role))"
                                            class="text-primary transition-colors hover:text-primary/80"
                                            title="{{ __('common.edit') }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    @if($role->name !== 'Super Admin')
                                    <button @click="confirmDelete({{ $role->id }})"
                                            class="text-danger hover:text-danger/80 transition-colors"
                                            title="{{ __('common.delete') }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-400 dark:text-gray-500">
                                {{ __('administration::roles.no_roles') }}
                            </td>
                        </tr>
                    @endforelse

                    <x-slot name="paginate">
                        {{ $roles->links() }}
                    </x-slot>
                </x-pos.table>
            </x-pos.card>
        </div>

        {{-- ═══════════════════════════════════════════════════════════
             MOBILE / TABLET (< lg): Cards
        ═══════════════════════════════════════════════════════════ --}}
        <div class="lg:hidden">
            @if ($roles->isEmpty())
                <div class="py-16 text-center text-gray-400 dark:text-gray-500">
                    <svg class="mx-auto mb-3 h-12 w-12 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    {{ __('administration::roles.no_roles') }}
                </div>
            @else
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                    @foreach ($roles as $role)
                        <div class="flex flex-col gap-3 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition-shadow hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold leading-tight text-gray-900 dark:text-white">{{ $role->name }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">#{{ $role->id }}</p>
                                </div>
                                @if($role->name === 'Super Admin')
                                    <span class="shrink-0 rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                                        {{ __('administration::roles.badge_protected') }}
                                    </span>
                                @endif
                            </div>

                            <div class="space-y-3 border-t border-gray-100 pt-3 dark:border-gray-800">
                                <div>
                                    <div class="mb-2 flex items-center justify-between gap-3">
                                        <span class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">
                                            {{ __('administration::roles.field_permissions') }}
                                        </span>
                                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                                            {{ trans_choice('administration::roles.permissions_count', $role->permissions->count(), ['count' => $role->permissions->count()]) }}
                                        </span>
                                    </div>

                                    @if($role->permissions->isEmpty())
                                        <p class="text-sm text-gray-400 dark:text-gray-500">
                                            {{ __('administration::roles.permission_none') }}
                                        </p>
                                    @else
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($role->permissions->take(4) as $perm)
                                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-[10px] font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                                    {{ $perm->name }}
                                                </span>
                                            @endforeach
                                            @if($role->permissions->count() > 4)
                                                <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-0.5 text-[10px] font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                                    +{{ $role->permissions->count() - 4 }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('administration::roles.updated') }} {{ $role->updated_at->diffForHumans() }}
                                </div>
                            </div>

                            <div class="mt-auto flex gap-2 border-t border-gray-100 pt-3 dark:border-gray-800">
                                <button @click="openEditModal(@js($role))"
                                        class="flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-primary/10 px-3 py-2 text-xs font-medium text-primary transition-colors hover:bg-primary/20">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ __('common.edit') }}
                                </button>
                                @if($role->name !== 'Super Admin')
                                    <button @click="confirmDelete({{ $role->id }})"
                                            class="flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-danger/10 px-3 py-2 text-xs font-medium text-danger transition-colors hover:bg-danger/20">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        {{ __('common.delete') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <x-pos.crud-pagination :paginator="$roles" />
            @endif
        </div>

        {{-- ════ Modal Crear / Editar ════════════════════════════════ --}}
        <x-pos.modal name="role-modal" focusable>
            <div class="p-6">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white"
                        x-text="editMode ? '{{ __('administration::roles.modal_edit') }}' : '{{ __('administration::roles.modal_create') }}'"></h2>
                    <button type="button" @click="$dispatch('close-modal', 'role-modal')" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm()" class="space-y-4">
                    <x-pos.input x-model="formData.name"
                                 label="{{ __('administration::roles.field_name') }}"
                                 placeholder="{{ __('administration::roles.field_name_ph') }}" />
                    <template x-if="errors.name">
                        <p class="text-sm text-danger" x-text="errors.name[0]"></p>
                    </template>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-3">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('administration::roles.field_permissions') }}
                            </label>
                            <span class="text-xs font-semibold text-gray-400 dark:text-gray-500"
                                  x-text="selectedPermissionsLabel()"></span>
                        </div>
                        <div class="grid grid-cols-1 gap-2 border rounded-xl p-4 bg-gray-50/50 dark:bg-gray-800/50 dark:border-gray-700 overflow-y-auto max-h-60 sm:grid-cols-2">
                            @foreach($permissions as $permission)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox"
                                           value="{{ $permission->name }}"
                                           x-model="formData.permissions"
                                           class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4 transition-all">
                                    <span class="text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">
                                        {{ $permission->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6">
                        <x-pos.button variant="outline" @click="$dispatch('close-modal', 'role-modal')" x-bind:disabled="loading">
                            {{ __('common.cancel') }}
                        </x-pos.button>
                        <x-pos.button type="submit" variant="primary" x-bind:disabled="loading">
                            <span x-show="!loading"
                                  x-text="editMode ? '{{ __('administration::roles.btn_update') }}' : '{{ __('administration::roles.btn_create') }}'"></span>
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

        {{-- ════ Modal Confirmar Eliminar ══════════════════════════ --}}
        <x-pos.modal name="confirm-delete" maxWidth="sm">
            <div class="p-6 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-danger/10 text-danger">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-white">{{ __('administration::roles.confirm_delete_title') }}</h3>
                <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('administration::roles.confirm_delete_body') }}
                </p>
                <div class="flex justify-center gap-3">
                    <x-pos.button variant="outline" @click="$dispatch('close-modal', 'confirm-delete')">
                        {{ __('common.cancel') }}
                    </x-pos.button>
                    <x-pos.button @click="executeDelete()" variant="danger">
                        {{ __('administration::roles.btn_confirm_delete') }}
                    </x-pos.button>
                </div>
            </div>
        </x-pos.modal>

    </div>

    @push('scripts')
        <script>
            function roleCrud() {
                return {
                    editMode: false,
                    loading: false,
                    formData: { id: null, name: '', permissions: [] },
                    errors: {},
                    filters: {
                        search: @js($search ?? ''),
                        per_page: @js($perPage ?? 10),
                    },
                    messages: {
                        invalidResponse: @js(__('administration::roles.msg_invalid_response')),
                        saveError: @js(__('administration::roles.msg_save_error')),
                        deleteError: @js(__('administration::roles.msg_delete_error')),
                    },
                    deleteId: null,

                    init() {
                        this.$watch('filters.search', (val) => {
                            clearTimeout(this._searchTimer);
                            this._searchTimer = setTimeout(() => this.applyFilters(), 600);
                        });
                    },

                    applyFilters() {
                        const url = new URL(window.location.href);
                        url.searchParams.set('search', this.filters.search);
                        url.searchParams.set('per_page', this.filters.per_page);
                        url.searchParams.set('page', 1);
                        window.location.href = url.toString();
                    },

                    openCreateModal() {
                        this.editMode = false;
                        this.formData = { id: null, name: '', permissions: [] };
                        this.errors = {};
                        this.$dispatch('open-modal', 'role-modal');
                    },

                    openEditModal(role) {
                        this.editMode = true;
                        this.formData = {
                            id: role.id,
                            name: role.name,
                            permissions: role.permissions.map(p => p.name)
                        };
                        this.errors = {};
                        this.$dispatch('open-modal', 'role-modal');
                    },

                    selectedPermissionsLabel() {
                        const count = this.formData.permissions?.length || 0;

                        if (count === 0) {
                            return @js(__('administration::roles.permission_none'));
                        }

                        return count === 1
                            ? @js(__('administration::roles.permission_single_selected'))
                            : @js(__('administration::roles.permission_selected', ['count' => '__count__'])).replace('__count__', count);
                    },

                    buildFormData() {
                        const payload = new FormData();

                        payload.append('name', this.formData.name ?? '');

                        (this.formData.permissions || []).forEach((permission, index) => {
                            payload.append(`permissions[${index}]`, permission);
                        });

                        if (this.editMode) {
                            payload.append('_method', 'PUT');
                        }

                        return payload;
                    },

                    async submitForm() {
                        this.loading = true;
                        this.errors = {};
                        const url = this.editMode ? `/administration/roles/${this.formData.id}` : '/administration/roles';
                        try {
                            const response = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                                body: this.buildFormData(),
                            });

                            const contentType = response.headers.get('content-type') || '';
                            const data = contentType.includes('application/json')
                                ? await response.json()
                                : { message: this.messages.invalidResponse };

                            if (response.ok) {
                                this.$dispatch('close-modal', 'role-modal');
                                this.notify(data.message);
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                this.errors = data.errors || {};
                                if (data.message && Object.keys(this.errors).length === 0) {
                                    this.notify(data.message, 'warning');
                                }
                            }
                        } catch (e) {
                            console.error(e);
                            this.notify(this.messages.saveError, 'danger');
                        } finally {
                            this.loading = false;
                        }
                    },

                    confirmDelete(id) {
                        this.deleteId = id;
                        this.$dispatch('open-modal', 'confirm-delete');
                    },

                    async executeDelete() {
                        try {
                            const response = await fetch(`/administration/roles/${this.deleteId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                            });
                            const data = await response.json();
                            if (response.ok) {
                                this.$dispatch('close-modal', 'confirm-delete');
                                this.notify(data.message);
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                this.notify(data.message || this.messages.deleteError, 'danger');
                            }
                        } catch (e) {
                            console.error(e);
                            this.notify(this.messages.deleteError, 'danger');
                        }
                    },

                    notify(message, type = 'success') {
                        window.dispatchToast({ type, message });
                    },
                };
            }
        </script>
    @endpush
</x-pos-layout>
