<?php

namespace App\Repository\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Models\Admin\Role;
use App\Http\Requests\Admin\UserRequest;
use App\Contracts\Admin\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    // User Index
    public function indexUser()
    {
        $users = Cache::has('users') ? Cache::get('users') : Cache::rememberForever('users', function () {
            return User::with('roles')->get();
        });
       return $users;
    }

    // User Create
    public function createUser()
    {
        $roles = Role::select('id','name')->get();
        return compact('roles');
    }

    // User Store
    public function storeUser(UserRequest $request)
    {
        $request->validated();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->attach($request->role);
        // $this->uploadImage($user);
    }

    // User Show
    public function showUser(User $user)
    {
        return compact('user');
    }

    // User Edit
    public function editUser(User $user)
    {
        $roles = Role::select('id','name')->get();
        return compact('roles');
    }

    // User Update
    public function updateUser(UserRequest $request, User $user)
    {
        if($request->password){
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        }else{
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $user->password,
            ]);
        }

        $user->roles()->sync($request->role);
    }

    // User Destroy
    public function destroyUser(User $user)
    {
        if($user->delete()){
            $user->roles()->detach();
        }
    }


    // Upload Image
    private function uploadImage(User $user)
    {
        if (request()->has('image')) {
            $user
                ->addMediaFromRequest('image')
                ->toMediaCollection('image');
        }
    }
}
