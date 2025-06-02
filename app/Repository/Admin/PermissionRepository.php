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
        $roles = Role::where('name', '!=', 'Super Admin')
             ->get()
             ->map(function($role) {
                 return [
                     'id' => $role->id,
                     'name' => $role->name
                 ];
             })->values()->toArray();

        return $roles;
    }

    // Permission Store
    public function storePermission(PermissionRequest $request)
    {
        Permission::create($request->validated());
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
