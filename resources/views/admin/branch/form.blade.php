@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ isset($branch) ? 'Edit' : 'Add New' }} Branch
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ isset($branch) ? route('branches.update', $branch->id) : route('branches.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($branch)) @method('PUT') @endif

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $branch->name ?? '') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Branch Code</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $branch->code ?? '') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Manager Name</label>
                        <input type="text" name="manager_name" class="form-control" value="{{ old('manager_name', $branch->manager_name ?? '') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Country <span class="text-danger">*</span></label>
                        <select name="country_id" id="country_id" class="form-select" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ (old('country_id', $branch->country_id ?? '') == $country->id) ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">State <span class="text-danger">*</span></label>
                        <select name="state_id" id="state_id" class="form-select" required>
                            <option value="">Select State</option>
                            @if(isset($states))
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $branch->email ?? '') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $branch->phone ?? '') }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Full Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $branch->address ?? '') }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch mt-4">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $branch->is_active ?? 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active Status</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('branches.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save" class="me-1"></i> {{ isset($branch) ? 'Update' : 'Save' }} Branch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Dynamic State Loading
    $('#country_id').on('change', function() {
        var countryId = $(this).val();
        $('#state_id').html('<option value="">Loading...</option>');
        if(countryId) {
            $.ajax({
                url: '/get-states/' + countryId,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('#state_id').empty().append('<option value="">Select State</option>');
                    $.each(data, function(key, value) {
                        $('#state_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        }
    });
});
</script>
@endpush
@endsection