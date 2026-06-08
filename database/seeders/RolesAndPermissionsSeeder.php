<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions with groups
        $permissionGroups = [
            'Role Management' => [
                'manage roles',
            ],
            'Shop Management' => [
                'approve shops',
            ],
            'Revenue & Payments' => [
                'view payments',
            ],
            'Agency Management' => [
                'manage agencies',
            ],
            'User Management' => [
                'users view',
                'users create',
                'users edit',
                'users delete',
                'users status',
                'users logs',
                'users security',
                'users reset password',
                'users reset 2fa',
                'users verify',
                'users email',
                'users unlock',
            ],
        ];

        foreach ($permissionGroups as $group => $perms) {
            foreach ($perms as $permission) {
                Permission::findOrCreate($permission, 'web')->update(['group' => $group]);
            }
        }

        // Create roles and assign permissions
        $adminRole = Role::findOrCreate('super-admin');
        $adminRole->givePermissionTo(Permission::all());

        $officerRole = Role::findOrCreate('field-officer');
        $officerRole->givePermissionTo(['view payments']);

        // User creation is now handled in DatabaseSeeder.php
    }
}
