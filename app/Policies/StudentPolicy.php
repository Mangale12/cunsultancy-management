<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Superadmin can view all
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can view students in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return true;
        }

        // Agent can view their direct students
        if ($user->hasRole('agent') && $user->agent) {
            return true;
        }

        // Employee can view students in their branch
        if ($user->hasRole('employee') && $user->employee) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Student $student): bool
    {
        // Superadmin can view all
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can view students in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $student->branch_id === $user->employee->branch_id;
        }

        // Agent can view their direct students only
        if ($user->hasRole('agent') && $user->agent) {
            return $student->agent_id === $user->agent->id;
        }

        // Employee can view students in their branch
        if ($user->hasRole('employee') && $user->employee) {
            return $student->branch_id === $user->employee->branch_id;
        }

        // Student can view themselves only
        if ($user->hasRole('student') && $user->student) {
            return $student->id === $user->student->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('manage_students');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Student $student): bool
    {
        // Superadmin can update all
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can update students in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $student->branch_id === $user->employee->branch_id;
        }

        // Agent can update their direct students only
        if ($user->hasRole('agent') && $user->agent) {
            return $student->agent_id === $user->agent->id;
        }

        // Employee can update students in their branch
        if ($user->hasRole('employee') && $user->employee) {
            return $student->branch_id === $user->employee->branch_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Student $student): bool
    {
        // Superadmin can delete all
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Branch admin can delete students in their branch
        if ($user->hasRole('branch_admin') && $user->employee) {
            return $student->branch_id === $user->employee->branch_id;
        }

        // Agent can delete their direct students only
        if ($user->hasRole('agent') && $user->agent) {
            return $student->agent_id === $user->agent->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Student $student): bool
    {
        return $user->hasRole('superadmin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Student $student): bool
    {
        return $user->hasRole('superadmin');
    }
}
