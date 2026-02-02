@extends('layouts.app')

@section('title', $country->name)

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
    title="{{ $country->name }}"
    subtitle="View country details and manage associated states and students."
    :actions="
        '<a href=\"' . route('countries.index') . '\" class=\"btn btn-outline-secondary me-2\">
            <i data-feather=\"arrow-left\" class=\"me-2\"></i> Back to Countries
        </a>
        <a href=\"' . route('countries.edit', $country->id) . '\" class=\"btn btn-primary\">
            <i data-feather=\"edit-2\" class=\"me-2\"></i> Edit Country
        </a>'
    "
/>

<!-- Country Details -->
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Basic Information -->
        <x-card>
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="globe" class="me-2"></i>
                    Basic Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Country Name</label>
                        <p class="fw-semibold">{{ $country->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Country Code</label>
                        <p class="fw-semibold">
                            @if($country->code)
                                <span class="badge bg-info">{{ $country->code }}</span>
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Currency</label>
                        <p class="fw-semibold">
                            @if($country->currency)
                                {{ $country->currency }}
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Phone Code</label>
                        <p class="fw-semibold">
                            @if($country->phone_code)
                                +{{ $country->phone_code }}
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Status</label>
                        <p class="fw-semibold">
                            @if($country->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Created At</label>
                        <p class="fw-semibold">{{ $country->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- States -->
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i data-feather="map-pin" class="me-2"></i>
                    States ({{ $country->states->count() }})
                </h5>
                <a href="{{ route('states.create') }}?country_id={{ $country->id }}" class="btn btn-sm btn-primary">
                    <i data-feather="plus" class="me-1"></i> Add State
                </a>
            </div>
            <div class="card-body">
                @if($country->states->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>State Name</th>
                                    <th>Code</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($country->states as $state)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                                <i data-feather="map-pin" class="text-primary" style="width: 14px; height: 14px;"></i>
                                            </div>
                                            <div class="fw-semibold">{{ $state->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($state->code)
                                            <span class="badge bg-secondary">{{ $state->code }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('states.edit', $state->id) }}" class="btn btn-sm btn-outline-primary">
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
                        <i data-feather="map-pin" class="text-muted" style="width: 48px; height: 48px;"></i>
                        <h5 class="mt-3 mb-2">No states found</h5>
                        <p class="text-muted mb-3">This country doesn't have any states yet.</p>
                        <a href="{{ route('states.create') }}?country_id={{ $country->id }}" class="btn btn-primary">
                            <i data-feather="plus" class="me-2"></i>Add First State
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
                        <i data-feather="globe" class="text-primary" style="width: 32px; height: 32px;"></i>
                    </div>
                    <h4 class="mb-1">{{ $country->name }}</h4>
                    <p class="text-muted">Country Overview</p>
                </div>
                
                <div class="row g-3">
                    <div class="col-6">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="map-pin" class="text-primary mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">{{ $country->states->count() }}</h5>
                                <small class="text-muted">States</small>
                            </div>
                        </x-card>
                    </div>
                    <div class="col-6">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="users" class="text-success mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">{{ $country->students->count() }}</h5>
                                <small class="text-muted">Students</small>
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
                    <a href="{{ route('states.create') }}?country_id={{ $country->id }}" class="btn btn-outline-primary">
                        <i data-feather="plus" class="me-2"></i>Add New State
                    </a>
                    <a href="{{ route('countries.edit', $country->id) }}" class="btn btn-outline-secondary">
                        <i data-feather="edit-2" class="me-2"></i>Edit Country
                    </a>
                    <form action="{{ route('countries.destroy', $country->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this country? This will also delete all associated states and students.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i data-feather="trash-2" class="me-2"></i>Delete Country
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
