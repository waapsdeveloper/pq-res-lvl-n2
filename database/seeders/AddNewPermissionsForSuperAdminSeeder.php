<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class AddNewPermissionsForSuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::pluck('id', 'slug')->toArray();

        if (!isset($roles['super-admin'])) {
            return; // skip if no super-admin role exists
        }

        $roleId = $roles['super-admin'];

        $newPermissions = [
            ['entity' => 'report', 'operations' => ['view', 'daily_sale_report', 'yearly', 'custom', 'weekly']],
            ['entity' => 'order', 'operations' => ['deleted_order','history']],
            ['entity' => 'role', 'operations' => ['filter']],
        ];

        foreach ($newPermissions as $perm) {
            foreach ($perm['operations'] as $operation) {
                Permission::firstOrCreate([
                    'role_id' => $roleId,
                    'slug'    => "{$perm['entity']}.{$operation}",
                ], [
                    'level' => 2,
                ]);
            }
        }
    }
}
