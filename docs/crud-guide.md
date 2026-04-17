# Guía para crear un CRUD en ASAA POS

> Lee esta guía de arriba a abajo antes de escribir código.  
> El ejemplo completo usa **Productos** como caso de uso.

---

## Índice

1. [Estructura del proyecto](#1-estructura-del-proyecto)
2. [Paso 1 — Migración y Modelo](#paso-1--migración-y-modelo)
3. [Paso 2 — Controller](#paso-2--controller)
4. [Paso 3 — Rutas](#paso-3--rutas)
5. [Paso 4 — Vista](#paso-4--vista)
6. [Paso 5 — Enlace en el Sidebar](#paso-5--enlace-en-el-sidebar)
7. [Componentes reutilizables disponibles](#componentes-reutilizables-disponibles)
8. [Convenciones obligatorias](#convenciones-obligatorias)
9. [Checklist final](#checklist-final)

---

## 1. Estructura del proyecto

```
app/
├── Modules/
│   └── Administration/              ← Módulo donde viven los CRUDs
│       ├── app/Http/Controllers/    ← Controllers del módulo
│       ├── resources/views/         ← Vistas del módulo
│       └── routes/web.php           ← Rutas del módulo
├── app/Models/                      ← Modelos Eloquent
└── database/migrations/             ← Migraciones

resources/views/components/pos/      ← Componentes Blade reutilizables
├── button.blade.php
├── card.blade.php
├── crud-filters.blade.php           ← Buscador + selector de registros
├── crud-pagination.blade.php        ← Paginación wrapper
├── crud-toolbar.blade.php           ← Título + botón de acción
├── input.blade.php
├── modal.blade.php
└── table.blade.php
```

---

## Paso 1 — Migración y Modelo

### 1.1 Crear la migración

```bash
docker compose exec app php artisan make:migration create_products_table
```

### 1.2 Definir la tabla

```php
// database/migrations/xxxx_create_products_table.php
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2)->default(0);
        $table->boolean('is_active')->default(true);   // ← Siempre agregar is_active
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('products');
}
```

```bash
docker compose exec app php artisan migrate
```

### 1.3 Crear o actualizar el Modelo

```php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
```

---

## Paso 2 — Controller

Crear el archivo manualmente en `Modules/Administration/app/Http/Controllers/ProductController.php`.

> ⚠️ El namespace **siempre** debe ser `Modules\Administration\Http\Controllers`, no `App\Http\Controllers`.

```php
<?php

namespace Modules\Administration\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    // ── Index con búsqueda y paginación ──────────────────────────────
    public function index(Request $request)
    {
        $search  = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $products = Product::query()
            ->when($search, fn($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
            )
            ->paginate($perPage)
            ->appends($request->query());   // ← Mantiene ?search=... en los links

        return view('administration::products.index', compact('products', 'search', 'perPage'));
    }

    // ── Crear ─────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:products',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
        ]);

        $product = Product::create(array_merge($validated, ['is_active' => true]));

        return response()->json([
            'success' => true,
            'message' => 'Producto creado exitosamente',
            'product' => $product,
        ]);
    }

    // ── Actualizar ────────────────────────────────────────────────────
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
        ]);

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado exitosamente',
            'product' => $product,
        ]);
    }

    // ── Habilitar / Deshabilitar ──────────────────────────────────────
    public function toggleStatus(Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();

        $status = $product->is_active ? 'activado' : 'deshabilitado';

        return response()->json([
            'success'   => true,
            'message'   => "Producto {$status} exitosamente",
            'is_active' => $product->is_active,
        ]);
    }
}
```

---

## Paso 3 — Rutas

Editar `Modules/Administration/routes/web.php` y agregar dentro del grupo existente:

```php
use Modules\Administration\Http\Controllers\ProductController;

Route::middleware(['auth', 'verified'])->prefix('administration')->name('administration.')->group(function () {

    // ... rutas ya existentes ...

    // ── Productos ────────────────────────────────────────────────────
    Route::get   ('products',                [ProductController::class, 'index']       )->name('products.index');
    Route::post  ('products',                [ProductController::class, 'store']       )->name('products.store');
    Route::put   ('products/{product}',      [ProductController::class, 'update']      )->name('products.update');
    Route::patch ('products/{product}/toggle', [ProductController::class, 'toggleStatus'])->name('products.toggle');
});
```

> **Convención de nombres de rutas:**  
> `administration.products.index`, `administration.products.store`, etc.

---

## Paso 4 — Vista

Crear la carpeta y el archivo:

```
Modules/Administration/resources/views/products/index.blade.php
```

### Plantilla completa de la vista

Copia esta plantilla y reemplaza:
- `producto` / `Product` / `products` → nombre de tu entidad
- Las columnas de la tabla y los campos del modal
- Los nombres de las rutas

```blade
<x-pos-layout>
    <x-slot name="header">Gestión de Productos</x-slot>

    <div x-data="productCrud()" class="space-y-6">

        {{-- ── Toolbar ─────────────────────────────────────────────── --}}
        <x-pos.crud-toolbar
            title="Lista de Productos"
            :subtitle="$products->total() . ' productos encontrados'"
        >
            <x-pos.button @click="openCreateModal()" variant="primary">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nuevo Producto
            </x-pos.button>
        </x-pos.crud-toolbar>

        {{-- ── Filtros ──────────────────────────────────────────────── --}}
        <x-pos.crud-filters search-placeholder="Buscar por nombre o descripción..." />

        {{-- ═══════ DESKTOP (lg+): Tabla ═══════════════════════════════ --}}
        <div class="hidden lg:block">
            <x-pos.card>
                <x-pos.table :headers="['ID', 'Nombre', 'Precio', 'Estado', 'Actualizado', 'Acciones']">
                    @forelse($products as $product)
                        <tr class="transition-colors hover:bg-gray-50/50 dark:hover:bg-gray-800/50">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                #{{ $product->id }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $product->name }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                ${{ number_format($product->price, 2) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="{{ $product->is_active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }}
                                            inline-flex rounded-full px-2.5 py-1 text-xs font-semibold leading-5">
                                    {{ $product->is_active ? 'Activo' : 'Deshabilitado' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $product->updated_at->diffForHumans() }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-left text-sm font-medium">
                                <div class="flex gap-3">
                                    {{-- Editar --}}
                                    <button @click="openEditModal(@js($product))"
                                            class="text-primary transition-colors hover:text-primary/80"
                                            title="Editar">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    {{-- Deshabilitar / Habilitar --}}
                                    <button @click="confirmDisable({{ $product->id }}, {{ $product->is_active ? 'true' : 'false' }})"
                                            class="{{ $product->is_active ? 'text-danger hover:text-danger/80' : 'text-success hover:text-success/80' }} transition-colors"
                                            title="{{ $product->is_active ? 'Deshabilitar' : 'Habilitar' }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400 dark:text-gray-500">
                                <svg class="mx-auto mb-3 h-12 w-12 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                No se encontraron productos.
                            </td>
                        </tr>
                    @endforelse

                    <x-slot name="paginate">
                        {{ $products->links() }}
                    </x-slot>
                </x-pos.table>
            </x-pos.card>
        </div>

        {{-- ═══════ MOBILE / TABLET (< lg): Cards ══════════════════════ --}}
        <div class="lg:hidden">
            @if ($products->isEmpty())
                <div class="py-16 text-center text-gray-400 dark:text-gray-500">
                    No se encontraron productos.
                </div>
            @else
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                    @foreach ($products as $product)
                        <div class="flex flex-col gap-3 rounded-2xl border border-gray-100 bg-white p-5
                                    shadow-sm transition-shadow hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            {{-- Cabecera del card --}}
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $product->name }}</p>
                                <span class="{{ $product->is_active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }}
                                            shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold">
                                    {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                            {{-- Cuerpo --}}
                            <div class="space-y-1 border-t border-gray-100 pt-3 text-sm dark:border-gray-800">
                                <p class="text-gray-500">${{ number_format($product->price, 2) }}</p>
                                <p class="text-xs text-gray-400">{{ $product->updated_at->diffForHumans() }}</p>
                            </div>
                            {{-- Acciones --}}
                            <div class="mt-auto flex gap-2 border-t border-gray-100 pt-3 dark:border-gray-800">
                                <button @click="openEditModal(@js($product))"
                                        class="flex flex-1 items-center justify-center gap-1.5 rounded-lg
                                               bg-primary/10 px-3 py-2 text-xs font-medium text-primary
                                               transition-colors hover:bg-primary/20">
                                    Editar
                                </button>
                                <button @click="confirmDisable({{ $product->id }}, {{ $product->is_active ? 'true' : 'false' }})"
                                        class="{{ $product->is_active ? 'bg-danger/10 text-danger hover:bg-danger/20' : 'bg-success/10 text-success hover:bg-success/20' }}
                                               flex flex-1 items-center justify-center gap-1.5 rounded-lg
                                               px-3 py-2 text-xs font-medium transition-colors">
                                    {{ $product->is_active ? 'Deshabilitar' : 'Habilitar' }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Paginación para cards --}}
                <x-pos.crud-pagination :paginator="$products" />
            @endif
        </div>

        {{-- ═══════ Modal Crear / Editar ════════════════════════════════ --}}
        <x-pos.modal name="product-modal" focusable>
            <div class="p-6">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white"
                        x-text="editMode ? 'Editar Producto' : 'Nuevo Producto'"></h2>
                    <button @click="$dispatch('close-modal', 'product-modal')"
                            class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm()" class="space-y-4">
                    <x-pos.input x-model="formData.name" label="Nombre" placeholder="Ej. Coca-Cola 600ml" />
                    <template x-if="errors.name">
                        <p class="text-sm text-danger" x-text="errors.name[0]"></p>
                    </template>

                    <x-pos.input x-model="formData.price" type="number" step="0.01" label="Precio" placeholder="0.00" />
                    <template x-if="errors.price">
                        <p class="text-sm text-danger" x-text="errors.price[0]"></p>
                    </template>

                    {{-- Agrega más campos aquí según tu modelo --}}

                    <div class="flex justify-end gap-3 pt-6">
                        <x-pos.button variant="outline"
                                      @click="$dispatch('close-modal', 'product-modal')"
                                      x-bind:disabled="loading">
                            Cancelar
                        </x-pos.button>
                        <x-pos.button type="submit" variant="primary" x-bind:disabled="loading">
                            <span x-show="!loading" x-text="editMode ? 'Actualizar' : 'Crear Producto'"></span>
                            <span x-show="loading" class="flex items-center">
                                <svg class="-ml-1 mr-3 h-5 w-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Procesando...
                            </span>
                        </x-pos.button>
                    </div>
                </form>
            </div>
        </x-pos.modal>

        {{-- ═══════ Modal Confirmar Deshabilitar ════════════════════════ --}}
        <x-pos.modal name="confirm-disable" maxWidth="sm">
            <div class="p-6 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-danger/10 text-danger">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-white"
                    x-text="confirmAction.isActive ? '¿Deshabilitar Producto?' : '¿Habilitar Producto?'"></h3>
                <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                    Esta acción cambiará la visibilidad del producto en el sistema.
                </p>
                <div class="flex justify-center gap-3">
                    <x-pos.button variant="outline"
                                  @click="$dispatch('close-modal', 'confirm-disable')">Cancelar</x-pos.button>
                    <x-pos.button @click="executeToggle()" variant="danger"
                                  x-text="confirmAction.isActive ? 'Confirmar Deshabilitar' : 'Confirmar Habilitar'">
                    </x-pos.button>
                </div>
            </div>
        </x-pos.modal>

        {{-- ═══════ Toast ════════════════════════════════════════════════ --}}
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
            // ════════════════════════════════════════════════════════════
            // Cambia "productCrud" y las rutas /administration/products
            // por el nombre de tu entidad.
            // ════════════════════════════════════════════════════════════
            function productCrud() {
                return {
                    editMode: false,
                    loading: false,
                    formData: {
                        id: null,
                        name: '',
                        price: '',
                        // ← Agrega aquí los campos de tu modelo
                    },
                    errors: {},
                    toast: { show: false, message: '' },
                    filters: {
                        search: @js($search ?? ''),
                        per_page: @js($perPage ?? 10),
                    },
                    confirmAction: { id: null, isActive: true },
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
                        this.formData = { id: null, name: '', price: '' };
                        this.errors = {};
                        this.$dispatch('open-modal', 'product-modal');
                    },

                    openEditModal(item) {
                        this.editMode = true;
                        this.formData = {
                            id:    item.id,
                            name:  item.name,
                            price: item.price,
                            // ← Mapea los campos de tu modelo aquí
                        };
                        this.errors = {};
                        this.$dispatch('open-modal', 'product-modal');
                    },

                    async submitForm() {
                        this.loading = true;
                        this.errors = {};
                        const url    = this.editMode ? `/administration/products/${this.formData.id}` : '/administration/products';
                        const method = this.editMode ? 'PUT' : 'POST';
                        try {
                            const response = await fetch(url, {
                                method,
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify(this.formData),
                            });
                            const data = await response.json();
                            if (response.ok) {
                                this.$dispatch('close-modal', 'product-modal');
                                this.showToast(data.message);
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                this.errors = data.errors || {};
                            }
                        } catch (e) {
                            console.error(e);
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
                            const response = await fetch(`/administration/products/${this.confirmAction.id}/toggle`, {
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
                        } catch (e) { console.error(e); }
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
```

---

## Paso 5 — Enlace en el Sidebar

Editar `resources/views/components/pos-layout.blade.php` y agregar dentro del `<nav>`:

```blade
<a href="{{ route('administration.products.index') }}"
   class="flex items-center px-4 py-3 text-sm font-medium
          {{ request()->routeIs('administration.products.*') ? 'bg-primary/10 text-primary' : 'text-gray-600 dark:text-gray-400' }}
          rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
    {{-- Elige un ícono SVG apropiado para tu entidad --}}
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
    </svg>
    Productos
</a>
```

---

## Componentes reutilizables disponibles

| Componente | Uso |
|---|---|
| `<x-pos-layout>` | Layout principal con sidebar y navbar |
| `<x-pos.crud-toolbar title="..." :subtitle="...">` | Barra: título + botón de acción |
| `<x-pos.crud-filters search-placeholder="...">` | Buscador con debounce + selector de registros — **requiere `applyFilters()` en x-data** |
| `<x-pos.crud-pagination :paginator="$items">` | Paginación — solo se muestra si hay más de una página |
| `<x-pos.card>` | Card contenedora con sombra y bordes |
| `<x-pos.table :headers="[...]">` | Tabla con encabezados y slot de paginación |
| `<x-pos.modal name="..." focusable>` | Modal con Alpine.js (`open-modal` / `close-modal`) |
| `<x-pos.button variant="primary\|outline\|danger">` | Botón estilizado |
| `<x-pos.input x-model="..." label="..." type="...">` | Input estilizado con label |

### Props del componente `crud-filters`

| Prop | Default | Descripción |
|---|---|---|
| `search-model` | `filters.search` | Variable Alpine para el buscador |
| `per-page-model` | `filters.per_page` | Variable Alpine para registros por página |
| `apply-action` | `applyFilters()` | Función Alpine que se llama al filtrar |
| `search-placeholder` | `Buscar...` | Texto del placeholder |
| `per-page-options` | `[5,10,20,30,50,100]` | Opciones del selector |

---

## Convenciones obligatorias

| Regla | Detalle |
|---|---|
| Namespace del controller | `Modules\Administration\Http\Controllers` |
| Nombre de la vista | `administration::entidad.index` |
| Nombre de rutas | `administration.entidad.index / store / update / toggle` |
| Respuestas del controller | Siempre `response()->json([...])` para store/update/toggle |
| Campo de estado | Siempre `is_active` booleano — nunca eliminar registros |
| Variable Alpine | Debe incluir `filters.search`, `filters.per_page` y `applyFilters()` |
| Modal name | Diferente por CRUD: `product-modal`, `user-modal`, etc. |

---

## Checklist final

Antes de considerar el CRUD terminado, verifica:

- [ ] Migración creada y ejecutada (`php artisan migrate`)
- [ ] Modelo con `$fillable` y `casts()` correctos
- [ ] Controller con namespace `Modules\Administration\Http\Controllers`
- [ ] 4 métodos en el controller: `index`, `store`, `update`, `toggleStatus`
- [ ] 4 rutas en `Modules/Administration/routes/web.php`
- [ ] Vista en `Modules/Administration/resources/views/{entidad}/index.blade.php`
- [ ] Vista usa `<x-pos-layout>`, `<x-pos.crud-toolbar>`, `<x-pos.crud-filters>`
- [ ] Vista tiene tabla desktop (`hidden lg:block`) y cards mobile (`lg:hidden`)
- [ ] Cards tienen paginación con `<x-pos.crud-pagination :paginator="$items" />`
- [ ] Alpine `x-data` tiene: `filters.search`, `filters.per_page`, `applyFilters()`, `init()` con `$watch`
- [ ] Enlace agregado en el sidebar con `routeIs` para activación
- [ ] Prueba en desktop y en móvil (redimensiona el navegador)
