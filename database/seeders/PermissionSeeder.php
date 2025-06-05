<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles as [slug => id]
        $roles = \App\Models\Role::pluck('id', 'slug')->toArray();

        $permissions = [
            ['entity' => 'role', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'user', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'product', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'category', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'variation', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'table', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'table_booking', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'expense_category', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'expense', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter', 'status', 'payment_status_update']],
            ['entity' => 'coupon', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'message', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'order', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter', 'payment_status', 'order_status', 'menu']],
            ['entity' => 'branch', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter', 'set_default', 'config_button']],
        ];

        // Map which roles get which entities' permissions
        $rolePermissions = [
            'super-admin' => ['*'], // All permissions
            'admin'       => ['user', 'product', 'category', 'variation', 'table', 'table_booking', 'expense_category', 'expense', 'coupon', 'message', 'order', 'branch'],
            'manager'     => ['user','product', 'category', 'variation', 'table', 'table_booking', 'expense_category', 'expense', 'coupon', 'order', 'branch'],
            'chef'        => ['product', 'order','category', 'variation'],
            'waiter'      => ['order', 'table_booking','table'],
            'cashier'     => ['order', 'expense', 'coupon'],
            'delivery-boy'=> ['order'],
            'receptionist'=> ['table_booking', 'order'],
            'cleaner'     => [],
            'customer'    => [],
        ];

        foreach ($rolePermissions as $roleSlug => $allowedEntities) {
            if (!isset($roles[$roleSlug])) continue;
            $roleId = $roles[$roleSlug];

            foreach ($permissions as $perm) {
                // Super Admin gets all permissions
                if ($allowedEntities === ['*'] || in_array($perm['entity'], $allowedEntities)) {
                    foreach ($perm['operations'] as $operation) {
                        Permission::firstOrCreate([
                            'role_id' => $roleId,
                            'slug'    => "{$perm['entity']}.{$operation}",
                        ], [
                            'level'   => 2,
                        ]);
                    }
                }
            }
        }
    }
}
