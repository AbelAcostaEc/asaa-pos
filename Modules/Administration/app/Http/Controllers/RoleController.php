<?php

namespace Modules\Administration\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $roles = Role::query()
            ->with('permissions')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->appends($request->query());

        $permissions = Permission::query()->orderBy('name')->get();

        return view('administration::roles.index', compact('roles', 'permissions', 'search', 'perPage'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $validated['name']]);
        
        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return response()->json([
            'success' => true,
            'message' => __('administration::roles.msg_created'),
            'role' => $role->load('permissions'),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->name = $validated['name'];
        $role->save();
        
        $role->syncPermissions($validated['permissions'] ?? []);

        return response()->json([
            'success' => true,
            'message' => __('administration::roles.msg_updated'),
            'role' => $role->load('permissions'),
        ]);
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return response()->json([
                'success' => false,
                'message' => __('administration::roles.msg_delete_forbidden'),
            ], 403);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => __('administration::roles.msg_deleted'),
        ]);
    }
}
