<?php

namespace App\Policies;

use App\Models\StudentDocument;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentDocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_student_documents');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StudentDocument $studentDocument): bool
    {
        // Allow if user is the student who owns the document
        if ($user->is($studentDocument->student->user)) {
            return true;
        }
        
        // Allow if user is an admin or has view_student_documents permission
        return $user->hasRole('admin') || $user->can('view_student_documents');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_student_documents');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StudentDocument $studentDocument): bool
    {
        // Document owners can update their own documents
        if ($user->is($studentDocument->student->user)) {
            return true;
        }
        
        // Admins and staff with update permission can update any document
        return $user->hasRole(['admin', 'staff']) && $user->can('update_student_documents');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StudentDocument $studentDocument): bool
    {
        // Only allow admins or staff with delete permission to delete documents
        return $user->hasRole(['admin', 'staff']) && $user->can('delete_student_documents');
    }

    /**
     * Determine whether the user can verify documents.
     */
    public function verify(User $user, StudentDocument $studentDocument): bool
    {
        // Only allow admins or staff with verify permission to verify documents
        return $user->hasRole(['admin', 'staff']) && $user->can('verify_student_documents');
    }

    /**
     * Determine whether the user can download the document.
     */
    public function download(User $user, StudentDocument $studentDocument): bool
    {
        // Document owners can download their own documents
        if ($user->is($studentDocument->student->user)) {
            return true;
        }
        
        // Admins and staff with view permission can download any document
        return $user->hasRole(['admin', 'staff']) && $user->can('view_student_documents');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StudentDocument $studentDocument): bool
    {
        return $user->hasRole('admin') && $user->can('restore_student_documents');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StudentDocument $studentDocument): bool
    {
        return $user->hasRole('admin') && $user->can('force_delete_student_documents');
    }
}
