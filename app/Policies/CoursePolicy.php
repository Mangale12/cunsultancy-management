<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
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

    public function view(User $user, Course $course): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Course $course): bool
    {
        return false;
    }

    public function delete(User $user, Course $course): bool
    {
        return false;
    }
}
