<?php

namespace App\Policies;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AgentPolicy
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

        // Branch admin can view agents in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return true;
        }

        // Agent can view themselves and their direct child agents
        if ($user->hasRole('agent') && $user->agent) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Agent $agent): bool
    {
        // Superadmin can view all
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can view agents in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $agent->branch_id === $user->employee->branch_id;
        }

        // Agent can view themselves and their direct child agents only
        if ($user->hasRole('agent') && $user->agent) {
            return $agent->id === $user->agent->id || $agent->parent_agent_id === $user->agent->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('manage_agents');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Agent $agent): bool
    {
        // Superadmin can update all
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can update agents in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $agent->branch_id === $user->employee->branch_id;
        }

        // Agent can update themselves only
        if ($user->hasRole('agent') && $user->agent) {
            return $agent->id === $user->agent->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Agent $agent): bool
    {
        // Superadmin can delete all
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can delete agents in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $agent->branch_id === $user->employee->branch_id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Agent $agent): bool
    {
        return $user->hasRole('superadmin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Agent $agent): bool
    {
        return $user->hasRole('superadmin');
    }
}
