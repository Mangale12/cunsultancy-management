<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create or update super admin user
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('nepal@123'),
                'email_verified_at' => now(),
            ]
        );

        // Get or create super admin role
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin'],
            [
                'guard_name' => 'web',
                'description' => 'Super Administrator with full system access',
            ]
        );

        // Assign all permissions to super admin role
        $allPermissions = Permission::all();
        $superAdminRole->syncPermissions($allPermissions);

        // Assign role to user
        $superAdmin->assignRole($superAdminRole);

        $this->command->info('✅ Super Admin user created successfully!');
        $this->command->info('📧 Email: superadmin@gmail.com');
        $this->command->info('🔑 Password: nepal@123');
        $this->command->info('🔐 Permissions assigned: ' . $allPermissions->count());
    }
}
