<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait HasAccessScopes
{
    /**
     * Scope to filter records visible to the user
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        // Superadmin can see all
        if ($user->hasRole('super_admin') || $user->hasRole('superadmin')) {
            return $query;
        }

        // Apply role-based filtering
        return $this->applyRoleBasedScope($query, $user);
    }

    /**
     * Apply role-based filtering based on the model type
     */
    protected function applyRoleBasedScope(Builder $query, User $user): Builder
    {
        $modelClass = static::class;

        // Branch admin scope
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $this->applyBranchAdminScope($query, $user);
        }

        // Agent scope
        if ($user->hasRole('agent') && $user->agent) {
            return $this->applyAgentScope($query, $user);
        }

        // Employee scope
        if ($user->hasRole('employee') && $user->employee) {
            return $this->applyEmployeeScope($query, $user);
        }

        // Student scope
        if ($user->hasRole('student') && $user->student) {
            return $this->applyStudentScope($query, $user);
        }

        // Default: no access
        return $query->whereRaw('1 = 0');
    }

    /**
     * Apply branch admin scope
     */
    protected function applyBranchAdminScope(Builder $query, User $user): Builder
    {
        $branchId = $user->employee->branch_id;
        
        // Filter by branch_id if the model has it
        if (in_array('branch_id', $this->getFillable())) {
            return $query->where('branch_id', $branchId);
        }

        // For models without branch_id, apply specific logic
        return $this->applyBranchAdminSpecificScope($query, $user);
    }

    /**
     * Apply agent scope
     */
    protected function applyAgentScope(Builder $query, User $user): Builder
    {
        $agentId = $user->agent->id;
        $branchId = $user->agent->branch_id;
        
        // Filter by agent_id if the model has it
        if (in_array('agent_id', $this->getFillable())) {
            return $query->where('agent_id', $agentId);
        }

        // Filter by branch_id if the model has it
        if (in_array('branch_id', $this->getFillable())) {
            return $query->where('branch_id', $branchId);
        }

        // For models without these fields, apply specific logic
        return $this->applyAgentSpecificScope($query, $user);
    }

    /**
     * Apply employee scope
     */
    protected function applyEmployeeScope(Builder $query, User $user): Builder
    {
        $branchId = $user->employee->branch_id;
        
        // Filter by branch_id if the model has it
        if (in_array('branch_id', $this->getFillable())) {
            return $query->where('branch_id', $branchId);
        }

        // For models without branch_id, apply specific logic
        return $this->applyEmployeeSpecificScope($query, $user);
    }

    /**
     * Apply student scope (self-only)
     */
    protected function applyStudentScope(Builder $query, User $user): Builder
    {
        $studentId = $user->student->id;
        
        // Filter by id if this is the Student model
        if (static::class === 'App\\Models\\Student') {
            return $query->where('id', $studentId);
        }

        // For other models, students have no access
        return $query->whereRaw('1 = 0');
    }

    /**
     * Override these methods in specific models for custom logic
     */
    protected function applyBranchAdminSpecificScope(Builder $query, User $user): Builder
    {
        return $query;
    }

    protected function applyAgentSpecificScope(Builder $query, User $user): Builder
    {
        return $query;
    }

    protected function applyEmployeeSpecificScope(Builder $query, User $user): Builder
    {
        return $query;
    }
}
