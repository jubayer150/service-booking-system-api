<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('roles')->exists() || DB::table('permissions')->exists()) {
            return;
        }

        $rolesWithPermissions = [
            RoleName::ADMIN->value => [
                'permissions' => [
                    'service' => ['viewAll', 'view', 'create', 'update', 'delete'],
                    'booking' => ['viewAny', 'view', 'update', 'delete'],
                ],
            ],
            RoleName::CUSTOMER->value => [
                'permissions' => [
                    'service' => ['viewAny', 'view'],
                    'booking' => ['viewAny', 'view', 'create', 'update', 'delete'],
                ],
            ],
        ];

        $now = now();

        /**
         * Step 1: Collect all unique permissions
         */
        $allPermissionNames = [];

        foreach ($rolesWithPermissions as $roleData) {
            foreach ($roleData['permissions'] as $group => $actions) {
                foreach ($actions as $action) {
                    $allPermissionNames[] = "{$group}.{$action}";
                }
            }
        }

        $allPermissionNames = array_unique($allPermissionNames);

        $allPermissions = array_map(fn ($name) => [
            'name'       => $name,
            'guard_name' => config('auth.defaults.guard'),
            'created_at' => $now,
            'updated_at' => $now,
        ], $allPermissionNames);

        DB::table('permissions')->insert($allPermissions);

        /**
         * Step 2: Fetch permissions from DB
         */
        $permissions = DB::table('permissions')->pluck('id', 'name')->toArray();

        /**
         * Step 3: Create roles and assign permissions
         */
        foreach ($rolesWithPermissions as $roleKey => $roleData) {
            $role = Role::create([
                'name'  => $roleKey,
                'guard_name' => config('auth.defaults.guard'),

            ]);

            $rolePermissionNames = [];

            foreach ($roleData['permissions'] as $group => $actions) {
                foreach ($actions as $action) {
                    $rolePermissionNames[] = "{$group}.{$action}";
                }
            }

            $rolePermissionIds = array_filter(array_map(
                fn ($name) => $permissions[$name] ?? null,
                $rolePermissionNames
            ));

            $role->syncPermissions($rolePermissionIds);
        }
    }
}
