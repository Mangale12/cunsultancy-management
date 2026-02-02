@extends('layouts.app')

@section('title', $university->name)

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
    title="{{ $university->name }}"
    subtitle="View university details and manage associated courses and student applications."
    :actions="
        '<a href=\"' . route('universities.index') . '\" class=\"btn btn-outline-secondary me-2\">
            <i data-feather=\"arrow-left\" class=\"me-2\"></i> Back to Universities
        </a>
        <a href=\"' . route('universities.edit', $university->id) . '\" class=\"btn btn-primary\">
            <i data-feather=\"edit-2\" class=\"me-2\"></i> Edit University
        </a>'
    "
/>

<!-- University Details -->
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Basic Information -->
        <x-card>
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="book-open" class="me-2"></i>
                    Basic Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">University Name</label>
                        <p class="fw-semibold">{{ $university->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">University Code</label>
                        <p class="fw-semibold">
                            @if($university->code)
                                <span class="badge bg-info">{{ $university->code }}</span>
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Country</label>
                        <p class="fw-semibold">
                            @if($university->country)
                                <a href="{{ route('countries.show', $university->country->id) }}" class="text-decoration-none">
                                    <i data-feather="globe" class="me-1"></i>{{ $university->country->name }}
                                </a>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">State</label>
                        <p class="fw-semibold">
                            @if($university->state)
                                <a href="{{ route('states.show', $university->state->id) }}" class="text-decoration-none">
                                    <i data-feather="map-pin" class="me-1"></i>{{ $university->state->name }}
                                </a>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Created At</label>
                        <p class="fw-semibold">{{ $university->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Last Updated</label>
                        <p class="fw-semibold">{{ $university->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Courses -->
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i data-feather="book" class="me-2"></i>
                    Courses ({{ $university->courses->count() }})
                </h5>
                <a href="{{ route('courses.create') }}?university_id={{ $university->id }}" class="btn btn-sm btn-primary">
                    <i data-feather="plus" class="me-1"></i> Add Course
                </a>
            </div>
            <div class="card-body">
                @if($university->courses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Course Name</th>
                                    <th>Level</th>
                                    <th>Duration</th>
                                    <th>Tuition Fee</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($university->courses as $course)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                                <i data-feather="book" class="text-primary" style="width: 14px; height: 14px;"></i>
                                            </div>
                                            <div class="fw-semibold">{{ $course->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            @if($course->level)
                                                {{ $course->level }}
                                            @else
                                                Not specified
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small">
                                            @if($course->duration_months)
                                                {{ $course->duration_months }} months
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            @if($course->tuition_fee)
                                                {{ $course->currency ?? 'USD' }} {{ number_format($course->tuition_fee, 2) }}
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-outline-primary">
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
                        <i data-feather="book" class="text-muted" style="width: 48px; height: 48px;"></i>
                        <h5 class="mt-3 mb-2">No courses found</h5>
                        <p class="text-muted mb-3">This university doesn't have any courses yet.</p>
                        <a href="{{ route('courses.create') }}?university_id={{ $university->id }}" class="btn btn-primary">
                            <i data-feather="plus" class="me-2"></i>Add First Course
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
                        <i data-feather="book-open" class="text-primary" style="width: 32px; height: 32px;"></i>
                    </div>
                    <h4 class="mb-1">{{ $university->name }}</h4>
                    <p class="text-muted">University Overview</p>
                </div>
                
                <div class="row g-3">
                    <div class="col-6">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="book" class="text-success mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">{{ $university->courses->count() }}</h5>
                                <small class="text-muted">Courses</small>
                            </div>
                        </x-card>
                    </div>
                    <div class="col-6">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="users" class="text-info mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">0</h5>
                                <small class="text-muted">Students</small>
                            </div>
                        </x-card>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Location Information -->
        @if($university->country || $university->state)
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="map-pin" class="me-2"></i>
                    Location Information
                </h5>
            </div>
            <div class="card-body">
                @if($university->country)
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                        <i data-feather="globe" class="text-primary" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">{{ $university->country->name }}</h6>
                        @if($university->country->phone_code)
                            <small class="text-muted">Phone Code: +{{ $university->country->phone_code }}</small>
                        @endif
                    </div>
                </div>
                @endif
                
                @if($university->state)
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                        <i data-feather="map-pin" class="text-success" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">{{ $university->state->name }}</h6>
                        @if($university->state->code)
                            <small class="text-muted">Code: {{ $university->state->code }}</small>
                        @endif
                    </div>
                </div>
                @endif
                
                <a href="{{ route('universities.edit', $university->id) }}" class="btn btn-sm btn-outline-primary w-100">
                    <i data-feather="edit-2" class="me-1"></i>Edit University Details
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
                    <a href="{{ route('courses.create') }}?university_id={{ $university->id }}" class="btn btn-outline-primary">
                        <i data-feather="plus" class="me-2"></i>Add Course
                    </a>
                    <a href="{{ route('universities.edit', $university->id) }}" class="btn btn-outline-secondary">
                        <i data-feather="edit-2" class="me-2"></i>Edit University
                    </a>
                    <form action="{{ route('universities.destroy', $university->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this university? This will also delete all associated courses.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i data-feather="trash-2" class="me-2"></i>Delete University
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
