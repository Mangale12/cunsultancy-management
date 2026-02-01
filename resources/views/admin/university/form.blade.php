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
                {{ isset($university) ? 'Edit' : 'Add New' }} University
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ isset($university) ? route('universities.update', $university->id) : route('universities.store') }}" method="POST">
                @csrf
                @if(isset($university))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">University Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" 
                               value="{{ old('name', $university->name ?? '') }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">University Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" 
                               value="{{ old('code', $university->code ?? '') }}" 
                               required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="countrySelect" class="form-label">Select Country</label>
                        <select class="form-select" id="countrySelect" name="country_id" required>
                            <option value="" selected disabled>Choose a country...</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $university->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="stateSelect" class="form-label">Select State</label>
                        <select class="form-select" id="stateSelect" name="state_id" required>
                            <option value="" selected disabled>Choose a state...</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}" {{ old('state_id', $university->state_id ?? '') == $state->id ? 'selected' : '' }}>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('state_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                  

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('universities.index') }}" class="btn btn-secondary">
                        <i data-feather="arrow-left" class="me-1" style="width: 14px; height: 14px;"></i> Back to List
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save" class="me-1" style="width: 14px; height: 14px;"></i>
                        {{ isset($university) ? 'Update' : 'Save' }} University
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

