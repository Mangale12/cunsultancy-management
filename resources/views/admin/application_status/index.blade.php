@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Application Status Management</h1>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ isset($applicationStatusEdit) ? 'Edit Application Status' : 'Add New Application Status' }}
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
            <form action="{{ isset($applicationStatusEdit) ? route('application-status.update', $applicationStatusEdit->id) : route('application-status.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($applicationStatusEdit)) @method('PUT') @endif
                
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Application Status <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" 
                               placeholder="e.g. January - 2026" 
                               value="{{ old('name', $applicationStatusEdit->name ?? '') }}" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Application Status Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" 
                               placeholder="e.g. January - 2026" 
                               value="{{ old('code', $applicationStatusEdit->code ?? '') }}" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i data-feather="save" class="me-1"></i> 
                            {{ isset($applicationStatusEdit) ? 'Update Application Status' : 'Save Application Status' }}
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
                            <th>Application Status</th>
                            <th>Application Status Code</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applicationStatus as $applicationStatus)
                        <tr>
                            <td>{{ $applicationStatus->name }}</td>
                            <td>{{ $applicationStatus->code }}</td>
                            <td class="text-end">
                                <a href="{{ route('application-status.edit', $applicationStatus->id) }}" class="btn btn-sm btn-light border">
                                    <i data-feather="edit" style="width: 14px;"></i>
                                </a>
                                <form action="{{ route('application-status.destroy', $applicationStatus->id) }}" method="POST" class="d-inline">
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
                            <td colspan="3" class="text-center text-muted py-4">No application status found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection