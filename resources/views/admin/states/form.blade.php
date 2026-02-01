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
                {{ isset($state) ? 'Edit' : 'Add New' }} State
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ isset($state) ? route('states.update', $state->id) : route('states.store') }}" method="POST">
                @csrf
                @if(isset($state))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Country Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" 
                               value="{{ old('name', $state->name ?? '') }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="countrySelect" class="form-label">Select Country</label>
                        <select class="form-select" id="countrySelect" name="country_id" required>
                            <option value="" selected disabled>Choose a country...</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $state->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                  

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('states.index') }}" class="btn btn-secondary">
                        <i data-feather="arrow-left" class="me-1" style="width: 14px; height: 14px;"></i> Back to List
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save" class="me-1" style="width: 14px; height: 14px;"></i>
                        {{ isset($state) ? 'Update' : 'Save' }} State
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

