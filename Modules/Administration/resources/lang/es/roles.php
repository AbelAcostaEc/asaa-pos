<?php

// Modules/Administration/resources/lang/es/roles.php
// Textos específicos del CRUD de Roles.

return [

    // ── Página ───────────────────────────────────────────────────────
    'page_title'      => 'Gestión de Roles',
    'list_title'      => 'Lista de Roles',
    'found'           => ':count roles encontrados',

    // ── Toolbar ──────────────────────────────────────────────────────
    'new_role'        => 'Nuevo Rol',
    'search_ph'       => 'Buscar por nombre...',

    // ── Tabla ─────────────────────────────────────────────────────────
    'col_id'          => 'ID',
    'col_name'        => 'Nombre del Rol',
    'col_permissions' => 'Permisos',
    'col_updated'     => 'Última Act.',
    'col_actions'     => 'Acciones',
    'no_roles'        => 'No se encontraron roles.',
    'updated'         => 'Actualizado',

    // ── Modal crear / editar ──────────────────────────────────────────
    'modal_create'    => 'Nuevo Rol',
    'modal_edit'      => 'Editar Rol',
    'field_name'      => 'Nombre del Rol',
    'field_name_ph'   => 'Ej. Administrador, Vendedor',
    'field_permissions' => 'Asignar Permisos',
    'btn_create'      => 'Crear Rol',
    'btn_update'      => 'Actualizar',
    'btn_confirm_delete' => 'Confirmar Eliminación',

    // ── Modal confirmar eliminar ─────────────────────────────────────
    'confirm_delete_title' => '¿Eliminar Rol?',
    'confirm_delete_body'  => 'Esta acción eliminará el rol de forma permanente. No se puede deshacer.',

    // ── Permisos (Opcional, etiquetas amigables) ─────────────────────
    'permission_all'  => 'Todos los permisos',
    'permission_none' => 'Sin permisos',
    'permission_single_selected' => '1 permiso seleccionado',
    'permission_selected' => ':count permisos seleccionados',
    'permissions_count' => '{0} Sin permisos|{1} :count permiso|[2,*] :count permisos',
    'badge_protected' => 'Protegido',

    // ── Mensajes ──────────────────────────────────────────────────────
    'msg_created' => 'Rol creado exitosamente.',
    'msg_updated' => 'Rol actualizado exitosamente.',
    'msg_deleted' => 'Rol eliminado exitosamente.',
    'msg_delete_forbidden' => 'No se puede eliminar el rol Super Admin.',
    'msg_invalid_response' => 'La respuesta del servidor no fue válida.',
    'msg_save_error' => 'No se pudo guardar el rol.',
    'msg_delete_error' => 'No se pudo eliminar el rol.',
];
