@extends('layouts.app')

@section('title', $applicationYear->year)

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
    title="{{ $applicationYear->year }}"
    subtitle="View application year details and manage associated student applications."
    :actions="
        '<a href=\"' . route('application-years.index') . '\" class=\"btn btn-outline-secondary me-2\">
            <i data-feather=\"arrow-left\" class=\"me-2\"></i> Back to Application Years
        </a>
        <a href=\"' . route('application-years.edit', $applicationYear->id) . '\" class=\"btn btn-primary\">
            <i data-feather=\"edit-2\" class=\"me-2\"></i> Edit Application Year
        </a>'
    "
/>

<!-- Application Year Details -->
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Basic Information -->
        <x-card>
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="calendar" class="me-2"></i>
                    Year Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Application Year</label>
                        <p class="fw-semibold">
                            <span class="badge bg-primary fs-6">{{ $applicationYear->year }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Status</label>
                        <p class="fw-semibold">
                            @if($applicationYear->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Created At</label>
                        <p class="fw-semibold">{{ $applicationYear->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Last Updated</label>
                        <p class="fw-semibold">{{ $applicationYear->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Student Applications -->
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i data-feather="users" class="me-2"></i>
                    Student Applications
                </h5>
                <span class="badge bg-primary">Coming Soon</span>
            </div>
            <div class="card-body">
                <div class="text-center py-4">
                    <i data-feather="users" class="text-muted" style="width: 48px; height: 48px;"></i>
                    <h5 class="mt-3 mb-2">Student Applications</h5>
                    <p class="text-muted mb-3">Track and manage student applications for {{ $applicationYear->year }}.</p>
                    <div class="alert alert-info">
                        <i data-feather="info" class="me-2"></i>
                        Student application tracking will be available in the next update.
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <div class="col-lg-4">
        <!-- Statistics -->
        <x-card>
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="bar-chart-2" class="me-2"></i>
                    Year Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-block mb-3">
                        <i data-feather="calendar" class="text-primary" style="width: 32px; height: 32px;"></i>
                    </div>
                    <h4 class="mb-1">{{ $applicationYear->year }}</h4>
                    <p class="text-muted">Application Year Overview</p>
                </div>
                
                <div class="row g-3">
                    <div class="col-12">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="users" class="text-success mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">0</h5>
                                <small class="text-muted">Student Applications</small>
                            </div>
                        </x-card>
                    </div>
                    <div class="col-12">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="check-circle" class="text-info mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">
                                    @if($applicationYear->is_active)
                                        Active
                                    @else
                                        Inactive
                                    @endif
                                </h5>
                                <small class="text-muted">Status</small>
                            </div>
                        </x-card>
                    </div>
                </div>
            </div>
        </x-card>

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
                    <a href="{{ route('application-years.edit', $applicationYear->id) }}" class="btn btn-outline-primary">
                        <i data-feather="edit-2" class="me-2"></i>Edit Application Year
                    </a>
                    <form action="{{ route('application-years.destroy', $applicationYear->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this application year? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i data-feather="trash-2" class="me-2"></i>Delete Application Year
                        </button>
                    </form>
                </div>
            </div>
        </x-card>

        <!-- Status Badge -->
        <x-card class="mt-4">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i data-feather="toggle-right" class="text-primary" style="width: 32px; height: 32px;"></i>
                </div>
                <h6 class="mb-2">Application Status</h6>
                <span class="badge {{ $applicationYear->is_active ? 'bg-success' : 'bg-secondary' }} fs-6">
                    {{ $applicationYear->is_active ? 'Active' : 'Inactive' }}
                </span>
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
