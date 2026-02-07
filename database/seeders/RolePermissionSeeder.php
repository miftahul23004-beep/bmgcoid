<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Role management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            
            // Content management
            'view articles',
            'create articles',
            'edit articles',
            'delete articles',
            
            'view products',
            'create products',
            'edit products',
            'delete products',
            
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            
            'view testimonials',
            'create testimonials',
            'edit testimonials',
            'delete testimonials',
            
            'view clients',
            'create clients',
            'edit clients',
            'delete clients',
            
            'view inquiries',
            'manage inquiries',
            
            // Settings
            'manage settings',
            'manage hero slides',
            'manage homepage sections',
            'manage seo',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin - has all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin - can manage content and view users
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo([
            'view users',
            'view roles',
            'view articles', 'create articles', 'edit articles', 'delete articles',
            'view products', 'create products', 'edit products', 'delete products',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view testimonials', 'create testimonials', 'edit testimonials', 'delete testimonials',
            'view clients', 'create clients', 'edit clients', 'delete clients',
            'view inquiries', 'manage inquiries',
            'manage settings',
            'manage hero slides',
            'manage homepage sections',
            'manage seo',
        ]);

        // Editor - can only manage content
        $editor = Role::firstOrCreate(['name' => 'Editor']);
        $editor->givePermissionTo([
            'view articles', 'create articles', 'edit articles',
            'view products', 'create products', 'edit products',
            'view categories',
            'view testimonials', 'create testimonials', 'edit testimonials',
            'view clients',
            'view inquiries',
        ]);

        // Assign Super Admin role to first user
        $firstUser = User::first();
        if ($firstUser && !$firstUser->hasRole('Super Admin')) {
            $firstUser->assignRole('Super Admin');
            $this->command->info("Super Admin role assigned to: {$firstUser->email}");
        }
    }
}
