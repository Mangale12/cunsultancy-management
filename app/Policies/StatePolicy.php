<?php

namespace App\Policies;

use App\Models\State;
use App\Models\User;

class StatePolicy
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
        return (bool) ($user->employee()->first() || $user->agent()->first() || $user->student()->first());
    }

    public function view(User $user, State $state): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return (bool) ($user->employee()->first() || $user->agent()->first() || $user->student()->first());
    }

    public function update(User $user, State $state): bool
    {
        return (bool) ($user->employee()->first() || $user->agent()->first() || $user->student()->first());
    }

    public function delete(User $user, State $state): bool
    {
        return (bool) ($user->employee()->first() || $user->agent()->first() || $user->student()->first());
    }
}
