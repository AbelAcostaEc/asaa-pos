# Guia practica de Alpine para CRUDs modulares

> Esta guia esta pensada para ti si ya dominas Laravel y controllers, pero Alpine todavia se siente "magico" o confuso.
>
> La idea no es solo que copies el patron, sino que entiendas por que esta armado asi y como repetirlo para otro CRUD sin sufrir.

---

## 1. Que problema resolvio este refactor

Antes teniamos algo asi:

- un `x-data="userCrud()"` en Blade
- una funcion enorme inline dentro del mismo Blade
- `fetch()` metido junto con estado UI, mensajes, modal, filtros y transformaciones

Eso funciona al inicio, pero cuando el CRUD crece aparecen estos problemas:

- el Blade se vuelve muy largo
- cuesta encontrar donde vive cada cosa
- se repite logica entre usuarios, roles, productos
- mezclar UI + HTTP + transformacion de datos vuelve mas dificil debuggear

El refactor separa responsabilidades.

---

## 2. Mapa mental simple

Piensa en esta arquitectura asi:

```text
Blade
  -> muestra la UI
  -> llama metodos Alpine
  -> pasa configuracion inicial

roleCrud.js / userCrud.js
  -> conecta la UI con la logica
  -> expone metodos para Alpine
  -> usa la fabrica base

crudFactory.js
  -> contiene la logica comun de CRUD
  -> loading, errors, submit, abrir modales, filtros

roleApi.js / userApi.js
  -> hablan con Laravel via fetch
  -> no saben nada de Alpine

roleMapper.js / userMapper.js
  -> traducen datos entre backend y frontend
  -> limpian formatos y evitan duplicacion

http.js
  -> wrapper generico de fetch
  -> CSRF, headers, parseo JSON, errores consistentes
```

Si lo resumes en una frase:

> Blade pinta, Crud coordina, Api consulta, Mapper traduce, Core reutiliza.

---

## 3. Estructura actual

```text
resources/js/
  core/
    http.js
    crudFactory.js

  modules/
    users/
      userApi.js
      userMapper.js
      userCrud.js

    roles/
      roleApi.js
      roleMapper.js
      roleCrud.js
```

---

## 4. Que hace cada archivo

### 4.1 `core/http.js`

Archivo: [resources/js/core/http.js](/home/asaa/Proyectos/docker/asaa-pos/app/resources/js/core/http.js:1)

Su trabajo es centralizar todo lo comun de `fetch`.

Resuelve estas preguntas:

- como se obtiene el CSRF token
- que headers se mandan siempre
- como parsear JSON de forma segura
- como lanzar errores uniformes cuando Laravel responde `422`, `403`, `500`, etc.

En vez de escribir esto en cada CRUD:

```js
fetch(url, {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        Accept: 'application/json',
    },
    body: payload,
})
```

ahora hacemos:

```js
http.post(url, { body: payload })
```

Eso reduce ruido y hace el codigo mas consistente.

### 4.2 `core/crudFactory.js`

Archivo: [resources/js/core/crudFactory.js](/home/asaa/Proyectos/docker/asaa-pos/app/resources/js/core/crudFactory.js:1)

Este es el corazon del refactor.

Es una fabrica: recibe configuracion y devuelve un objeto Alpine listo para usar.

Contiene logica comun como:

- `loading`
- `errors`
- `filters`
- `openCreateModal()`
- `openEditModal()`
- `submitForm()`
- `confirmDisable()` / `executeToggle()`
- `confirmDelete()` / `executeDelete()`
- `notify()`

La idea es:

- lo generico vive aqui
- lo especifico del recurso vive en `userCrud`, `roleCrud`, etc.

### 4.3 `modules/users/userApi.js`

Archivo: [resources/js/modules/users/userApi.js](/home/asaa/Proyectos/docker/asaa-pos/app/resources/js/modules/users/userApi.js:1)

Aqui viven solo llamadas HTTP del recurso usuario.

Ejemplo:

- `fetchById(id)`
- `create(payload)`
- `update(id, payload)`
- `toggleStatus(id)`

Importante:

`userApi.js` no sabe nada de modales, Alpine, `x-model`, ni DOM.

Solo sabe hablar con el backend.

### 4.4 `modules/users/userMapper.js`

Archivo: [resources/js/modules/users/userMapper.js](/home/asaa/Proyectos/docker/asaa-pos/app/resources/js/modules/users/userMapper.js:1)

Un mapper sirve para transformar datos.

Ejemplos reales:

- convertir `roles` del backend a un array limpio de nombres
- armar el `FormData`
- convertir `is_active` a boolean real
- preparar un item para mostrarse en tabla o card

Si el backend responde una cosa y la UI necesita otra forma, el mapper es el puente.

### 4.5 `modules/users/userCrud.js`

Archivo: [resources/js/modules/users/userCrud.js](/home/asaa/Proyectos/docker/asaa-pos/app/resources/js/modules/users/userCrud.js:1)

Este archivo es la capa que Alpine consume.

Hace tres cosas:

1. Toma `config` desde el Blade
2. Llama a `createCrudFactory(...)`
3. Agrega helpers especificos del recurso, por ejemplo:
   - `userName(id)`
   - `userRoles(id)`
   - `userStatusBadgeClass(id)`

Es decir:

- la base CRUD resuelve el comportamiento comun
- `userCrud.js` adapta esa base a "usuarios"

### 4.6 Roles sigue el mismo patron

Archivos:

- [resources/js/modules/roles/roleApi.js](/home/asaa/Proyectos/docker/asaa-pos/app/resources/js/modules/roles/roleApi.js:1)
- [resources/js/modules/roles/roleMapper.js](/home/asaa/Proyectos/docker/asaa-pos/app/resources/js/modules/roles/roleMapper.js:1)
- [resources/js/modules/roles/roleCrud.js](/home/asaa/Proyectos/docker/asaa-pos/app/resources/js/modules/roles/roleCrud.js:1)

Si entiendes Users, entiendes Roles.

La diferencia es solo la logica del recurso.

---

## 5. Como se conecta con Alpine

Archivo: [resources/js/app.js](/home/asaa/Proyectos/docker/asaa-pos/app/resources/js/app.js:1)

```js
import Alpine from 'alpinejs';
import roleCrud from './modules/roles/roleCrud';
import userCrud from './modules/users/userCrud';

document.addEventListener('alpine:init', () => {
    Alpine.data('roleCrud', roleCrud);
    Alpine.data('userCrud', userCrud);
});
```

Esto significa:

- `roleCrud` queda registrado como componente Alpine
- `userCrud` tambien
- luego Blade puede usar:

```blade
<div x-data="userCrud({...})">
```

Eso no ejecuta cualquier cosa "misteriosa".
Simplemente Alpine llama la funcion `userCrud(config)` y usa el objeto que retorna como estado del componente.

---

## 6. Como leer `x-data` sin perderte

Ejemplo simplificado:

```blade
<div
    x-data="userCrud({
        filters: {
            search: @js($search ?? ''),
            per_page: @js($perPage ?? 10),
        },
        endpoints: {
            collection: @js(route('administration.users.store')),
            resource: @js(url('administration/users')),
        },
        initialUsers: @js([...]),
    })"
>
```

Piensalo asi:

- `x-data` no es la logica completa
- `x-data` solo pasa configuracion inicial

Esa configuracion sirve para:

- mandar rutas Laravel al frontend
- mandar traducciones
- mandar filtros iniciales
- mandar datos iniciales ya renderizados por PHP

Entonces Blade sigue siendo importante, pero ya no contiene la logica operativa.

---

## 7. Flujo real de un CRUD de usuarios

### 7.1 Abrir modal crear

Cuando haces click:

```blade
@click="openCreateModal()"
```

ocurre esto:

1. Blade llama `openCreateModal()`
2. ese metodo viene de `crudFactory.js`
3. resetea `formData`
4. limpia errores
5. abre el modal con `$dispatch('open-modal', 'user-modal')`

### 7.2 Abrir modal editar

Cuando haces click:

```blade
@click="openEditModal(5)"
```

ocurre esto:

1. Alpine llama `openEditModal(id)`
2. `crudFactory.js` delega en `api.fetchById(id)` o `getEditData(...)`
3. el resultado llega al mapper
4. `mapDetailToForm(...)` transforma la respuesta a estructura de formulario
5. `formData` se llena
6. se abre el modal

### 7.3 Guardar

Cuando haces submit:

```blade
@submit.prevent="submitForm()"
```

flujo:

1. `submitForm()` activa `loading = true`
2. llama `buildPayload({ formData, editMode })`
3. decide si crear o actualizar
4. usa `api.create(...)` o `api.update(...)`
5. si Laravel responde `422`, `errors` se llena
6. si todo sale bien, cierra modal y muestra toast
7. si el comportamiento del CRUD lo permite, actualiza `itemsById` o recarga la pagina

### 7.4 Toggle o delete

Mismo patron:

1. guardas el `id` a confirmar
2. abres modal de confirmacion
3. ejecutas accion real al confirmar
4. actualizas estado local o recargas

---

## 8. Que es `itemsById` y por que existe

Este punto es clave.

En el refactor guardamos muchos datos en forma de lookup:

```js
itemsById: {
    1: { id: 1, name: 'Admin', ... },
    2: { id: 2, name: 'Caja', ... },
}
```

En vez de andar buscando en arrays todo el tiempo, podemos hacer:

```js
this.itemsById[id]
```

Ventajas:

- mas rapido de consultar
- mas facil de actualizar
- ideal para tabla + cards + modal
- evita pasar objetos completos con `@js($user)`

Antes:

```blade
@click="openEditModal(@js($user))"
```

Ahora:

```blade
@click="openEditModal({{ $user->id }})"
```

Eso es mejor porque:

- Blade manda menos datos
- Alpine trabaja con una fuente central de verdad
- la UI se puede actualizar localmente sin reconstruir todo

---

## 9. Que hace un mapper en palabras humanas

Muchas veces el backend y la UI no quieren exactamente el mismo formato.

Ejemplo de usuario:

Backend:

```json
{
  "user": {
    "id": 3,
    "name": "Ana",
    "roles": [
      { "name": "Admin" },
      { "name": "Ventas" }
    ]
  }
}
```

Formulario Alpine necesita:

```js
{
    id: 3,
    name: 'Ana',
    roles: ['Admin', 'Ventas']
}
```

Esa conversion vive en el mapper.

La regla mental es:

> Si estas transformando datos, casi seguro eso no va en Blade ni en Api. Va en Mapper.

---

## 10. Que parte va en cada archivo

Usa esta tabla mental:

### Va en `Blade`

- estructura HTML
- `x-model`
- `x-text`
- `@click`
- config inicial desde PHP
- textos y componentes visuales

### Va en `Crud`

- metodos que la UI llama
- helpers para pintar datos
- conectar factory + api + mapper
- reglas especificas del recurso

### Va en `Api`

- `http.get/post/patch/delete`
- rutas del recurso
- nada de DOM
- nada de Alpine

### Va en `Mapper`

- `FormData`
- normalizacion de arrays
- convertir respuesta backend a objeto UI
- convertir formulario a item local

### Va en `Core`

- logica compartida por todos los CRUDs
- manejo estandar de errores
- patrones repetidos

---

## 11. Como crear un CRUD nuevo siguiendo este patron

Voy a usar `products` como ejemplo.

### Paso 1. Crear archivos

```text
resources/js/modules/products/
  productApi.js
  productMapper.js
  productCrud.js
```

### Paso 2. Crear `productApi.js`

Ejemplo:

```js
import { http } from '../../core/http';

export function createProductApi(endpoints = {}) {
    const collectionUrl = endpoints.collection ?? '/administration/products';
    const resourceUrl = endpoints.resource ?? '/administration/products';

    return {
        fetchById(id, options = {}) {
            return http.get(`${resourceUrl}/${id}`, options);
        },

        create(payload, options = {}) {
            return http.post(collectionUrl, { ...options, body: payload });
        },

        update(id, payload, options = {}) {
            return http.post(`${resourceUrl}/${id}`, { ...options, body: payload });
        },

        toggleStatus(id, options = {}) {
            return http.patch(`${resourceUrl}/${id}/toggle`, options);
        },
    };
}
```

### Paso 3. Crear `productMapper.js`

Ejemplo minimo:

```js
export const createEmptyProductForm = () => ({
    id: null,
    name: '',
    price: '',
    description: '',
});

export const mapProductResponseToForm = (data = {}) => {
    const product = data.product ?? {};

    return {
        id: product.id ?? null,
        name: product.name ?? '',
        price: product.price ?? '',
        description: product.description ?? '',
    };
};

export const buildProductPayload = ({ formData, editMode }) => {
    const payload = new FormData();
    payload.append('name', formData.name ?? '');
    payload.append('price', formData.price ?? '');
    payload.append('description', formData.description ?? '');

    if (editMode) {
        payload.append('_method', 'PUT');
    }

    return payload;
};
```

### Paso 4. Crear `productCrud.js`

Ejemplo base:

```js
import { createCrudFactory } from '../../core/crudFactory';
import { createProductApi } from './productApi';
import {
    createEmptyProductForm,
    mapProductResponseToForm,
    buildProductPayload,
} from './productMapper';

export default function productCrud(config = {}) {
    return {
        ...createCrudFactory({
            api: createProductApi(config.endpoints),
            createEmptyForm: createEmptyProductForm,
            mapDetailToForm: mapProductResponseToForm,
            buildPayload: buildProductPayload,
            filters: config.filters,
            messages: config.messages,
            modalNames: {
                form: 'product-modal',
                confirm: 'confirm-toggle-product',
            },
        }),
    };
}
```

### Paso 5. Registrar en `app.js`

```js
import productCrud from './modules/products/productCrud';

document.addEventListener('alpine:init', () => {
    Alpine.data('productCrud', productCrud);
});
```

### Paso 6. Usarlo en Blade

```blade
<div
    x-data="productCrud({
        filters: {
            search: @js($search ?? ''),
            per_page: @js($perPage ?? 10),
        },
        endpoints: {
            collection: @js(route('administration.products.store')),
            resource: @js(url('administration/products')),
        },
        messages: {
            invalidResponse: @js('La respuesta del servidor no fue valida.'),
            saveError: @js('No se pudo guardar el producto.'),
        },
    })"
>
```

---

## 12. Cuando usar `fetchById` y cuando no

No todos los CRUD necesitan endpoint `show`.

### Usa `fetchById`

Cuando:

- el item mostrado en lista no tiene todos los datos
- la edicion necesita mas campos
- hay relaciones que no quieres cargar completas en index

Ejemplo: usuarios

La lista muestra lo minimo, pero para editar conviene pedir detalle al backend.

### No uses `fetchById`

Cuando:

- ya tienes toda la informacion necesaria en la pagina
- el recurso es pequeno y simple

Ejemplo: roles

Ya tenemos `name` y `permissions` en la vista inicial, entonces `roleCrud` usa:

```js
getEditData: ({ item }) => item
```

Eso significa:

> para editar, usa el dato local y no hagas request extra

---

## 13. Como pensar las expresiones Alpine del Blade

### `x-model`

Une un input con una propiedad del estado.

```blade
<input x-model="formData.name">
```

Significa:

- si el usuario escribe, cambia `formData.name`
- si `formData.name` cambia por JS, el input se actualiza

### `x-text`

Pinta texto dinamico.

```blade
<span x-text="userName(5)"></span>
```

### `@click`

Escucha eventos.

```blade
<button @click="openEditModal(5)">
```

### `x-bind:class`

Permite clases dinamicas.

```blade
<span x-bind:class="userStatusBadgeClass(5)">
```

### `x-if`

Renderiza algo solo si se cumple una condicion.

```blade
<template x-if="errors.name">
```

### `x-for`

Renderiza listas.

```blade
<template x-for="role in userRoles(5)" :key="role">
```

---

## 14. Errores comunes al crear un nuevo CRUD

### Error 1. Meter `fetch` dentro de `productCrud.js`

Se puede, pero rompe la separacion.

Haz esto:

- `productCrud.js` coordina
- `productApi.js` consulta

### Error 2. Construir `FormData` en 4 lugares

Si el payload pertenece al recurso, dejalo en el mapper.

### Error 3. Pasar objetos enormes con `@js($model)`

Mejor:

- pasa `id` al click
- si hace falta, siembra `initialItems`
- o usa `fetchById`

### Error 4. Mezclar HTML con logica compleja

Si la expresion Alpine en Blade se empieza a poner fea, crea un helper en el `Crud`.

Mal:

```blade
x-text="formData.permissions.length === 0 ? 'Sin permisos' : formData.permissions.length + ' permisos'"
```

Mejor:

```blade
x-text="selectedPermissionsLabel()"
```

### Error 5. Duplicar mensajes y manejo de errores

Si un patron se repite entre CRUDs, preguntate si debe ir en `crudFactory.js` o `http.js`.

---

## 15. Regla simple para decidir donde poner una nueva linea de codigo

Hazte esta pregunta:

### "Esta linea habla de que?"

Si habla de:

- UI: Blade o Crud
- request HTTP: Api o http.js
- transformar datos: Mapper
- comportamiento comun entre varios CRUDs: Core

Esta sola pregunta evita muchisimo desorden.

---

## 16. Checklist rapido para refactorizar un CRUD viejo

Cuando veas una funcion Alpine grande inline:

1. Mueve el `fetch` a `resourceApi.js`
2. Mueve transformaciones a `resourceMapper.js`
3. Deja `resourceCrud.js` como adaptador para Alpine
4. Si hay logica repetida, subela a `crudFactory.js`
5. En Blade deja solo:
   - estructura visual
   - eventos
   - config inicial
6. Reemplaza `@js($model)` por `id` cuando sea posible
7. Registra el modulo en `app.js`
8. Corre `npm run build`

---

## 17. Ejemplo de lectura completa de una accion

Tomemos este boton:

```blade
<button @click="openEditModal({{ $user->id }})">
```

Para entenderlo de punta a punta, sigue este camino:

1. Blade dispara `openEditModal(id)`
2. Ese metodo existe en el objeto que retorno `userCrud(config)`
3. `userCrud` viene de [resources/js/modules/users/userCrud.js](/home/asaa/Proyectos/docker/asaa-pos/app/resources/js/modules/users/userCrud.js:1)
4. `userCrud` usa `createCrudFactory(...)`
5. `createCrudFactory` implementa `openEditModal`
6. `openEditModal` llama al API del recurso
7. la respuesta se transforma con el mapper
8. `formData` se llena
9. el modal se abre
10. los inputs muestran datos porque usan `x-model`

Si aprendes a seguir ese recorrido, ya sabes leer esta arquitectura.

---

## 18. Orden recomendado para leer el codigo cuando te pierdas

Cuando abras un CRUD y no entiendas nada, sigue este orden:

1. Blade
2. `resourceCrud.js`
3. `crudFactory.js`
4. `resourceApi.js`
5. `resourceMapper.js`

Ese orden ayuda porque:

- primero ves que quiere la UI
- luego ves que expone Alpine
- luego ves la maquinaria compartida
- por ultimo detalles de request y transformacion

---

## 19. Resumen ultra corto

Si manana tuvieras que explicarte esto a ti mismo en una nota, seria esto:

- `Blade` muestra y dispara eventos
- `x-data="algoCrud(config)"` crea el estado Alpine
- `algoCrud.js` adapta el CRUD al recurso
- `crudFactory.js` evita repetir logica comun
- `algoApi.js` hace requests
- `algoMapper.js` transforma datos
- `http.js` estandariza fetch

---

## 20. Siguiente paso recomendado

La mejor forma de aprender este patron es hacer uno nuevo.

Te recomiendo este ejercicio:

1. crear `productApi.js`
2. crear `productMapper.js`
3. crear `productCrud.js`
4. registrar `productCrud` en `app.js`
5. dejar un Blade usando `x-data="productCrud({...})"`

Si lo haces una vez con calma, ya no se siente abstracto.

---

## 21. Relacion con la guia general del proyecto

Si necesitas la parte completa de Laravel, rutas, migraciones y estructura general, revisa tambien:

[docs/crud-guide.md](/home/asaa/Proyectos/docker/asaa-pos/app/docs/crud-guide.md)

Esta guia nueva es el complemento para entender la parte frontend modular con Alpine.
