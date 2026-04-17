<x-pos-layout>
    <x-slot name="header">
        {{ __('Gestión Administrativa') }}
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Dashboard Stats -->
        <x-pos.card class="border-l-4 border-l-primary shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between p-2">
                <div>
                    <p class="text-xs uppercase tracking-wider font-semibold text-gray-400 dark:text-gray-500">Usuarios Totales</p>
                    <p class="text-3xl font-bold mt-1 text-gray-900 dark:text-white">{{ \App\Models\User::count() }}</p>
                </div>
                <div class="p-4 bg-primary/10 rounded-2xl text-primary">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-success font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                <span>Sincronizado</span>
            </div>
        </x-pos.card>

        <x-pos.card class="border-l-4 border-l-purple-500 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between p-2">
                <div>
                    <p class="text-xs uppercase tracking-wider font-semibold text-gray-400 dark:text-gray-500">Roles del Sistema</p>
                    <p class="text-3xl font-bold mt-1 text-gray-900 dark:text-white">{{ \Spatie\Permission\Models\Role::count() }}</p>
                </div>
                <div class="p-4 bg-purple-500/10 rounded-2xl text-purple-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-500 font-medium">
                <span>Gestión de acceso activa</span>
            </div>
        </x-pos.card>
    </div>

    <!-- Quick Access -->
    <div class="mt-10">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Accesos Directos</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('administration.users.index') }}" class="group block p-6 bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:border-primary/20 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">Gestión de Usuarios</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Alta, baja y modificación de usuarios del sistema.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('administration.roles.index') }}" class="group block p-6 bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:border-purple-500/20 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-500/10 rounded-2xl flex items-center justify-center text-purple-600 group-hover:bg-purple-500 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">Gestión de Roles</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configuración de perfiles y permisos de seguridad.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-pos-layout>