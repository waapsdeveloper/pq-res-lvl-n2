<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;

class SandboxRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- Manager Role ---
        $managerRole = Role::firstOrCreate(
            ['slug' => 'manager'],
            ['name' => 'Manager', 'slug' => 'manager']
        );

        // Remove all previous permissions for manager
        \App\Models\Permission::where('role_id', $managerRole->id)->delete();

        // Manager permissions (only these)
        $managerPermissions = [
            ['entity' => 'dashboard', 'operations' => ['view']],
            ['entity' => 'product', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'category', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'variation', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'expense_category', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'expense', 'operations' => ['add', 'view', 'edit', 'update', 'delete', 'list', 'filter', 'status', 'payment_status_update']],
        ];

        foreach ($managerPermissions as $perm) {
            foreach ($perm['operations'] as $operation) {
                Permission::firstOrCreate([
                    'role_id' => $managerRole->id,
                    'slug'    => "{$perm['entity']}.{$operation}",
                ], [
                    'level'   => 2,
                ]);
            }
        }

        // Create Manager user
        User::firstOrCreate(
            ['email' => 'manager@email.com'],
            [
                'name' => 'Manager User',
                'email' => 'manager@email.com',
                'phone' => '9999999991',
                'password' => bcrypt('manager123$'),
                'role_id' => $managerRole->id,
                'status' => 'active',
                'restaurant_id' => 1,
                'image' => 'images/user/user-manager.png',
                'dial_code' => '+1'
            ]
        );

        // --- Saleman Role ---
        $salemanRole = Role::firstOrCreate(
            ['slug' => 'saleman'],
            ['name' => 'Saleman', 'slug' => 'saleman']
        );

        // Saleman permissions
        $salemanPermissions = [
            ['entity' => 'dashboard', 'operations' => ['view']],
            ['entity' => 'order', 'operations' => ['add', 'edit', 'delete', 'list', 'filter', 'payment_status', 'order_status', 'menu', 'deleted_order', 'history']],
            ['entity' => 'report', 'operations' => ['view', 'daily_sale_report', 'yearly', 'custom','daily_product_report']],
        ];

        foreach ($salemanPermissions as $perm) {
            foreach ($perm['operations'] as $operation) {
                Permission::firstOrCreate([
                    'role_id' => $salemanRole->id,
                    'slug'    => "{$perm['entity']}.{$operation}",
                ], [
                    'level'   => 2,
                ]);
            }
        }

        // Create Saleman user
        User::firstOrCreate(
            ['email' => 'saleman@email.com'],
            [
                'name' => 'Saleman User',
                'email' => 'saleman@email.com',
                'phone' => '9999999992',
                'password' => bcrypt('saleman123$'),
                'role_id' => $salemanRole->id,
                'status' => 'active',
                'restaurant_id' => 1,
                'image' => 'images/user/user-saleman.png',
                'dial_code' => '+1'
            ]
        );
    }
}
