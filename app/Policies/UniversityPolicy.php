<?php

namespace App\Policies;

use App\Models\University;
use App\Models\User;

class UniversityPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isSuperadmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return (bool) ($user->employee || $user->agent || $user->student);
    }

    public function view(User $user, University $university): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, University $university): bool
    {
        return false;
    }

    public function delete(User $user, University $university): bool
    {
        return false;
    }
}
