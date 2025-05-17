<?php

namespace App\Contracts\Admin;

use App\Models\Admin\Role;
use App\Http\Requests\Admin\RoleRequest;

interface RoleRepositoryInterface
{
    public function indexRole();

    public function createRole();

    public function storeRole(RoleRequest $request);

    public function showRole(Role $role);

    public function editRole(Role $role);

    public function updateRole(RoleRequest $request, Role $role);

    public function destroyRole(Role $role);
}
