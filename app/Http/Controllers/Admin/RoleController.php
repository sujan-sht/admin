<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\Admin\RoleRepositoryInterface;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    protected $roleRepositoryInterface;

    public function __construct(RoleRepositoryInterface $roleRepositoryInterface)
    {
        $this->roleRepositoryInterface = $roleRepositoryInterface;
        $this->authorizeResource(Role::class, 'role');

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = $this->roleRepositoryInterface->indexRole();
        return Inertia::render('admin/Role/Index', [
            'roles' => RoleResource::collection($roles['roles'])->resolve()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('admin/Role/Form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $this->roleRepositoryInterface->storeRole($request);
        return to_route('roles.index')->with('message','Role Successfully Added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return Inertia::render('admin/Role/View', $this->roleRepositoryInterface->showRole($role));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return Inertia::render('admin/Role/Form', [
            'role' => $this->roleRepositoryInterface->editRole($role)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role)
    {
        $this->roleRepositoryInterface->updateRole($request, $role);
        return to_route('roles.index')->with('info','Role Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->roleRepositoryInterface->destroyRole($role);
        return to_route('roles.index')->with('success','Role Deleted Successfully');
    }
}
