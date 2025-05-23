<?php


namespace App\Contracts\Admin;

use App\Models\Admin\Permission;
use App\Http\Requests\Admin\PermissionRequest;

interface PermissionRepositoryInterface
{
    public function indexPermission();

    public function createPermission();

    public function storePermission(PermissionRequest $request);

    public function showPermission(Permission $permission);

    public function editPermission(Permission $permission);

    public function updatePermission(PermissionRequest $request, Permission $permission);

    public function destroyPermission(Permission $permission);
}
