<?php

// Modules/Administration/resources/lang/en/roles.php
// English translations for the Roles CRUD.

return [

    // Page
    'page_title'      => 'Role Management',
    'list_title'      => 'Role List',
    'found'           => ':count roles found',

    // Toolbar
    'new_role'        => 'New Role',
    'search_ph'       => 'Search by role name...',

    // Table
    'col_id'          => 'ID',
    'col_name'        => 'Role Name',
    'col_permissions' => 'Permissions',
    'col_updated'     => 'Last Updated',
    'col_actions'     => 'Actions',
    'no_roles'        => 'No roles found.',
    'updated'         => 'Updated',

    // Create / Edit Modal
    'modal_create'    => 'New Role',
    'modal_edit'      => 'Edit Role',
    'field_name'      => 'Role Name',
    'field_name_ph'   => 'e.g. Administrator, Seller',
    'field_permissions' => 'Assign Permissions',
    'btn_create'      => 'Create Role',
    'btn_update'      => 'Update',
    'btn_confirm_delete' => 'Confirm Delete',

    // Confirm Delete Modal
    'confirm_delete_title' => 'Delete Role?',
    'confirm_delete_body'  => 'This action will permanently delete the role. It cannot be undone.',

    // Permissions
    'permission_all'  => 'All permissions',
    'permission_none' => 'No permissions',
    'permission_single_selected' => '1 permission selected',
    'permission_selected' => ':count permissions selected',
    'permissions_count' => '{0} No permissions|{1} :count permission|[2,*] :count permissions',
    'badge_protected' => 'Protected',

    // Messages
    'msg_created' => 'Role created successfully.',
    'msg_updated' => 'Role updated successfully.',
    'msg_deleted' => 'Role deleted successfully.',
    'msg_delete_forbidden' => 'The Super Admin role cannot be deleted.',
    'msg_invalid_response' => 'The server response was invalid.',
    'msg_save_error' => 'The role could not be saved.',
    'msg_delete_error' => 'The role could not be deleted.',
];
