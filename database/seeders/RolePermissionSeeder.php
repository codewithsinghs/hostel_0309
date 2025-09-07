<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // 1️⃣ Clear cache first
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2️⃣ Define permissions for existing routes
        $permissions = [
            // Reports
            'reports.view', 'reports.create', 'reports.edit', 'reports.delete', 'reports.toggle-status',

            // Blogs
            'blogs.view', 'blogs.create', 'blogs.edit', 'blogs.delete', 'blogs.toggle-status',

            // Posts
            'posts.view', 'posts.create', 'posts.edit', 'posts.delete', 'posts.toggle-status',

            // Events
            'events.view', 'events.create', 'events.edit', 'events.delete', 'events.toggle-status',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3️⃣ Define roles
        $roles = [
            'admin' => $permissions, // admin gets all
            'editor' => [
                'reports.view', 'blogs.view', 'blogs.create', 'blogs.edit', 'posts.view', 'posts.create', 'posts.edit',
            ],
            'viewer' => [
                'reports.view', 'blogs.view', 'posts.view', 'events.view',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        $this->command->info('Roles & Permissions seeded successfully.');
    }
}
