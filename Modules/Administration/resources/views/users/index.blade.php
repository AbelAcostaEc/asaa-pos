<x-pos-layout>
    <x-slot name="header">
        Gestión de Usuarios
    </x-slot>

    <div x-data="userCrud()" class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Lista de Usuarios</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total de usuarios: {{ $users->total() }}</p>
            </div>
            <x-pos.button @click="openCreateModal()" variant="primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Nuevo Usuario
            </x-pos.button>
        </div>

        <!-- Users Table -->
        <x-pos.card>
            <x-pos.table :headers="['ID', 'Nombre', 'Email', 'Estado', 'Última Act.', 'Acciones']">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#{{ $user->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-500 font-bold mr-3">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span @click="toggleStatus({{ $user->id }})" class="cursor-pointer px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }}">
                                {{ $user->is_active ? 'Activo' : 'Deshabilitado' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->updated_at->diffForHumans() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <div class="flex gap-3">
                                <button @click="openEditModal(@js($user))" class="text-primary hover:text-primary/80 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="confirmDisable({{ $user->id }}, {{ $user->is_active ? 'true' : 'false' }})" class="{{ $user->is_active ? 'text-danger hover:text-danger/80' : 'text-success hover:text-success/80' }} transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            No se encontraron usuarios.
                        </td>
                    </tr>
                @endforelse

                <x-slot name="paginate">
                    {{ $users->links() }}
                </x-slot>
            </x-pos.table>
        </x-pos.card>

        <!-- Upsert Modal -->
        <x-pos.modal name="user-modal" focusable>
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white" x-text="editMode ? 'Editar Usuario' : 'Nuevo Usuario'"></h2>
                    <button @click="$dispatch('close-modal', 'user-modal')" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
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
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
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
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
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
            style="display: none;"
        >
            <div class="w-8 h-8 bg-success/20 text-success rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
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
                confirmAction: {
                    id: null,
                    isActive: true
                },

                openCreateModal() {
                    this.editMode = false;
                    this.formData = { id: null, name: '', email: '', password: '' };
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
                    
                    const url = this.editMode 
                        ? `/administration/users/${this.formData.id}` 
                        : '/administration/users';
                    
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
                    this.confirmAction = { id, isActive };
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
                    } catch (error) { console.error(error); }
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
