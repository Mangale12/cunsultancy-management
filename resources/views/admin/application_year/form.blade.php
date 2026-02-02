@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ isset($applicationYear) ? 'Edit Application Year' : 'Add New Application Year' }}
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ isset($applicationYear) ? route('application-years.update', $applicationYear->id) : route('application-years.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($applicationYear)) @method('PUT') @endif
                
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Application Year <span class="text-danger">*</span></label>
                        <input type="text" name="year" class="form-control @error('year') is-invalid @enderror" 
                               placeholder="e.g. 2024, 2025" 
                               value="{{ old('year', $applicationYear->year ?? '') }}" required>
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Application Year Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                               value="{{ old('start_date', $applicationYear->start_date ?? '') }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Application Year End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                               value="{{ old('end_date', $applicationYear->end_date ?? '') }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input @error('is_active') is-invalid @enderror" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $applicationYear->is_active ?? 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active for Applications</label>
                        </div>
                        @error('is_active')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Enable this year for student applications</small>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i data-feather="save" class="me-1"></i> 
                            {{ isset($applicationYear) ? 'Update Application Year' : 'Save Application Year' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('application-years.index') }}" class="btn btn-secondary">
            <i data-feather="arrow-left" class="me-1"></i> Back to List
        </a>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (feather) {
            feather.replace({ width: 14, height: 14 });
        }
    });
</script>
@endpush
@endsection
