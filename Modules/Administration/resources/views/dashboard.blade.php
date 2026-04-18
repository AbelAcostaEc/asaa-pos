<x-pos-layout>
    <x-slot name="header">
        {{ __('Gestión Administrativa') }}
    </x-slot>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <x-pos.stat-card label="Usuarios Totales" :value="\App\Models\User::count()" hint="Sincronizado con la base actual." tone="primary">
            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </x-pos.stat-card>

        <x-pos.stat-card label="Roles del Sistema" :value="\Spatie\Permission\Models\Role::count()" hint="Gestión de acceso activa." tone="accent">
            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
        </x-pos.stat-card>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        <x-pos.card>
            <x-slot name="header">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400 dark:text-slate-500">Atajos</p>
                        <h3 class="mt-2 text-xl font-black tracking-tight text-slate-900 dark:text-white">Accesos Directos</h3>
                    </div>
                    <div class="rounded-2xl bg-[rgb(var(--color-primary))]/10 px-3 py-1 text-xs font-semibold text-[rgb(var(--color-primary))]">Reusable UI</div>
                </div>
            </x-slot>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <a href="{{ route('administration.users.index') }}" class="group block rounded-3xl border border-slate-200/80 bg-white/60 p-6 transition-all duration-300 hover:-translate-y-1 hover:border-[rgb(var(--color-primary))]/20 hover:shadow-xl dark:border-slate-800 dark:bg-slate-900/40">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[rgb(var(--color-primary))]/10 text-[rgb(var(--color-primary))] transition-colors group-hover:bg-[rgb(var(--color-primary))] group-hover:text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white">Gestión de Usuarios</h4>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Alta, baja y modificación de usuarios del sistema.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('administration.roles.index') }}" class="group block rounded-3xl border border-slate-200/80 bg-white/60 p-6 transition-all duration-300 hover:-translate-y-1 hover:border-[rgb(var(--color-accent))]/20 hover:shadow-xl dark:border-slate-800 dark:bg-slate-900/40">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[rgb(var(--color-accent))]/10 text-[rgb(var(--color-accent))] transition-colors group-hover:bg-[rgb(var(--color-accent))] group-hover:text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white">Gestión de Roles</h4>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Configuración de perfiles y permisos de seguridad.</p>
                        </div>
                    </div>
                </a>
            </div>
        </x-pos.card>

        <x-pos.card>
            <x-slot name="header">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-400 dark:text-slate-500">UI Kit</p>
                    <h3 class="mt-2 text-xl font-black tracking-tight text-slate-900 dark:text-white">Base de Template</h3>
                </div>
            </x-slot>
            <div class="space-y-4">
                <x-pos.alert type="info" dismissible="false">El layout ahora usa un shell visual reusable con sidebar, topbar y superficies tipo glass.</x-pos.alert>
                <x-pos.alert type="success" dismissible="false">Las notificaciones ya pueden migrarse a un sistema global de toast para cualquier módulo.</x-pos.alert>
                <x-pos.alert type="warning" dismissible="false">La siguiente mejora ideal sería extraer textos del layout a archivos de idioma si quieres publicarlo como starter kit.</x-pos.alert>
            </div>
        </x-pos.card>
    </div>
</x-pos-layout>
