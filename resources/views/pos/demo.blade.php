<x-pos-layout>
    <x-slot name="header">
        Dashboard POS
    </x-slot>

    <div class="space-y-8">
        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-pos.card class="border-l-4 border-primary">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Ventas Hoy</p>
                        <h3 class="text-2xl font-bold mt-1">$1,240.00</h3>
                    </div>
                    <div class="p-3 bg-primary/10 rounded-xl text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <p class="text-xs text-success font-medium mt-4 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7l-3 3-3-3m5 8V10h-4v5h4z" clip-rule="evenodd" /></svg>
                    +12% vs ayer
                </p>
            </x-pos.card>

            <x-pos.card class="border-l-4 border-secondary">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Órdenes</p>
                        <h3 class="text-2xl font-bold mt-1">45</h3>
                    </div>
                    <div class="p-3 bg-secondary/10 rounded-xl text-secondary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4 font-medium">Hoy</p>
            </x-pos.card>

            <x-pos.card class="border-l-4 border-accent">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Clientes</p>
                        <h3 class="text-2xl font-bold mt-1">12</h3>
                    </div>
                    <div class="p-3 bg-accent/10 rounded-xl text-accent">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <p class="text-xs text-accent font-medium mt-4">Nuevos este mes</p>
            </x-pos.card>

            <x-pos.card class="border-l-4 border-warning">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Stock Bajo</p>
                        <h3 class="text-2xl font-bold mt-1">8</h3>
                    </div>
                    <div class="p-3 bg-warning/10 rounded-xl text-warning">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                </div>
                <p class="text-xs text-danger font-medium mt-4">Requiere atención</p>
            </x-pos.card>
        </div>

        <!-- Alerts Section -->
        <div class="space-y-4">
            <h2 class="text-lg font-bold">Sistema de Alertas</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-pos.alert type="info">¡Bienvenido al nuevo sistema POS! Todo funciona correctamente.</x-pos.alert>
                <x-pos.alert type="success">Venta realizada con éxito. El ticket ha sido impreso.</x-pos.alert>
                <x-pos.alert type="warning">El inventario de "Cerveza Corona" está por debajo del mínimo.</x-pos.alert>
                <x-pos.alert type="danger">Error al procesar el pago. Por favor intente de nuevo.</x-pos.alert>
            </div>
        </div>

        <!-- Buttons & Modals Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" x-data="{ modalOpen: false }">
            <x-pos.card title="Componentes UI">
                <div class="space-y-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-3">Botones Dinámicos</p>
                        <div class="flex flex-wrap gap-3">
                            <x-pos.button variant="primary">Principal</x-pos.button>
                            <x-pos.button variant="secondary">Secundario</x-pos.button>
                            <x-pos.button variant="accent">Accent</x-pos.button>
                            <x-pos.button variant="outline">Outline</x-pos.button>
                            <x-pos.button variant="danger">Danger</x-pos.button>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-3">Interacción con Alpine.js</p>
                        <x-pos.button @click="$dispatch('open-modal', 'demo-modal')" variant="accent" class="w-full sm:w-auto">
                            Abrir Modal Funcional
                        </x-pos.button>
                    </div>
                </div>
            </x-pos.card>

            <x-pos.card title="Formulario Estilizado">
                <form class="space-y-4">
                    <x-pos.input label="Nombre del Producto" placeholder="Ej. Coca Cola 600ml" />
                    <div class="grid grid-cols-2 gap-4">
                        <x-pos.input label="Precio" type="number" step="0.01" value="15.50" />
                        <x-pos.input label="Stock" type="number" value="100" error="Quedan pocas unidades" />
                    </div>
                    <div class="flex justify-end pt-2">
                        <x-pos.button variant="primary">Guardar Cambios</x-pos.button>
                    </div>
                </form>
            </x-pos.card>
        </div>

        <!-- Table Section -->
        <x-pos.card title="Últimas Ventas (Tabla con Paginación)">
            <x-pos.table :headers="['ID', 'Cliente', 'Total', 'Estado', 'Fecha', 'Acciones']">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#1234</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Juan Pérez</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-primary">$45.00</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-success/10 text-success">Completado</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Hace 5 min</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button class="text-accent hover:text-accent/80">Ver</button>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#1235</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Maria García</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-primary">$120.50</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-warning/10 text-warning">Pendiente</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Hace 15 min</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button class="text-accent hover:text-accent/80">Ver</button>
                    </td>
                </tr>
                
                <x-slot name="paginate">
                    <div class="flex items-center justify-between border-t border-gray-100 dark:border-gray-800 pt-4">
                        <p class="text-sm text-gray-500">Mostrando 1 a 2 de 50 resultados</p>
                        <div class="flex gap-2">
                            <x-pos.button variant="outline" size="sm" disabled>Anterior</x-pos.button>
                            <x-pos.button variant="outline" size="sm">Siguiente</x-pos.button>
                        </div>
                    </div>
                </x-slot>
            </x-pos.table>
        </x-pos.card>
    </div>

    <!-- Modal Definition -->
    <x-pos.modal name="demo-modal" focusable>
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Modal de Confirmación</h2>
                <button @click="$dispatch('close-modal', 'demo-modal')" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Este es un ejemplo de modal responsivo y accesible utilizando Alpine.js. Soporta transitions, cierre con escape y click fuera, y gestión de foco.
            </p>

            <div class="flex justify-end gap-3">
                <x-pos.button variant="outline" @click="$dispatch('close-modal', 'demo-modal')">Cancelar</x-pos.button>
                <x-pos.button variant="primary" @click="$dispatch('close-modal', 'demo-modal')">Aceptar y Continuar</x-pos.button>
            </div>
        </div>
    </x-pos.modal>
</x-pos-layout>
