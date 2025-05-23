<?php

namespace Database\Seeders;

use App\Models\Admin\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dummyRoles = [
            ['name'=>'Super Admin','description'=>'This is a super admin.'],
            ['name'=>'Admin','description'=>'This is a admin.'],
            ['name'=>'Editor','description'=>'This is a editor.'],
            ['name'=>'User','description'=>'This is a user.'],

        ];

        Role::insert($dummyRoles);
    }
}
