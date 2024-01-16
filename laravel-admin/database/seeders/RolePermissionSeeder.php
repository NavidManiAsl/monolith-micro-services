<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::whereName('admin')->first();
        $permissions = Permission::all();
        foreach ($permissions as $permission) {

            DB::table('role_permission')->insert(
                [
                    'role_id' => $admin->id,
                    'permission_id' => $permission->id
                ]

            );
        }

        $editor = Role::whereName('editor')->first();
        foreach ($permissions as $permission) {
            if ($permission->name !== 'edit_roles') {
                DB::table('role_permission')->insert(
                    [
                        'role_id' => $editor->id,
                        'permission_id' => $permission->id
                    ]

                );
            }
        }
        $viewer = Role::whereName('editor')->first();
        $viewerPermissions = [
            'view_products',
            'view_roles',
            'view_users',
            'view_orders'
        ];
        foreach ($permissions as $permission) {
            if (in_array($permission->name, $viewerPermissions)) {
                DB::table('role_permission')->insert(
                    [
                        'role_id' => $viewer->id,
                        'permission_id' => $permission->id
                    ]

                );
            }
        }
    }


}
