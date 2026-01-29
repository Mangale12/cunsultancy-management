<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;

class AdminPolicy
{
    /**
     * Determine whether the user can access admin panel.
     */
    public function accessAdminPanel($user): bool
    {
        return $user && $user->hasRole('superadmin');
    }
}
