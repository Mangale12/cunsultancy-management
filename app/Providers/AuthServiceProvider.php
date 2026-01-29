<?php

namespace App\Providers;

use App\Models\Agent;
use App\Models\Branch;
use App\Models\Commission;
use App\Models\Country;
use App\Models\Course;
use App\Models\Employee;
use App\Models\State;
use App\Models\Student;
use App\Models\University;
use App\Policies\AgentPolicy;
use App\Policies\BranchPolicy;
use App\Policies\CommissionPolicy;
use App\Policies\CountryPolicy;
use App\Policies\CoursePolicy;
use App\Policies\EmployeePolicy;
use App\Policies\StatePolicy;
use App\Policies\StudentPolicy;
use App\Policies\UniversityPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Country::class => CountryPolicy::class,
        State::class => StatePolicy::class,
        Branch::class => BranchPolicy::class,
        Employee::class => EmployeePolicy::class,
        University::class => UniversityPolicy::class,
        Course::class => CoursePolicy::class,
        Student::class => StudentPolicy::class,
        Agent::class => AgentPolicy::class,
        Commission::class => CommissionPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Super admin has access to everything
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super_admin')) {
                return true;
            }
        });
    }
}
