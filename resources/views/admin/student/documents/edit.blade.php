@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Edit Document: {{ $document->title }}</h4>
        <a href="{{ route('students.documents.index', $student->id) }}" class="btn btn-sm btn-outline-secondary">
            <i data-feather="arrow-left" class="me-1"></i> Back to Documents
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('students.documents.update', [$student->id, $document->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="document_type" class="form-label">Document Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('document_type') is-invalid @enderror" 
                                            id="document_type" 
                                            name="document_type" 
                                            required>
                                        @foreach($documentTypes as $value => $label)
                                            <option value="{{ $value }}" 
                                                {{ old('document_type', $document->document_type) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('document_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Document Title <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $document->title) }}" 
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3">{{ old('notes', $document->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @can('verify', $document)
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_verified" 
                                       name="is_verified" 
                                       value="1"
                                       {{ $document->is_verified ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_verified">
                                    Mark as Verified
                                </label>
                            </div>
                            <small class="text-muted">Check this box to verify this document.</small>
                        </div>
                        @endcan

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('students.documents.show', [$student->id, $document->id]) }}" 
                                   class="btn btn-outline-secondary me-2"
                                   target="_blank">
                                    <i data-feather="eye" class="me-1"></i> Preview
                                </a>
                                <a href="{{ route('students.documents.download', [$student->id, $document->id]) }}" 
                                   class="btn btn-outline-secondary">
                                    <i data-feather="download" class="me-1"></i> Download
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save" class="me-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Document Information</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light p-3 rounded-circle me-3">
                            <i data-feather="file" class="text-primary" width="24" height="24"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $document->file_name }}</h6>
                            <div class="text-muted small">{{ $document->formatted_file_size }}</div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <span class="text-muted">Uploaded:</span>
                            <span class="float-end">{{ $document->created_at->format('M d, Y h:i A') }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="text-muted">Last Updated:</span>
                            <span class="float-end">{{ $document->updated_at->format('M d, Y h:i A') }}</span>
                        </li>
                        @if($document->is_verified)
                            <li class="mb-2">
                                <span class="text-muted">Verified:</span>
                                <span class="float-end">
                                    <span class="badge bg-success">
                                        <i data-feather="check-circle" class="me-1" width="12"></i> Verified
                                    </span>
                                </span>
                            </li>
                            @if($document->verified_at && $document->verifiedBy)
                                <li class="mb-2">
                                    <span class="text-muted">Verified By:</span>
                                    <span class="float-end">
                                        {{ $document->verifiedBy->name }}
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <span class="text-muted">Verified On:</span>
                                    <span class="float-end">
                                        {{ $document->verified_at->format('M d, Y h:i A') }}
                                    </span>
                                </li>
                            @endif
                        @endif
                    </ul>
                    
                    <hr>
                    
                    <div class="d-grid">
                        <form action="{{ route('students.documents.destroy', [$student->id, $document->id]) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this document? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i data-feather="trash-2" class="me-1"></i> Delete Document
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
