<?php

namespace App\Policies;

use App\Models\Commission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommissionPolicy
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

        // Branch admin can view commissions in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return true;
        }

        // Agent can view their commissions
        if ($user->hasRole('agent') && $user->agent) {
            return true;
        }

        // Employee can view commissions in their branch
        if ($user->hasRole('employee') && $user->employee) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Commission $commission): bool
    {
        // Superadmin can view all
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can view commissions in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $commission->branch_id === $user->employee->branch_id;
        }

        // Agent can view their commissions only
        if ($user->hasRole('agent') && $user->agent) {
            return $commission->agent_id === $user->agent->id;
        }

        // Employee can view commissions in their branch
        if ($user->hasRole('employee') && $user->employee) {
            return $commission->branch_id === $user->employee->branch_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('manage_commissions');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Commission $commission): bool
    {
        // Superadmin can update all
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can update commissions in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $commission->branch_id === $user->employee->branch_id;
        }

        // Agent can update their commissions only
        if ($user->hasRole('agent') && $user->agent) {
            return $commission->agent_id === $user->agent->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Commission $commission): bool
    {
        // Superadmin can delete all
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can delete commissions in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $commission->branch_id === $user->employee->branch_id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Commission $commission): bool
    {
        return $user->hasRole('superadmin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Commission $commission): bool
    {
        return $user->hasRole('superadmin');
    }
}
