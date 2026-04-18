<x-pos-layout>
    <x-slot name="header">{{ __('administration::users.page_title') }}</x-slot>

    <div x-data="userCrud()" class="space-y-6">

        {{-- ── Toolbar ─────────────────────────────────────────────── --}}
        <x-pos.crud-toolbar
            title="{{ __('administration::users.list_title') }}"
            :subtitle="trans_choice('administration::users.found', $users->total(), ['count' => $users->total()])">
            <x-pos.button @click="openCreateModal()" variant="primary">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('administration::users.new_user') }}
            </x-pos.button>
        </x-pos.crud-toolbar>

        {{-- ── Filtros ──────────────────────────────────────────────── --}}
        <x-pos.crud-filters search-placeholder="{{ __('administration::users.search_ph') }}" />

        {{-- ═══════════════════════════════════════════════════════════
             DESKTOP (lg+): Tabla
        ═══════════════════════════════════════════════════════════ --}}
        <div class="hidden lg:block">
            <x-pos.card>
                <x-pos.table :headers="[__('administration::users.col_id'), __('administration::users.col_name'), __('administration::users.col_email'), __('administration::users.col_roles'), __('administration::users.col_status'), __('administration::users.col_updated'), __('administration::users.col_actions')]">
                    @forelse($users as $user)
                        <tr class="transition-colors hover:bg-gray-50/50 dark:hover:bg-gray-800/50">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                #{{ $user->id }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-sm font-bold text-primary">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->email }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @if ($user->roles)
                                        @foreach ($user->roles as $role)
                                            <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-0.5 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="{{ $user->is_active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }} inline-flex rounded-full px-2.5 py-1 text-xs font-semibold leading-5">
                                    {{ $user->is_active ? __('common.active') : __('common.disabled') }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->updated_at->diffForHumans() }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-left text-sm font-medium">
                                <div class="flex gap-3">
                                    <button @click="openEditModal(@js($user))"
                                        class="text-primary transition-colors hover:text-primary/80"
                                        title="{{ __('common.edit') }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button @click="confirmDisable({{ $user->id }}, {{ $user->is_active ? 'true' : 'false' }})"
                                        class="{{ $user->is_active ? 'text-danger hover:text-danger/80' : 'text-success hover:text-success/80' }} transition-colors"
                                        title="{{ $user->is_active ? __('common.disable') : __('common.enable') }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400 dark:text-gray-500">
                                <svg class="mx-auto mb-3 h-12 w-12 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ __('administration::users.no_users') }}
                            </td>
                        </tr>
                    @endforelse

                    <x-slot name="paginate">
                        {{ $users->links() }}
                    </x-slot>
                </x-pos.table>
            </x-pos.card>
        </div>

        {{-- ═══════════════════════════════════════════════════════════
             MOBILE / TABLET (< lg): Cards
        ═══════════════════════════════════════════════════════════ --}}
        <div class="lg:hidden">
            @if ($users->isEmpty())
                <div class="py-16 text-center text-gray-400 dark:text-gray-500">
                    <svg class="mx-auto mb-3 h-12 w-12 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ __('administration::users.no_users') }}
                </div>
            @else
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                    @foreach ($users as $user)
                        <div class="flex flex-col gap-3 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition-shadow hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            {{-- Header --}}
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-base font-bold text-primary">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold leading-tight text-gray-900 dark:text-white">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">#{{ $user->id }}</p>
                                    </div>
                                </div>
                                <span class="{{ $user->is_active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }} shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold">
                                    {{ $user->is_active ? __('common.active') : __('common.inactive') }}
                                </span>
                            </div>
                            {{-- Body --}}
                            <div class="space-y-1.5 border-t border-gray-100 pt-3 text-sm dark:border-gray-800">
                                <div class="flex min-w-0 items-center gap-2 text-gray-500 dark:text-gray-400">
                                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="truncate">{{ $user->email }}</span>
                                </div>
                                <div class="flex flex-wrap gap-1 py-1">
                                    @if ($user->roles)
                                        @foreach ($user->roles as $role)
                                            <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-0.5 text-[10px] font-medium text-purple-700 ring-1 ring-inset ring-purple-700/10">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('administration::users.updated') }} {{ $user->updated_at->diffForHumans() }}
                                </div>
                            </div>
                            {{-- Actions --}}
                            <div class="mt-auto flex gap-2 border-t border-gray-100 pt-3 dark:border-gray-800">
                                <button @click="openEditModal(@js($user))"
                                    class="flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-primary/10 px-3 py-2 text-xs font-medium text-primary transition-colors hover:bg-primary/20">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ __('common.edit') }}
                                </button>
                                <button @click="confirmDisable({{ $user->id }}, {{ $user->is_active ? 'true' : 'false' }})"
                                    class="{{ $user->is_active ? 'bg-danger/10 text-danger hover:bg-danger/20' : 'bg-success/10 text-success hover:bg-success/20' }} flex flex-1 items-center justify-center gap-1.5 rounded-lg px-3 py-2 text-xs font-medium transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    {{ $user->is_active ? __('common.disable') : __('common.enable') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <x-pos.crud-pagination :paginator="$users" />
            @endif
        </div>

        {{-- ════ Modal Crear / Editar ════════════════════════════════ --}}
        <x-pos.modal name="user-modal" focusable>
            <div class="p-6">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white"
                        x-text="editMode ? '{{ __('administration::users.modal_edit') }}' : '{{ __('administration::users.modal_create') }}'"></h2>
                    <button type="button" @click="$dispatch('close-modal', 'user-modal')" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm()" class="space-y-4">
                    <x-pos.input x-model="formData.name"
                        label="{{ __('administration::users.field_name') }}"
                        placeholder="{{ __('administration::users.field_name_ph') }}" />
                    <template x-if="errors.name">
                        <p class="text-sm text-danger" x-text="errors.name[0]"></p>
                    </template>

                    <x-pos.input x-model="formData.email" type="email"
                        label="{{ __('administration::users.field_email') }}"
                        placeholder="{{ __('administration::users.field_email_ph') }}" />
                    <template x-if="errors.email">
                        <p class="text-sm text-danger" x-text="errors.email[0]"></p>
                    </template>

                    <div class="space-y-1">
                        <x-pos.input x-model="formData.password" type="password"
                            label="{{ __('administration::users.field_password') }}"
                            x-bind:placeholder="editMode ? '{{ __('administration::users.field_password_ph_edit') }}' : '{{ __('administration::users.field_password_ph_create') }}'" />
                        <template x-if="errors.password">
                            <p class="text-sm text-danger" x-text="errors.password[0]"></p>
                        </template>
                        <template x-if="editMode">
                            <p class="text-xs text-gray-500">{{ __('administration::users.password_hint') }}</p>
                        </template>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('administration::users.field_roles') }}
                        </label>
                        <div class="grid grid-cols-1 gap-2 rounded-xl border bg-gray-50/50 p-4 sm:grid-cols-2 dark:border-gray-700 dark:bg-gray-800/50">
                            @foreach ($roles as $role)
                                <label class="group flex cursor-pointer items-center gap-3">
                                    <input type="checkbox"
                                        value="{{ $role->name }}"
                                        x-model="formData.roles"
                                        class="h-4 w-4 rounded border-gray-300 text-primary transition-all focus:ring-primary">
                                    <span class="text-sm text-gray-600 transition-colors group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white">
                                        {{ $role->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6">
                        <x-pos.button variant="outline" @click="$dispatch('close-modal', 'user-modal')" x-bind:disabled="loading">
                            {{ __('common.cancel') }}
                        </x-pos.button>
                        <x-pos.button type="submit" variant="primary" x-bind:disabled="loading">
                            <span x-show="!loading"
                                x-text="editMode ? '{{ __('administration::users.btn_update') }}' : '{{ __('administration::users.btn_create') }}'"></span>
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

        {{-- ════ Modal Confirmar Estado ══════════════════════════════ --}}
        <x-pos.modal name="confirm-disable" maxWidth="sm">
            <div class="p-6 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-danger/10 text-danger">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-white"
                    x-text="confirmAction.isActive ? '{{ __('administration::users.confirm_disable_title') }}' : '{{ __('administration::users.confirm_enable_title') }}'"></h3>
                <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('administration::users.confirm_body') }}
                </p>
                <div class="flex justify-center gap-3">
                    <x-pos.button variant="outline" @click="$dispatch('close-modal', 'confirm-disable')">
                        {{ __('common.cancel') }}
                    </x-pos.button>
                    <x-pos.button @click="executeToggle()" variant="danger"
                        x-text="confirmAction.isActive ? '{{ __('administration::users.btn_confirm_disable') }}' : '{{ __('administration::users.btn_confirm_enable') }}'">
                    </x-pos.button>
                </div>
            </div>
        </x-pos.modal>

        {{-- ════ Toast ═══════════════════════════════════════════════ --}}
        <div x-show="toast.show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-10 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="translate-y-10 opacity-0"
            class="fixed bottom-8 right-8 z-50 flex items-center gap-3 rounded-xl bg-gray-900 p-4 text-white shadow-2xl"
            style="display:none;">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-success/20 text-success">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <p class="text-sm font-medium" x-text="toast.message"></p>
        </div>

    </div>{{-- /x-data --}}

    @push('scripts')
        <script>
            function userCrud() {
                return {
                    editMode: false,
                    loading: false,
                    formData: {
                        id: null,
                        name: '',
                        email: '',
                        password: '',
                        roles: []
                    },
                    errors: {},
                    toast: {
                        show: false,
                        message: ''
                    },
                    filters: {
                        search: @js($search ?? ''),
                        per_page: @js($perPage ?? 10),
                    },
                    confirmAction: {
                        id: null,
                        isActive: true
                    },
                    _searchTimer: null,

                    init() {
                        this.$watch('filters.search', () => {
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
                        this.formData = {
                            id: null,
                            name: '',
                            email: '',
                            password: '',
                            roles: []
                        };
                        this.errors = {};
                        this.$dispatch('open-modal', 'user-modal');
                    },

                    openEditModal(user) {
                        this.editMode = true;
                        this.formData = {
                            id: user.id,
                            name: user.name,
                            email: user.email,
                            password: '',
                            roles: user.roles ? user.roles.map(r => r.name) : []
                        };
                        this.errors = {};
                        this.$dispatch('open-modal', 'user-modal');
                    },

                    buildFormData() {
                        const payload = new FormData();

                        payload.append('name', this.formData.name ?? '');
                        payload.append('email', this.formData.email ?? '');

                        if (this.formData.password) {
                            payload.append('password', this.formData.password);
                        }

                        (this.formData.roles || []).forEach((role, index) => {
                            payload.append(`roles[${index}]`, role);
                        });

                        if (this.editMode) {
                            payload.append('_method', 'PUT');
                        }

                        return payload;
                    },

                    async submitForm() {
                        this.loading = true;
                        this.errors = {};
                        const url = this.editMode ? `/administration/users/${this.formData.id}` : '/administration/users';
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
                                : { message: 'La respuesta del servidor no fue valida.' };

                            if (response.ok) {
                                this.$dispatch('close-modal', 'user-modal');
                                this.showToast(data.message);
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                this.errors = data.errors || {};
                                if (data.message && Object.keys(this.errors).length === 0) {
                                    this.showToast(data.message);
                                }
                            }
                        } catch (e) {
                            console.error(e);
                            this.showToast('No se pudo guardar el usuario.');
                        } finally {
                            this.loading = false;
                        }
                    },

                    confirmDisable(id, isActive) {
                        this.confirmAction = {
                            id,
                            isActive
                        };
                        this.$dispatch('open-modal', 'confirm-disable');
                    },

                    async executeToggle() {
                        try {
                            const response = await fetch(`/administration/users/${this.confirmAction.id}/toggle`, {
                                method: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                            });
                            const data = await response.json();
                            if (response.ok) {
                                this.$dispatch('close-modal', 'confirm-disable');
                                this.showToast(data.message);
                                setTimeout(() => window.location.reload(), 1000);
                            }
                        } catch (e) {
                            console.error(e);
                        }
                    },

                    async toggleStatus(id) {
                        try {
                            const response = await fetch(`/administration/users/${id}/toggle`, {
                                method: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                            });
                            const data = await response.json();
                            if (response.ok) {
                                this.showToast(data.message);
                                setTimeout(() => window.location.reload(), 500);
                            }
                        } catch (e) {
                            console.error(e);
                        }
                    },

                    showToast(msg) {
                        this.toast.message = msg;
                        this.toast.show = true;
                        setTimeout(() => this.toast.show = false, 3000);
                    },
                };
            }
        </script>
    @endpush
</x-pos-layout>
