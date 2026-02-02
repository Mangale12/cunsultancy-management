@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Document Type Management</h1>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ isset($documentTypeEdit) ? 'Edit Document Type' : 'Add New Document Type' }}
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
            <form action="{{ isset($documentTypeEdit) ? route('document-types.update', $documentTypeEdit->id) : route('document-types.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($documentTypeEdit)) @method('PUT') @endif
                
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Document Type <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" 
                               placeholder="e.g. January - 2026" 
                               value="{{ old('name', $documentTypeEdit->name ?? '') }}" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i data-feather="save" class="me-1"></i> 
                            {{ isset($documentTypeEdit) ? 'Update Document Type' : 'Save Document Type' }}
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
                            <th>Document Type</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documentTypes as $documentType)
                        <tr>
                            <td>{{ $documentType->name }}</td>
                            <td class="text-end">
                                <a href="{{ route('document-types.edit', $documentType->id) }}" class="btn btn-sm btn-light border">
                                    <i data-feather="edit" style="width: 14px;"></i>
                                </a>
                                <form action="{{ route('document-types.destroy', $documentType->id) }}" method="POST" class="d-inline">
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
                            <td colspan="2" class="text-center text-muted py-4">No document type found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection