<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'View',
                'slug' => 'view',
            ],
            [
                'name' => 'Create',
                'slug' => 'create',
            ],
            [
                'name' => 'Update',
                'slug' => 'update',
            ],
            [
                'name' => 'Delete',
                'slug' => 'delete',
            ],
        ];

        foreach ($permissions as $permissionItem) {
            Permission::updateOrCreate(
                ['name' => $permissionItem['name']],
                ['slug' => $permissionItem['slug']]
            );
        }

        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor',
            ],
            [
                'name' => 'Viewer',
                'slug' => 'viewer',
            ],
        ];

        foreach ($roles as $roleItem) {
            $role = Role::updateOrCreate(
                ['name' => $roleItem['name']],
                ['slug' => $roleItem['slug']]
            );

            $permissions = [];

            if ($role->slug == 'admin') {
                $permissions = ['view', 'create', 'update', 'delete'];
            } elseif ($role->slug == 'editor') {
                $permissions = ['view', 'create', 'update'];
            } elseif ($role->slug == 'viewer') {
                $permissions = ['view'];
            }

            if (!empty($permissions)){
                $attachedPermissions = Permission::whereIn('slug', $permissions)
                    ->pluck('id')
                    ->toArray();
                $role->permissions()->syncWithoutDetaching($attachedPermissions);
            }
        }
    }
}
