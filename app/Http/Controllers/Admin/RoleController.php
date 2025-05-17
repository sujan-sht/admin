<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\Admin\RoleRepositoryInterface;
use App\Http\Requests\Admin\RoleRequest;
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
        return Inertia::render('admin/Role/Index', $this->roleRepositoryInterface->indexRole());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('admin/Role/Form', [
            'mode' => 'Create'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $this->roleRepositoryInterface->storeRole($request);
        return to_route('roles.index')->with('message','Successfully Added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
