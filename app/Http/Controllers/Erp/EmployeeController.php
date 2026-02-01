<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Branch;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('admin.employee.index', compact('employees'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('admin.employee.form', compact('branches'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'hire_date' => 'nullable|date',
            'branch_id' => 'required|exists:branches,id',
            'is_active' => 'boolean',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        $branches = Branch::all();
        return view('admin.employee.form', compact('employee', 'branches'));
    }
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'hire_date' => 'nullable|date',
            'branch_id' => 'required|exists:branches,id',
            'is_active' => 'boolean',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
    // public function index(Request $request): Response
    // {
    //     $search = $request->get('search');
    //     $branch = $request->get('branch');
    //     $perPage = $request->get('per_page', 10);

    //     $query = Employee::with('branch')
    //         ->when($search, function ($query, $search) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%")
    //                   ->orWhere('email', 'like', "%{$search}%")
    //                   ->orWhere('position', 'like', "%{$search}%");
    //             });
    //         })
    //         ->when($branch, function ($query, $branch) {
    //             $query->where('branch_id', $branch);
    //         })
    //         ->orderBy('created_at', 'desc');

    //     $employees = $query->paginate($perPage);

    //     $branches = Branch::all();

    //     return Inertia::render('employees/index', [
    //         'employees' => $employees,
    //         'branches' => $branches,
    //         'filters' => [
    //             'search' => $search,
    //             'branch' => $branch,
    //             'per_page' => $perPage,
    //         ],
    //     ]);
    // }

    // public function create(): Response
    // {
    //     $branches = Branch::all();

    //     return Inertia::render('employees/create', [
    //         'branches' => $branches,
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:employees,email',
    //         'phone' => 'nullable|string|max:20',
    //         'address' => 'nullable|string',
    //         'position' => 'nullable|string|max:255',
    //         'department' => 'nullable|string|max:255',
    //         'salary' => 'nullable|numeric|min:0',
    //         'hire_date' => 'nullable|date',
    //         'branch_id' => 'required|exists:branches,id',
    //         'is_active' => 'boolean',
    //     ]);

    //     Employee::create($validated);

    //     return redirect()->route('employees.index')
    //         ->with('success', 'Employee created successfully.');
    // }

    // public function show(Employee $employee): Response
    // {
    //     $employee->load('branch');

    //     return Inertia::render('employees/show', [
    //         'employee' => $employee,
    //     ]);
    // }

    // public function edit(Employee $employee): Response
    // {
    //     $branches = Branch::all();

    //     return Inertia::render('employees/edit', [
    //         'employee' => $employee,
    //         'branches' => $branches,
    //     ]);
    // }

    // public function update(Request $request, Employee $employee)
    // {
    //     $validated = $request->validate([
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:employees,email,' . $employee->id,
    //         'phone' => 'nullable|string|max:20',
    //         'address' => 'nullable|string',
    //         'position' => 'nullable|string|max:255',
    //         'department' => 'nullable|string|max:255',
    //         'salary' => 'nullable|numeric|min:0',
    //         'hire_date' => 'nullable|date',
    //         'branch_id' => 'required|exists:branches,id',
    //         'is_active' => 'boolean',
    //     ]);

    //     $employee->update($validated);

    //     return redirect()->route('employees.index')
    //         ->with('success', 'Employee updated successfully.');
    // }

    // public function destroy(Employee $employee)
    // {
    //     $employee->delete();

    //     return redirect()->route('employees.index')
    //         ->with('success', 'Employee deleted successfully.');
    // }
}
