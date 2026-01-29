<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BranchPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Superadmin can view all
        if ($user->hasRole('super_admin') || $user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can view branches
        if ($user->hasRole('branch_admin') && $user->employee) {
            return true;
        }

        // Agent can view their branch
        if ($user->hasRole('agent') && $user->agent) {
            return true;
        }

        // Employee can view their branch
        if ($user->hasRole('employee') && $user->employee) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Branch $branch): bool
    {
        // Superadmin can view all
        if ($user->hasRole('super_admin') || $user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can view their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $branch->id === $user->employee->branch_id;
        }

        // Agent can view their branch
        if ($user->hasRole('agent') && $user->agent) {
            return $branch->id === $user->agent->branch_id;
        }

        // Employee can view their branch
        if ($user->hasRole('employee') && $user->employee) {
            return $branch->id === $user->employee->branch_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('manage_branches');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Branch $branch): bool
    {
        // Superadmin can update all
        if ($user->hasRole('super_admin') || $user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can update their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $branch->id === $user->employee->branch_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Branch $branch): bool
    {
        // Superadmin can delete all
        if ($user->hasRole('super_admin') || $user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can delete their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $branch->id === $user->employee->branch_id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Branch $branch): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('superadmin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Branch $branch): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('superadmin');
    }
}
