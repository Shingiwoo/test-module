<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        //permission for roles
        Permission::create(['name' => 'roles.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'roles.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'roles.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'roles.delete', 'guard_name' => 'api']);

        //permission for permissions
        Permission::create(['name' => 'permissions.index', 'guard_name' => 'api']);

        //permission for posts
        Permission::create(['name' => 'tasks.index', 'guard_name' => 'api']);
        Permission::create(['name' => 'tasks.create', 'guard_name' => 'api']);
        Permission::create(['name' => 'tasks.edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'tasks.delete', 'guard_name' => 'api']);
    }
}
