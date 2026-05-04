<?php
// database/seeders/RolesAndPermissionsSeeder.php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Permissions
        $permissions = [
            ['name' => 'projects.view',      'label' => 'View Projects',      'group' => 'Projects'],
            ['name' => 'projects.create',    'label' => 'Create Projects',    'group' => 'Projects'],
            ['name' => 'projects.edit',      'label' => 'Edit Projects',      'group' => 'Projects'],
            ['name' => 'projects.delete',    'label' => 'Delete Projects',    'group' => 'Projects'],

            ['name' => 'asset_types.view',   'label' => 'View Asset Types',   'group' => 'Asset Types'],
            ['name' => 'asset_types.create', 'label' => 'Create Asset Types', 'group' => 'Asset Types'],
            ['name' => 'asset_types.edit',   'label' => 'Edit Asset Types',   'group' => 'Asset Types'],
            ['name' => 'asset_types.delete', 'label' => 'Delete Asset Types', 'group' => 'Asset Types'],

            ['name' => 'campaigns.view',     'label' => 'View Campaigns',     'group' => 'Campaigns'],
            ['name' => 'campaigns.create',   'label' => 'Create Campaigns',   'group' => 'Campaigns'],
            ['name' => 'campaigns.edit',     'label' => 'Edit Campaigns',     'group' => 'Campaigns'],
            ['name' => 'campaigns.delete',   'label' => 'Delete Campaigns',   'group' => 'Campaigns'],

            ['name' => 'assets.view',        'label' => 'View Assets',        'group' => 'Assets'],
            ['name' => 'assets.create',      'label' => 'Create Assets',      'group' => 'Assets'],
            ['name' => 'assets.edit',        'label' => 'Edit Assets',        'group' => 'Assets'],
            ['name' => 'assets.delete',      'label' => 'Delete Assets',      'group' => 'Assets'],

            ['name' => 'users.view',         'label' => 'View Users',         'group' => 'Users'],
            ['name' => 'users.create',       'label' => 'Create Users',       'group' => 'Users'],
            ['name' => 'users.edit',         'label' => 'Edit Users',         'group' => 'Users'],
            ['name' => 'users.delete',       'label' => 'Delete Users',       'group' => 'Users'],

            ['name' => 'roles.view',         'label' => 'View Roles',         'group' => 'Roles'],
            ['name' => 'roles.create',       'label' => 'Create Roles',       'group' => 'Roles'],
            ['name' => 'roles.edit',         'label' => 'Edit Roles',         'group' => 'Roles'],
            ['name' => 'roles.delete',       'label' => 'Delete Roles',       'group' => 'Roles'],

            ['name' => 'activity_logs.view', 'label' => 'View Activity Logs', 'group' => 'Logs'],
            ['name' => 'settings.manage',    'label' => 'Manage Settings',    'group' => 'Settings'],
            ['name' => 'dashboard.view',    'label' => 'Dashboard',    'group' => 'dashboard'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        // Super Admin role
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin'],
            ['label' => 'Super Admin', 'is_super_admin' => true]
        );

        // Super Admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );

        // Role assign
        $user->roles()->syncWithoutDetaching($superAdminRole->id);
    }
}
