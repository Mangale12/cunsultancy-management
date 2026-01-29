<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmployeePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Superadmin can view all
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can view employees in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return true;
        }

        // Employee can view employees in their branch
        if ($user->hasRole('employee') && $user->employee) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Employee $employee): bool
    {
        // Superadmin can view all
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can view employees in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $employee->branch_id === $user->employee->branch_id;
        }

        // Employee can view employees in their branch
        if ($user->hasRole('employee') && $user->employee) {
            return $employee->branch_id === $user->employee->branch_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('manage_employees');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Employee $employee): bool
    {
        // Superadmin can update all
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can update employees in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $employee->branch_id === $user->employee->branch_id;
        }

        // Employee can update themselves only
        if ($user->hasRole('employee') && $user->employee) {
            return $employee->id === $user->employee->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Employee $employee): bool
    {
        // Superadmin can delete all
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can delete employees in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $employee->branch_id === $user->employee->branch_id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Employee $employee): bool
    {
        return $user->hasRole('superadmin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Employee $employee): bool
    {
        return $user->hasRole('superadmin');
    }
}
