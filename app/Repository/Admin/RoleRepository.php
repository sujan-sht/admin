<?php

namespace App\Repository\Admin;


use App\Models\Admin\Role;
use App\Http\Requests\Admin\RoleRequest;
use App\Contracts\Admin\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    // Role Index
    public function indexRole()
    {
        $roles = Role::all();
        return compact('roles');
    }

    // Role Create
    public function createRole()
    {
        //
    }

    // Role Store
    public function storeRole(RoleRequest $request)
    {
        Role::create($request->validated());
    }

    // Role Show
    public function showRole(Role $role)
    {

        $role->load('permissions');
        $moduleOptions = getAllUniqueModelNames($role);
        return ['role' => $role, 'modules' => $moduleOptions];
    }

    // Role Edit
    public function editRole(Role $role)
    {
        return compact('role');
    }

    // Role Update
    public function updateRole(RoleRequest $request, Role $role)
    {
        $role->update($request->validated());
    }

    // Role Destroy
    public function destroyRole(Role $role)
    {
        $role->delete();
    }
}
