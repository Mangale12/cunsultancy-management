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

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ isset($intake) ? 'Edit' : 'Add New' }} Intake
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ isset($intake) ? route('intakes.update', $intake->id) : route('intakes.store') }}" method="POST">
                @csrf
                @if(isset($intake))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="name" class="form-label">Intake Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" 
                               value="{{ old('name', $intake->name ?? '') }}" 
                               placeholder="e.g., Spring 2024, Fall 2024"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Enter the intake name (e.g., Spring 2024, Fall 2024, Summer 2024)</small>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('intakes.index') }}" class="btn btn-secondary">
                        <i data-feather="arrow-left" class="me-1" style="width: 14px; height: 14px;"></i> Back to List
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save" class="me-1" style="width: 14px; height: 14px;"></i>
                        {{ isset($intake) ? 'Update' : 'Save' }} Intake
                    </button>
                </div>
            </form>
        </div>
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
