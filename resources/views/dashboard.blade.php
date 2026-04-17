<x-pos-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stats Cards -->
        <x-pos.card class="border-l-4 border-l-primary">
            <div class="flex items-center justify-between p-2">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Usuarios Activos</p>
                    <p class="text-2xl font-bold mt-1">{{ \App\Models\User::count() }}</p>
                </div>
                <div class="p-3 bg-primary/10 rounded-xl text-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </x-pos.card>

        <x-pos.card class="border-l-4 border-l-success">
            <div class="flex items-center justify-between p-2">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Roles Definidos</p>
                    <p class="text-2xl font-bold mt-1">{{ \Spatie\Permission\Models\Role::count() }}</p>
                </div>
                <div class="p-3 bg-success/10 rounded-xl text-success">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
            </div>
        </x-pos.card>
    </div>

    <div class="mt-8">
        <x-pos.card>
            <div class="p-4">
                <h3 class="text-lg font-bold mb-4">Bienvenido al Sistema POS</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Desde aquí puedes gestionar los accesos, roles y configuraciones generales del sistema de administración.
                </p>
            </div>
        </x-pos.card>
    </div>
</x-pos-layout>
