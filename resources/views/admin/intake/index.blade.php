@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Intake Management</h1>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ isset($intakeEdit) ? 'Edit Intake' : 'Add New Intake' }}
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ isset($intakeEdit) ? route('intakes.update', $intakeEdit->id) : route('intakes.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($intakeEdit)) @method('PUT') @endif
                
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Intake Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" 
                               placeholder="e.g. January - 2026" 
                               value="{{ old('name', $intakeEdit->name ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Intake Icon/Image</label>
                        <input type="file" name="image_path" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i data-feather="save" class="me-1"></i> 
                            {{ isset($intakeEdit) ? 'Update Intake' : 'Save Intake' }}
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
                            <th>Intake Details</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($intakes as $intake)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $intake->image_path ? asset('storage/'.$intake->image_path) : 'https://ui-avatars.com/api/?name='.$intake->name }}" 
                                         class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                    <div>
                                        <small class="text-muted">ID: #INT-{{ $intake->id }}</small>
                                        <div class="fw-bold">{{ $intake->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('intakes.edit', $intake->id) }}" class="btn btn-sm btn-light border">
                                    <i data-feather="edit" style="width: 14px;"></i>
                                </a>
                                <form action="{{ route('intakes.destroy', $intake->id) }}" method="POST" class="d-inline">
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
                            <td colspan="2" class="text-center text-muted py-4">No intakes found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection