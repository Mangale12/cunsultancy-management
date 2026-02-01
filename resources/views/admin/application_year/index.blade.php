@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Application Year Management</h1>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ isset($applicationYearEdit) ? 'Edit Application Year' : 'Add New Application Year' }}
            </h6>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="{{ isset($applicationYearEdit) ? route('application-years.update', $applicationYearEdit->id) : route('application-years.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($applicationYearEdit)) @method('PUT') @endif
                
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Application Year <span class="text-danger">*</span></label>
                        <input type="text" name="year" class="form-control" 
                               placeholder="e.g. January - 2026" 
                               value="{{ old('year', $applicationYearEdit->year ?? '') }}" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Application Year Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control" 
                               placeholder="e.g. January - 2026" 
                               value="{{ old('start_date', $applicationYearEdit->start_date ?? '') }}" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Application Year End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control" 
                               placeholder="e.g. January - 2026" 
                               value="{{ old('end_date', $applicationYearEdit->end_date ?? '') }}" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i data-feather="save" class="me-1"></i> 
                            {{ isset($applicationYearEdit) ? 'Update Application Year' : 'Save Application Year' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Application Year Details</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applicationYears as $applicationYear)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $applicationYear->image_path ? asset('storage/'.$applicationYear->image_path) : 'https://ui-avatars.com/api/?name='.$applicationYear->year }}" 
                                         class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                    <div>
                                        <small class="text-muted">ID: #APP-{{ $applicationYear->id }}</small>
                                        <div class="fw-bold">{{ $applicationYear->year }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('application-years.edit', $applicationYear->id) }}" class="btn btn-sm btn-light border">
                                    <i data-feather="edit" style="width: 14px;"></i>
                                </a>
                                <form action="{{ route('application-years.destroy', $applicationYear->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger" 
                                            onclick="return confirm('Are you sure?')">
                                        <i data-feather="trash-2" style="width: 14px;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">No application years found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection