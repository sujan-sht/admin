<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Admin\Permission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Contracts\Admin\PermissionRepositoryInterface;
use App\Http\Resources\PermissionResource;

class PermissionController extends Controller
{
    protected $permissionRepositoryInterface;

    public function __construct(PermissionRepositoryInterface $permissionRepositoryInterface)
    {
        $this->permissionRepositoryInterface = $permissionRepositoryInterface;
        $this->authorizeResource(Permission::class, 'permission');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = $this->permissionRepositoryInterface->indexPermission();
        return Inertia::render('admin/Permission/Index', [
            'permissions' => PermissionResource::collection($permissions['permissions'])->resolve()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('admin/Permission/Form', [
            'roles' => $this->permissionRepositoryInterface->createPermission()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {
        $this->permissionRepositoryInterface->storePermission($request);
        return to_route('permissions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        return view('admin-master::admin.permission.show', $this->permissionRepositoryInterface->showPermission($permission));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {

         return Inertia::render('admin/Permission/Form', [
            'roles' => $this->permissionRepositoryInterface->createPermission(),
            'permission' => $permission
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, Permission $permission)
    {
        $this->permissionRepositoryInterface->updatePermission($request, $permission);
        return to_route('permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $this->permissionRepositoryInterface->destroyPermission($permission);
        return back();
    }

    public function makeModulePermission(PermissionRequest $request)
    {
        Permission::create([
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'role_id' => $request->role_id,
            'model' => $request->model,
        ]);
        return back();
    }

    public function updateModulePermission(Request $request, Permission $permission)
    {
        $request->validate([
            'browse' => 'boolean',
            'read' => 'boolean',
            'edit' => 'boolean',
            'add' => 'boolean',
            'delete' => 'boolean',
        ]);
        $permission->update($request->only(['browse', 'read', 'edit', 'add', 'delete']));
        return back();
    }
}
