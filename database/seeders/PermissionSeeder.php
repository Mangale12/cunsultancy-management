<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.manage.roles',
            
            // Role Management
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'roles.manage.permissions',
            
            // Country Management
            'countries.view',
            'countries.create',
            'countries.edit',
            'countries.delete',
            
            // State Management
            'states.view',
            'states.create',
            'states.edit',
            'states.delete',
            
            // Branch Management
            'branches.view',
            'branches.create',
            'branches.edit',
            'branches.delete',
            
            // Employee Management
            'employees.view',
            'employees.create',
            'employees.edit',
            'employees.delete',
            
            // Agent Management
            'agents.view',
            'agents.create',
            'agents.edit',
            'agents.delete',
            
            // University Management
            'universities.view',
            'universities.create',
            'universities.edit',
            'universities.delete',
            
            // Course Management
            'courses.view',
            'courses.create',
            'courses.edit',
            'courses.delete',
            
            // Student Management
            'students.view',
            'students.create',
            'students.edit',
            'students.delete',
            
            // Student Applications
            'student-applications.view',
            'student-applications.create',
            'student-applications.edit',
            'student-applications.delete',
            'student-applications.approve',
            'student-applications.reject',
            
            // Document Management
            'documents.view',
            'documents.create',
            'documents.edit',
            'documents.delete',
            'documents.upload',
            'documents.download',
            'documents.verify',
            
            // Document Types
            'document-types.view',
            'document-types.create',
            'document-types.edit',
            'document-types.delete',
            
            // System Administration
            'system.admin',
            'system.settings',
            'system.logs',
            'system.backup',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->command->info('✅ Permissions seeded successfully!');
        $this->command->info('📊 Total permissions created: ' . count($permissions));
    }
}
