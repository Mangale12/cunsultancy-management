@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ isset($employee) ? 'Edit' : 'Add' }} Employee</h6>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="{{ isset($employee) ? route('employees.update', $employee->id) : route('employees.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($employee)) @method('PUT') @endif

                <div class="row mb-4">
                    <div class="col-12"><h5 class="text-muted border-bottom pb-2">Personal Information</h5></div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $employee->first_name ?? '') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $employee->last_name ?? '') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="image_path" class="form-control">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12"><h5 class="text-muted border-bottom pb-2">Contact & Branch</h5></div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email ?? '') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone ?? '') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Assign to Branch</label>
                        <select name="branch_id" class="form-select" required>
                            <option value="">Choose Branch...</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ (old('branch_id', $employee->branch_id ?? '') == $branch->id) ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Full Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $employee->address ?? '') }}</textarea>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12"><h5 class="text-muted border-bottom pb-2">Job Information</h5></div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Job Title</label>
                        <input type="text" name="job_title" class="form-control" value="{{ old('job_title', $employee->job_title ?? '') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" value="{{ old('department', $employee->department ?? '') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Salary</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="salary" class="form-control" value="{{ old('salary', $employee->salary ?? '') }}">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Hire Date</label>
                        <input type="date" name="hire_date" class="form-control" value="{{ old('hire_date', $employee->hire_date ?? '') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-check form-switch mt-4">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $employee->is_active ?? 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active Employee</label>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Back</a>
                    <button type="submit" class="btn btn-primary px-5">
                        <i data-feather="save" class="me-1"></i> {{ isset($employee) ? 'Update' : 'Save' }} Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection