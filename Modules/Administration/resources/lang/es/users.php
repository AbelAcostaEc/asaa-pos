<?php

// Modules/Administration/resources/lang/es/users.php
// Textos específicos del CRUD de Usuarios.

return [

    // ── Página ───────────────────────────────────────────────────────
    'page_title'      => 'Gestión de Usuarios',
    'list_title'      => 'Lista de Usuarios',
    'found'           => ':count usuarios encontrados',

    // ── Toolbar ──────────────────────────────────────────────────────
    'new_user'        => 'Nuevo Usuario',
    'search_ph'       => 'Buscar por nombre o email...',

    // ── Tabla ─────────────────────────────────────────────────────────
    'col_id'          => 'ID',
    'col_name'        => 'Nombre',
    'col_email'       => 'Email',
    'col_roles'       => 'Roles',
    'col_status'      => 'Estado',
    'col_updated'     => 'Última Act.',
    'col_actions'     => 'Acciones',
    'no_users'        => 'No se encontraron usuarios.',
    'updated'         => 'Actualizado',

    // ── Modal crear / editar ──────────────────────────────────────────
    'modal_create'    => 'Nuevo Usuario',
    'modal_edit'      => 'Editar Usuario',
    'field_name'      => 'Nombre Completo',
    'field_name_ph'   => 'Ej. Juan Pérez',
    'field_email'     => 'Correo Electrónico',
    'field_email_ph'  => 'juan@example.com',
    'field_password'  => 'Contraseña',
    'field_password_ph_create' => 'Mínimo 8 caracteres',
    'field_password_ph_edit'   => 'Dejar en blanco para mantener',
    'field_roles'     => 'Asignar Roles',
    'password_hint'   => 'Solo completa si deseas cambiar la contraseña.',
    'btn_create'      => 'Crear Usuario',
    'btn_update'      => 'Actualizar',

    // ── Modal confirmar estado ────────────────────────────────────────
    'confirm_disable_title' => '¿Deshabilitar Usuario?',
    'confirm_enable_title'  => '¿Habilitar Usuario?',
    'confirm_body'          => 'Esta acción cambiará el estado de acceso del usuario al sistema.',
    'btn_confirm_disable'   => 'Confirmar Deshabilitar',
    'btn_confirm_enable'    => 'Confirmar Habilitar',

    // ── Mensajes ──────────────────────────────────────────────────────
    'msg_invalid_response' => 'La respuesta del servidor no fue válida.',
    'msg_save_error'       => 'No se pudo guardar el usuario.',

];
