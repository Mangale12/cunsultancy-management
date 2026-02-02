@extends('layouts.app')

@section('title', $state->name)

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
    title="{{ $state->name }}"
    subtitle="View state details and manage associated students and locations."
    :actions="
        '<a href=\"' . route('states.index') . '\" class=\"btn btn-outline-secondary me-2\">
            <i data-feather=\"arrow-left\" class=\"me-2\"></i> Back to States
        </a>
        <a href=\"' . route('states.edit', $state->id) . '\" class=\"btn btn-primary\">
            <i data-feather=\"edit-2\" class=\"me-2\"></i> Edit State
        </a>'
    "
/>

<!-- State Details -->
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Basic Information -->
        <x-card>
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="map-pin" class="me-2"></i>
                    Basic Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">State Name</label>
                        <p class="fw-semibold">{{ $state->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">State Code</label>
                        <p class="fw-semibold">
                            @if($state->code)
                                <span class="badge bg-info">{{ $state->code }}</span>
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Country</label>
                        <p class="fw-semibold">
                            @if($state->country)
                                <a href="{{ route('countries.show', $state->country->id) }}" class="text-decoration-none">
                                    <i data-feather="globe" class="me-1"></i>{{ $state->country->name }}
                                </a>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Created At</label>
                        <p class="fw-semibold">{{ $state->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Students -->
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i data-feather="users" class="me-2"></i>
                    Students ({{ $state->students->count() }})
                </h5>
                <a href="{{ route('students.create') }}?state_id={{ $state->id }}" class="btn btn-sm btn-primary">
                    <i data-feather="user-plus" class="me-1"></i> Add Student
                </a>
            </div>
            <div class="card-body">
                @if($state->students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($state->students->take(10) as $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $student->image_path ? asset('storage/'.$student->image_path) : 'https://ui-avatars.com/api/?name='.urlencode($student->name).'&background=0d6efd&color=fff' }}" 
                                                 class="rounded-circle me-3" width="35" height="35" alt="{{ $student->name }}">
                                            <div class="fw-semibold">{{ $student->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small text-truncate" style="max-width: 200px;">
                                            {{ $student->email }}
                                        </div>
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
                    @if($state->students->count() > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('students.index') }}?state_id={{ $state->id }}" class="btn btn-outline-primary">
                            View All {{ $state->students->count() }} Students
                        </a>
                    </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i data-feather="users" class="text-muted" style="width: 48px; height: 48px;"></i>
                        <h5 class="mt-3 mb-2">No students found</h5>
                        <p class="text-muted mb-3">This state doesn't have any students yet.</p>
                        <a href="{{ route('students.create') }}?state_id={{ $state->id }}" class="btn btn-primary">
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
                        <i data-feather="map-pin" class="text-primary" style="width: 32px; height: 32px;"></i>
                    </div>
                    <h4 class="mb-1">{{ $state->name }}</h4>
                    <p class="text-muted">State Overview</p>
                </div>
                
                <div class="row g-3">
                    <div class="col-6">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="users" class="text-success mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">{{ $state->students->count() }}</h5>
                                <small class="text-muted">Students</small>
                            </div>
                        </x-card>
                    </div>
                    <div class="col-6">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="globe" class="text-info mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">1</h5>
                                <small class="text-muted">Country</small>
                            </div>
                        </x-card>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Country Information -->
        @if($state->country)
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="globe" class="me-2"></i>
                    Country Information
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                        <i data-feather="globe" class="text-primary" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">{{ $state->country->name }}</h6>
                        @if($state->country->code)
                            <small class="text-muted">Code: {{ $state->country->code }}</small>
                        @endif
                    </div>
                </div>
                <a href="{{ route('countries.show', $state->country->id) }}" class="btn btn-sm btn-outline-primary w-100">
                    <i data-feather="external-link" class="me-1"></i> View Country Details
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
                    <a href="{{ route('students.create') }}?state_id={{ $state->id }}" class="btn btn-outline-primary">
                        <i data-feather="user-plus" class="me-2"></i>Add Student
                    </a>
                    <a href="{{ route('states.edit', $state->id) }}" class="btn btn-outline-secondary">
                        <i data-feather="edit-2" class="me-2"></i>Edit State
                    </a>
                    <form action="{{ route('states.destroy', $state->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this state? This will also delete all associated students.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i data-feather="trash-2" class="me-2"></i>Delete State
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
