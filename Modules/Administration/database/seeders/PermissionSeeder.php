<?php

namespace Modules\Administration\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'administration.access',
            
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.toggle',

            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Create initial Super Admin role and assign all permissions
        $role = Role::findOrCreate('Super Admin');
        $role->givePermissionTo(Permission::all());
    }
}
