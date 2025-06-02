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
        // Example roles (replace with your actual role IDs)
        $roles = Role::pluck('name', 'id')->toArray();

        $permissions = [
            ['entity' => 'user', 'operations' => ['add', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'product', 'operations' => ['add', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'category', 'operations' => ['add', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'variation', 'operations' => ['add', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'table', 'operations' => ['add', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'table_booking', 'operations' => ['add', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'expense_category', 'operations' => ['add', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'coupon', 'operations' => ['add', 'update', 'delete', 'list', 'filter']],
            ['entity' => 'message', 'operations' => ['add', 'update', 'delete', 'list', 'filter']],
        ];

        // Usage:
        foreach ($roles as $role) {
            foreach ($permissions as $perm) {
                foreach ($perm['operations'] as $operation) {
                    
                    Permission::firstOrCreate([
                        'role_id' => $role->id,
                        'slug'    => "{$perm['entity']}.{$operation}",
                    ], [
                        'level'   => 2,
                    ]);





                }
            }
        }
    }
}
