<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\Agent;
use App\Models\Student;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Policies\AdminPolicy;

class UserRoleController extends Controller
{
    public function __construct()
    {
        // Middleware will be applied at the route level
    }

    /**
     * Check if user is superadmin
     */
    private function ensureSuperadmin()
    {
        if (!auth()->user()->hasRole('super_admin') && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized access');
        }
    }

    /**
     * Display users with roles management
     */
    public function index(Request $request): Response
    {
        $this->ensureSuperadmin();
        
        $search = $request->get('search');
        $roleFilter = $request->get('role');
        $perPage = $request->get('per_page', 10);

        $query = User::with('roles', 'employee', 'agent', 'student')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($roleFilter, function ($query, $roleFilter) {
                $query->whereHas('roles', function ($q) use ($roleFilter) {
                    $q->where('name', $roleFilter);
                });
            });

        $users = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $roles = Role::orderBy('name')->get();

        return Inertia::render('admin/user-roles/index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => [
                'search' => $search,
                'role' => $roleFilter,
                'per_page' => $perPage,
            ],
        ]);
    }

    /**
     * Show user role assignment form
     */
    public function edit(User $user): Response
    {
        $this->ensureSuperadmin();
        
        $user->load('roles', 'employee', 'agent', 'student');
        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        return Inertia::render('admin/user-roles/edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'employee' => $user->employee,
                'agent' => $user->agent,
                'student' => $user->student,
            ],
            'roles' => $roles,
            'permissions' => $permissions->groupBy(function ($permission) {
                return explode('_', $permission->name)[0];
            }),
        ]);
    }

    /**
     * Update user roles and permissions
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureSuperadmin();
        
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        DB::beginTransaction();
        try {
            // Sync roles
            $user->syncRoles($request->roles ?? []);

            // Sync permissions (direct permissions)
            $user->syncPermissions($request->permissions ?? []);

            DB::commit();

            return redirect()
                ->route('admin.user-roles.index')
                ->with('success', 'User roles and permissions updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Failed to update user roles and permissions: ' . $e->getMessage());
        }
    }

    /**
     * Show roles and permissions management
     */
    public function rolesIndex(): Response
    {
        $this->ensureSuperadmin();
        
        $roles = Role::with('permissions')
            ->withCount('users')
            ->orderBy('name')
            ->get();

        $permissions = Permission::orderBy('name')->get()
            ->groupBy(function ($permission) {
                return explode('_', $permission->name)[0];
            });

        return Inertia::render('admin/user-roles/roles', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show role edit form
     */
    public function editRole(Role $role): Response
    {
        $this->ensureSuperadmin();
        
        $role->load('permissions');
        $permissions = Permission::orderBy('name')->get()
            ->groupBy(function ($permission) {
                return explode('_', $permission->name)[0];
            });

        return Inertia::render('admin/user-roles/edit-role', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'permissions' => $role->permissions->pluck('name'),
            ],
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update role permissions
     */
    public function updateRole(Request $request, Role $role): RedirectResponse
    {
        $this->ensureSuperadmin();
        
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        DB::beginTransaction();
        try {
            $role->syncPermissions($request->permissions ?? []);

            DB::commit();

            return redirect()
                ->route('admin.user-roles.roles')
                ->with('success', 'Role permissions updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Failed to update role permissions: ' . $e->getMessage());
        }
    }

    /**
     * Get user details for AJAX
     */
    public function getUserDetails(User $user): array
    {
        $this->ensureSuperadmin();
        
        $user->load('roles', 'permissions', 'employee', 'agent', 'student');

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name'),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'employee' => $user->employee ? [
                'id' => $user->employee->id,
                'name' => $user->employee->name,
                'branch_id' => $user->employee->branch_id,
            ] : null,
            'agent' => $user->agent ? [
                'id' => $user->agent->id,
                'name' => $user->agent->name,
                'branch_id' => $user->agent->branch_id,
            ] : null,
            'student' => $user->student ? [
                'id' => $user->student->id,
                'name' => $user->student->name,
                'branch_id' => $user->student->branch_id,
            ] : null,
        ];
    }
}
