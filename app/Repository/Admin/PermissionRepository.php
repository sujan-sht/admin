<?php

namespace App\Repository\Admin;

use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use App\Http\Requests\Admin\PermissionRequest;
use App\Contracts\Admin\PermissionRepositoryInterface;

class PermissionRepository implements PermissionRepositoryInterface
{
    // Permission Index
    public function indexPermission()
    {
        $permissions = Permission::whereHas('role', function ($query) {
            $query->where('name', '!=', 'Super Admin');
        })->get();
        return compact('permissions');
    }

    // Permission Create
    public function createPermission()
    {
        $roles = Role::all();
        return compact('roles');
    }

    // Permission Store
    public function storePermission(PermissionRequest $request)
    {
        $request->validated();
        Permission::create([
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'role_id' => $request->role_id,
            'name' => $request->name,
            'model' => 'all'
        ]);
    }

    // Permission Show
    public function showPermission(Permission $permission)
    {
        return compact('permission');
    }

    // Permission Edit
    public function editPermission(Permission $permission)
    {
        $roles = Role::all();
        return compact('permission','roles');
    }

    // Permission Update
    public function updatePermission(PermissionRequest $request, Permission $permission)
    {
        $permission->update($request->validated());
    }

    // Permission Destroy
    public function destroyPermission(Permission $permission)
    {
        $permission->delete();
    }
}
