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
                {{ isset($country) ? 'Edit' : 'Add New' }} Country
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ isset($country) ? route('countries.update', $country->id) : route('countries.store') }}" method="POST">
                @csrf
                @if(isset($country))
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Country Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" 
                               value="{{ old('name', $country->name ?? '') }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">Country Code (ISO)</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" 
                               value="{{ old('code', $country->code ?? '') }}"
                               maxlength="3">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="currency" class="form-label">Currency</label>
                        <input type="text" class="form-control @error('currency') is-invalid @enderror" 
                               id="currency" name="currency" 
                               value="{{ old('currency', $country->currency ?? '') }}">
                        @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phone_code" class="form-label">Phone Code</label>
                        <div class="input-group">
                            <span class="input-group-text">+</span>
                            <input type="text" class="form-control @error('phone_code') is-invalid @enderror" 
                                   id="phone_code" name="phone_code" 
                                   value="{{ old('phone_code', $country->phone_code ?? '') }}">
                        </div>
                        @error('phone_code')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input @error('is_active') is-invalid @enderror" 
                               type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ (old('is_active', $country->is_active ?? true) ? 'checked' : '') }}>
                        <label class="form-check-label" for="is_active">Active for Consultancy</label>
                    </div>
                    @error('is_active')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('countries.index') }}" class="btn btn-secondary">
                        <i data-feather="arrow-left" class="me-1" style="width: 14px; height: 14px;"></i> Back to List
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save" class="me-1" style="width: 14px; height: 14px;"></i>
                        {{ isset($country) ? 'Update' : 'Save' }} Country
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

