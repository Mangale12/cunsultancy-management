@extends('layouts.app')

@section('title', $course->name)

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
    title="{{ $course->name }}"
    subtitle="View course details and manage student applications and enrollment."
    :actions="
        '<a href=\"' . route('courses.index') . '\" class=\"btn btn-outline-secondary me-2\">
            <i data-feather=\"arrow-left\" class=\"me-2\"></i> Back to Courses
        </a>
        <a href=\"' . route('courses.edit', $course->id) . '\" class=\"btn btn-primary\">
            <i data-feather=\"edit-2\" class=\"me-2\"></i> Edit Course
        </a>'
    "
/>

<!-- Course Details -->
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Basic Information -->
        <x-card>
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="book" class="me-2"></i>
                    Course Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Course Name</label>
                        <p class="fw-semibold">{{ $course->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Course Level</label>
                        <p class="fw-semibold">
                            <span class="badge bg-info">{{ $course->level }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Duration</label>
                        <p class="fw-semibold">
                            @if($course->duration_months)
                                {{ $course->duration_months }} {{ $course->duration_months == 1 ? 'month' : 'months' }}
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Tuition Fee</label>
                        <p class="fw-semibold">
                            @if($course->tuition_fee)
                                <span class="text-success">{{ $course->currency ?? 'USD' }} {{ number_format($course->tuition_fee, 2) }}</span>
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">University</label>
                        <p class="fw-semibold">
                            @if($course->university)
                                <a href="{{ route('universities.show', $course->university->id) }}" class="text-decoration-none">
                                    <i data-feather="book-open" class="me-1"></i>{{ $course->university->name }}
                                </a>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Created At</label>
                        <p class="fw-semibold">{{ $course->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Course Image -->
        @if($course->image_path)
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="image" class="me-2"></i>
                    Course Image
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <img src="{{ asset('storage/' . $course->image_path) }}" 
                         alt="{{ $course->name }}" 
                         class="img-fluid rounded shadow-sm" 
                         style="max-height: 300px;">
                </div>
            </div>
        </x-card>
        @endif

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
                    <p class="text-muted mb-3">Track and manage student applications for this course.</p>
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
                    Course Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-block mb-3">
                        <i data-feather="book" class="text-primary" style="width: 32px; height: 32px;"></i>
                    </div>
                    <h4 class="mb-1">{{ $course->name }}</h4>
                    <p class="text-muted">Course Overview</p>
                </div>
                
                <div class="row g-3">
                    <div class="col-6">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="users" class="text-success mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">0</h5>
                                <small class="text-muted">Students</small>
                            </div>
                        </x-card>
                    </div>
                    <div class="col-6">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="clock" class="text-info mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">{{ $course->duration_months ?? 'N/A' }}</h5>
                                <small class="text-muted">Duration</small>
                            </div>
                        </x-card>
                    </div>
                    <div class="col-12">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="dollar-sign" class="text-warning mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">
                                    @if($course->tuition_fee)
                                        {{ $course->currency ?? 'USD' }} {{ number_format($course->tuition_fee, 0) }}
                                    @else
                                        N/A
                                    @endif
                                </h5>
                                <small class="text-muted">Tuition Fee</small>
                            </div>
                        </x-card>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- University Information -->
        @if($course->university)
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="book-open" class="me-2"></i>
                    University Information
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                        <i data-feather="book-open" class="text-primary" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">{{ $course->university->name }}</h6>
                        @if($course->university->code)
                            <small class="text-muted">Code: {{ $course->university->code }}</small>
                        @endif
                    </div>
                </div>
                
                <a href="{{ route('universities.show', $course->university->id) }}" class="btn btn-sm btn-outline-primary w-100">
                    <i data-feather="external-link" class="me-1"></i> View University Details
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
                    <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-outline-primary">
                        <i data-feather="edit-2" class="me-2"></i>Edit Course
                    </a>
                    <a href="{{ route('universities.show', $course->university_id) }}" class="btn btn-outline-secondary">
                        <i data-feather="book-open" class="me-2"></i>View University
                    </a>
                    <form action="{{ route('courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i data-feather="trash-2" class="me-2"></i>Delete Course
                        </button>
                    </form>
                </div>
            </div>
        </x-card>

        <!-- Course Level Badge -->
        <x-card class="mt-4">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i data-feather="award" class="text-primary" style="width: 32px; height: 32px;"></i>
                </div>
                <h6 class="mb-2">Course Level</h6>
                <span class="badge bg-info fs-6">{{ $course->level }}</span>
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
