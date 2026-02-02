@extends('layouts.app')

@section('title', $branch->name)

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i data-feather="check-circle" class="me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i data-feather="alert-circle" class="me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<x-page-header 
    title="{{ $branch->name }}"
    subtitle="View branch details and manage associated employees, agents, and students."
    :actions="
        '<a href=\"' . route('branches.index') . '\" class=\"btn btn-outline-secondary me-2\">
            <i data-feather=\"arrow-left\" class=\"me-2\"></i> Back to Branches
        </a>
        <a href=\"' . route('branches.edit', $branch->id) . '\" class=\"btn btn-primary\">
            <i data-feather=\"edit-2\" class=\"me-2\"></i> Edit Branch
        </a>'
    "
/>

<!-- Branch Details -->
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Basic Information -->
        <x-card>
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="briefcase" class="me-2"></i>
                    Basic Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Branch Name</label>
                        <p class="fw-semibold">{{ $branch->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Branch Code</label>
                        <p class="fw-semibold">
                            <span class="badge bg-info">{{ $branch->code }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Manager Name</label>
                        <p class="fw-semibold">
                            @if($branch->manager_name)
                                {{ $branch->manager_name }}
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Status</label>
                        <p class="fw-semibold">
                            @if($branch->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Country</label>
                        <p class="fw-semibold">
                            @if($branch->country)
                                <a href="{{ route('countries.show', $branch->country->id) }}" class="text-decoration-none">
                                    <i data-feather="globe" class="me-1"></i>{{ $branch->country->name }}
                                </a>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">State</label>
                        <p class="fw-semibold">
                            @if($branch->state)
                                <a href="{{ route('states.show', $branch->state->id) }}" class="text-decoration-none">
                                    <i data-feather="map-pin" class="me-1"></i>{{ $branch->state->name }}
                                </a>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-muted">Contact Information</label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <small class="text-muted">Email:</small>
                                <p class="fw-semibold">
                                    @if($branch->email)
                                        <a href="mailto:{{ $branch->email }}">{{ $branch->email }}</a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Phone:</small>
                                <p class="fw-semibold">
                                    @if($branch->phone)
                                        <a href="tel:{{ $branch->phone }}">{{ $branch->phone }}</a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-muted">Address</label>
                        <p class="fw-semibold">
                            @if($branch->address)
                                {{ $branch->address }}
                            @else
                                <span class="text-muted">No address provided</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Created At</label>
                        <p class="fw-semibold">{{ $branch->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Employees -->
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i data-feather="users" class="me-2"></i>
                    Employees ({{ $branch->employees->count() }})
                </h5>
                <a href="{{ route('employees.create') }}?branch_id={{ $branch->id }}" class="btn btn-sm btn-primary">
                    <i data-feather="user-plus" class="me-1"></i> Add Employee
                </a>
            </div>
            <div class="card-body">
                @if($branch->employees->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Email</th>
                                    <th>Job Title</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($branch->employees as $employee)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                                <i data-feather="users" class="text-primary" style="width: 14px; height: 14px;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                                <small class="text-muted">ID: #EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small text-truncate" style="max-width: 200px;">
                                            @if($employee->email)
                                                <a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a>
                                            @else
                                                <span class="text-muted">No email</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            @if($employee->job_title)
                                                {{ $employee->job_title }}
                                            @else
                                                Not assigned
                                            @endif
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i data-feather="edit-2" style="width: 14px; height: 14px;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i data-feather="users" class="text-muted" style="width: 48px; height: 48px;"></i>
                        <h5 class="mt-3 mb-2">No employees found</h5>
                        <p class="text-muted mb-3">This branch doesn't have any employees yet.</p>
                        <a href="{{ route('employees.create') }}?branch_id={{ $branch->id }}" class="btn btn-primary">
                            <i data-feather="user-plus" class="me-2"></i>Add First Employee
                        </a>
                    </div>
                @endif
            </div>
        </x-card>

        <!-- Agents -->
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i data-feather="user-check" class="me-2"></i>
                    Agents ({{ $branch->agents->count() }})
                </h5>
                <a href="{{ route('agents.create') }}?branch_id={{ $branch->id }}" class="btn btn-sm btn-primary">
                    <i data-feather="user-plus" class="me-1"></i> Add Agent
                </a>
            </div>
            <div class="card-body">
                @if($branch->agents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Agent Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($branch->agents as $agent)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $agent->image_path ? asset('storage/'.$agent->image_path) : 'https://ui-avatars.com/api/?name='.urlencode($agent->name).'&background=0d6efd&color=fff' }}" 
                                                 class="rounded-circle me-3" width="35" height="35" alt="{{ $agent->name }}">
                                            <div>
                                                <div class="fw-semibold">{{ $agent->name }}</div>
                                                <small class="text-muted">ID: #AG-{{ str_pad($agent->id, 3, '0', STR_PAD_LEFT) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small text-truncate" style="max-width: 200px;">
                                            @if($agent->email)
                                                <a href="mailto:{{ $agent->email }}">{{ $agent->email }}</a>
                                            @else
                                                <span class="text-muted">No email</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            @if($agent->phone)
                                                <a href="tel:{{ $agent->phone }}">{{ $agent->phone }}</a>
                                            @else
                                                <span class="text-muted">No phone</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i data-feather="edit-2" style="width: 14px; height: 14px;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i data-feather="user-check" class="text-muted" style="width: 48px; height: 48px;"></i>
                        <h5 class="mt-3 mb-2">No agents found</h5>
                        <p class="text-muted mb-3">This branch doesn't have any agents yet.</p>
                        <a href="{{ route('agents.create') }}?branch_id={{ $branch->id }}" class="btn btn-primary">
                            <i data-feather="user-plus" class="me-2"></i>Add First Agent
                        </a>
                    </div>
                @endif
            </div>
        </x-card>

        <!-- Students -->
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i data-feather="graduation-cap" class="me-2"></i>
                    Students ({{ $branch->students->count() }})
                </h5>
                <a href="{{ route('students.create') }}?branch_id={{ $branch->id }}" class="btn btn-sm btn-primary">
                    <i data-feather="user-plus" class="me-1"></i> Add Student
                </a>
            </div>
            <div class="card-body">
                @if($branch->students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Course</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($branch->students->take(10) as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $student->image_path ? asset('storage/'.$student->image_path) : 'https://ui-avatars.com/api/?name='.urlencode($student->first_name.' '.$student->last_name).'&background=0d6efd&color=fff' }}" 
                                                 class="rounded-circle me-3" width="35" height="35" alt="{{ $student->first_name }} {{ $student->last_name }}">
                                            <div>
                                                <div class="fw-semibold">{{ $student->first_name }} {{ $student->last_name }}</div>
                                                <small class="text-muted">ID: #STU-{{ str_pad($student->id, 3, '0', STR_PAD_LEFT) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small text-truncate" style="max-width: 200px;">
                                            @if($student->email)
                                                <a href="mailto:{{ $student->email }}">{{ $student->email }}</a>
                                            @else
                                                <span class="text-muted">No email</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            @if($student->phone)
                                                <a href="tel:{{ $student->phone }}">{{ $student->phone }}</a>
                                            @else
                                                <span class="text-muted">No phone</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            @if($student->course)
                                                {{ $student->course->name ?? 'No course' }}
                                            @else
                                                No course
                                            @endif
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                            </a>
                                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-secondary">
                                                <i data-feather="edit-2" style="width: 14px; height: 14px;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($branch->students->count() > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('students.index') }}?branch_id={{ $branch->id }}" class="btn btn-outline-primary">
                            View All {{ $branch->students->count() }} Students
                        </a>
                    </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i data-feather="graduation-cap" class="text-muted" style="width: 48px; height: 48px;"></i>
                        <h5 class="mt-3 mb-2">No students found</h5>
                        <p class="text-muted mb-3">This branch doesn't have any students yet.</p>
                        <a href="{{ route('students.create') }}?branch_id={{ $branch->id }}" class="btn btn-primary">
                            <i data-feather="user-plus" class="me-2"></i>Add First Student
                        </a>
                    </div>
                @endif
            </div>
        </x-card>
    </div>

    <div class="col-lg-4">
        <!-- Statistics -->
        <x-card>
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="bar-chart-2" class="me-2"></i>
                    Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-block mb-3">
                        <i data-feather="briefcase" class="text-primary" style="width: 32px; height: 32px;"></i>
                    </div>
                    <h4 class="mb-1">{{ $branch->name }}</h4>
                    <p class="text-muted">Branch Overview</p>
                </div>
                
                <div class="row g-3">
                    <div class="col-6">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="users" class="text-success mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">{{ $branch->employees->count() }}</h5>
                                <small class="text-muted">Employees</small>
                            </div>
                        </x-card>
                    </div>
                    <div class="col-6">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="user-check" class="text-primary mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">{{ $branch->agents->count() }}</h5>
                                <small class="text-muted">Agents</small>
                            </div>
                        </x-card>
                    </div>
                    <div class="col-6">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="graduation-cap" class="text-info mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">{{ $branch->students->count() }}</h5>
                                <small class="text-muted">Students</small>
                            </div>
                        </x-card>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Location Information -->
        @if($branch->country || $branch->state)
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="map-pin" class="me-2"></i>
                    Location Information
                </h5>
            </div>
            <div class="card-body">
                @if($branch->country)
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                        <i data-feather="globe" class="text-primary" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">{{ $branch->country->name }}</h6>
                        @if($branch->country->phone_code)
                            <small class="text-muted">Phone Code: +{{ $branch->country->phone_code }}</small>
                        @endif
                    </div>
                </div>
                @endif
                
                @if($branch->state)
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                        <i data-feather="map-pin" class="text-success" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">{{ $branch->state->name }}</h6>
                        @if($branch->state->code)
                            <small class="text-muted">Code: {{ $branch->state->code }}</small>
                        @endif
                    </div>
                </div>
                @endif
                
                <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-sm btn-outline-primary w-100">
                    <i data-feather="edit-2" class="me-1"></i>Edit Branch Details
                </a>
            </div>
        </x-card>
        @endif

        <!-- Quick Actions -->
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="zap" class="me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('employees.create') }}?branch_id={{ $branch->id }}" class="btn btn-outline-primary">
                        <i data-feather="user-plus" class="me-2"></i>Add Employee
                    </a>
                    <a href="{{ route('agents.create') }}?branch_id={{ $branch->id }}" class="btn btn-outline-secondary">
                        <i data-feather="user-plus" class="me-2"></i>Add Agent
                    </a>
                    <a href="{{ route('students.create') }}?branch_id={{ $branch->id }}" class="btn btn-outline-info">
                        <i data-feather="user-plus" class="me-2"></i>Add Student
                    </a>
                    <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-outline-secondary">
                        <i data-feather="edit-2" class="me-2"></i>Edit Branch
                    </a>
                    <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this branch? This will also delete all associated employees, agents, and students.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i data-feather="trash-2" class="me-2"></i>Delete Branch
                        </button>
                    </form>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Feather Icons
        feather.replace();
    });
</script>
@endpush
