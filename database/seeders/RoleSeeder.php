<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage_users',
            'manage_settings',
            'export_website',
            'publish_posts',
            'edit_all_posts',
            'edit_own_posts',
            'manage_pages',
            'manage_media',
            'manage_categories',
            'manage_tags',
            'view_dashboard',
            'view_exports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Super Admin — full access
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions($permissions);

        // Admin — manage content + export, limited user management
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'export_website',
            'publish_posts',
            'edit_all_posts',
            'edit_own_posts',
            'manage_pages',
            'manage_media',
            'manage_categories',
            'manage_tags',
            'view_dashboard',
            'view_exports',
        ]);

        // Editor — review and publish
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $editor->syncPermissions([
            'publish_posts',
            'edit_all_posts',
            'edit_own_posts',
            'manage_media',
            'manage_categories',
            'manage_tags',
            'view_dashboard',
        ]);

        // Author — create own content only
        $author = Role::firstOrCreate(['name' => 'author']);
        $author->syncPermissions([
            'edit_own_posts',
            'manage_media',
            'view_dashboard',
        ]);
    }
}
