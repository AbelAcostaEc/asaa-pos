<x-pos-layout>
    <x-slot name="header">
        Gestión de Usuarios
    </x-slot>

    <div x-data="userCrud()" class="space-y-6">
        <!-- Header Actions -->
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Lista de Usuarios</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $users->total() }} usuarios encontrados</p>
            </div>
            <x-pos.button @click="openCreateModal()" variant="primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nuevo Usuario
            </x-pos.button>
        </div>

        <!-- Filters Row — responsive -->
        <div class="flex flex-wrap items-center gap-3">
            <!-- Search Input -->
            <div class="relative flex-1 min-w-[200px] max-w-md">
                <input
                    type="text"
                    x-model="filters.search"
                    @keydown.enter="applyFilters()"
                    placeholder="Buscar por nombre o email..."
                    class="w-full pl-9 pr-9 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-gray-700
                           bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300
                           placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/30
                           focus:border-primary transition-all"
                >
                <!-- Clear button -->
                <button
                    x-show="filters.search"
                    @click="filters.search = ''; applyFilters()"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                    style="display:none;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Per Page Selector -->
            <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 shadow-sm shrink-0">
                <span class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">Mostrar</span>
                <select
                    x-model="filters.per_page"
                    @change="applyFilters()"
                    class="text-sm font-medium bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-primary/30 cursor-pointer"
                >
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">registros</span>
            </div>
        </div>

        <!-- ═══ DESKTOP TABLE (lg+) ═══ -->
        <div class="hidden lg:block">
            <x-pos.card>
                <x-pos.table :headers="['ID', 'Nombre', 'Email', 'Estado', 'Última Act.', 'Acciones']">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#{{ $user->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-sm mr-3">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }}">
                                {{ $user->is_active ? 'Activo' : 'Deshabilitado' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->updated_at->diffForHumans() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <div class="flex gap-3">
                                <button @click="openEditModal(@js($user))" class="text-primary hover:text-primary/80 transition-colors" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="confirmDisable({{ $user->id }}, {{ $user->is_active ? 'true' : 'false' }})" class="{{ $user->is_active ? 'text-danger hover:text-danger/80' : 'text-success hover:text-success/80' }} transition-colors" title="{{ $user->is_active ? 'Deshabilitar' : 'Habilitar' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-400 dark:text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            No se encontraron usuarios.
                        </td>
                    </tr>
                    @endforelse

                    <x-slot name="paginate">
                        {{ $users->links() }}
                    </x-slot>
                </x-pos.table>
            </x-pos.card>
        </div>

        <!-- ═══ MOBILE / TABLET CARDS (< lg) ═══ -->
        <div class="lg:hidden">
            @if($users->isEmpty())
                <div class="py-16 text-center text-gray-400 dark:text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    No se encontraron usuarios.
                </div>
            @else
                <!-- Grid: 3 cols md / 2 cols sm / 1 col default -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($users as $user)
                    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm flex flex-col gap-3 hover:shadow-md transition-shadow">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold text-base shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white leading-tight">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">#{{ $user->id }}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full shrink-0 {{ $user->is_active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }}">
                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="space-y-1.5 text-sm border-t border-gray-100 dark:border-gray-800 pt-3">
                            <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 min-w-0">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span class="truncate">{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-400 dark:text-gray-500 text-xs">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Actualizado {{ $user->updated_at->diffForHumans() }}
                            </div>
                        </div>

                        <!-- Card Actions -->
                        <div class="flex gap-2 border-t border-gray-100 dark:border-gray-800 pt-3 mt-auto">
                            <button
                                @click="openEditModal(@js($user))"
                                class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Editar
                            </button>
                            <button
                                @click="confirmDisable({{ $user->id }}, {{ $user->is_active ? 'true' : 'false' }})"
                                class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg {{ $user->is_active ? 'bg-danger/10 text-danger hover:bg-danger/20' : 'bg-success/10 text-success hover:bg-success/20' }} transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                {{ $user->is_active ? 'Deshabilitar' : 'Habilitar' }}
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination below cards -->
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- Upsert Modal -->
        <x-pos.modal name="user-modal" focusable>
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white" x-text="editMode ? 'Editar Usuario' : 'Nuevo Usuario'"></h2>
                    <button @click="$dispatch('close-modal', 'user-modal')" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm()" class="space-y-4">
                    <x-pos.input x-model="formData.name" label="Nombre Completo" placeholder="Ej. Juan Pérez" />
                    <template x-if="errors.name">
                        <p class="text-sm text-danger" x-text="errors.name[0]"></p>
                    </template>

                    <x-pos.input x-model="formData.email" type="email" label="Correo Electrónico" placeholder="juan@example.com" />
                    <template x-if="errors.email">
                        <p class="text-sm text-danger" x-text="errors.email[0]"></p>
                    </template>

                    <div class="space-y-1">
                        <x-pos.input x-model="formData.password" type="password" label="Contraseña" x-bind:placeholder="editMode ? 'Dejar en blanco para mantener' : 'Mínimo 8 caracteres'" />
                        <template x-if="errors.password">
                            <p class="text-sm text-danger" x-text="errors.password[0]"></p>
                        </template>
                        <template x-if="editMode">
                            <p class="text-xs text-gray-500">Solo completa si deseas cambiar la contraseña.</p>
                        </template>
                    </div>

                    <div class="flex justify-end gap-3 pt-6">
                        <x-pos.button variant="outline" @click="$dispatch('close-modal', 'user-modal')" x-bind:disabled="loading">
                            Cancelar
                        </x-pos.button>
                        <x-pos.button type="submit" variant="primary" x-bind:disabled="loading">
                            <span x-show="!loading" x-text="editMode ? 'Actualizar' : 'Crear Usuario'"></span>
                            <span x-show="loading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Procesando...
                            </span>
                        </x-pos.button>
                    </div>
                </form>
            </div>
        </x-pos.modal>

        <!-- Disable Confirmation Modal -->
        <x-pos.modal name="confirm-disable" maxWidth="sm">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-danger/10 text-danger rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2" x-text="confirmAction.isActive ? '¿Deshabilitar Usuario?' : '¿Habilitar Usuario?'"></h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    Esta acción cambiará el estado de acceso del usuario al sistema.
                </p>

                <div class="flex justify-center gap-3">
                    <x-pos.button variant="outline" @click="$dispatch('close-modal', 'confirm-disable')">Cancelar</x-pos.button>
                    <x-pos.button @click="executeToggle()" variant="danger" x-text="confirmAction.isActive ? 'Confirmar Deshabilitar' : 'Confirmar Habilitar'"></x-pos.button>
                </div>
            </div>
        </x-pos.modal>

        <!-- Success Toast (Alpine-only simple notification) -->
        <div
            x-show="toast.show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-10 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="translate-y-10 opacity-0"
            class="fixed bottom-8 right-8 z-50 p-4 bg-gray-900 text-white rounded-xl shadow-2xl flex items-center gap-3"
            style="display: none;">
            <div class="w-8 h-8 bg-success/20 text-success rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <p class="text-sm font-medium" x-text="toast.message"></p>
        </div>
    </div>

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
                    password: ''
                },
                errors: {},
                toast: {
                    show: false,
                    message: ''
                },
                filters: {
                    search: @js($search ?? ''),
                    per_page: @js($perPage ?? 10)
                },
                confirmAction: {
                    id: null,
                    isActive: true
                },
                _searchTimer: null,

                init() {
                    this.$watch('filters.search', () => {
                        clearTimeout(this._searchTimer);
                        this._searchTimer = setTimeout(() => {
                            this.applyFilters();
                        }, 600);
                    });
                },

                applyFilters() {
                    let url = new URL(window.location.href);
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
                        password: ''
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
                        password: ''
                    };
                    this.errors = {};
                    this.$dispatch('open-modal', 'user-modal');
                },

                async submitForm() {
                    this.loading = true;
                    this.errors = {};

                    const url = this.editMode ?
                        `/administration/users/${this.formData.id}` :
                        '/administration/users';

                    const method = this.editMode ? 'PUT' : 'POST';

                    try {
                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.formData)
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.$dispatch('close-modal', 'user-modal');
                            this.showToast(data.message);
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            this.errors = data.errors || {};
                        }
                    } catch (error) {
                        console.error('Error:', error);
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
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();
                        if (response.ok) {
                            this.$dispatch('close-modal', 'confirm-disable');
                            this.showToast(data.message);
                            setTimeout(() => window.location.reload(), 1000);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                },

                async toggleStatus(id) {
                    // Quick toggle without confirmation
                    try {
                        const response = await fetch(`/administration/users/${id}/toggle`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (response.ok) {
                            this.showToast(data.message);
                            setTimeout(() => window.location.reload(), 500);
                        }
                    } catch (error) {
                        console.error(error);
                    }
                },

                showToast(msg) {
                    this.toast.message = msg;
                    this.toast.show = true;
                    setTimeout(() => this.toast.show = false, 3000);
                }
            }
        }
    </script>
    @endpush
</x-pos-layout>