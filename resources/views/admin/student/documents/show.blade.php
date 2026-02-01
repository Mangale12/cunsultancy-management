@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Document: {{ $document->title }}</h4>
        <div>
            <a href="{{ route('students.documents.index', $student->id) }}" class="btn btn-sm btn-outline-secondary me-2">
                <i data-feather="arrow-left" class="me-1"></i> Back to Documents
            </a>
            @can('update', $document)
            <a href="{{ route('students.documents.edit', [$student->id, $document->id]) }}" class="btn btn-sm btn-outline-primary">
                <i data-feather="edit" class="me-1"></i> Edit
            </a>
            @endcan
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8
        ">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    @php
                        $fileExtension = strtolower(pathinfo($document->file_name, PATHINFO_EXTENSION));
                        $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']);
                        $isPdf = $fileExtension === 'pdf';
                        $isDocument = in_array($fileExtension, ['doc', 'docx', 'txt']);
                    @endphp

                    @if($isImage)
                        <div class="text-center mb-4">
                            <img src="{{ Storage::url($document->file_path) }}" 
                                 alt="{{ $document->title }}" 
                                 class="img-fluid rounded border"
                                 style="max-height: 70vh;">
                        </div>
                    @elseif($isPdf)
                        <div class="embed-responsive" style="height: 80vh;">
                            <iframe src="{{ Storage::url($document->file_path) }}#toolbar=0" 
                                    class="w-100 h-100 border rounded"
                                    frameborder="0"></iframe>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light p-5 rounded">
                                <i data-feather="file" width="48" height="48" class="text-muted mb-3"></i>
                                <h5>Preview not available</h5>
                                <p class="text-muted">This file type cannot be previewed in the browser.</p>
                                <a href="{{ route('students.documents.download', [$student->id, $document->id]) }}" 
                                   class="btn btn-primary mt-2">
                                    <i data-feather="download" class="me-1"></i> Download File
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-{{ $document->is_verified ? 'success' : 'secondary' }} me-2">
                                <i data-feather="{{ $document->is_verified ? 'check-circle' : 'alert-circle' }}" class="me-1" width="14"></i>
                                {{ $document->is_verified ? 'Verified' : 'Not Verified' }}
                            </span>
                            <span class="text-muted small">{{ $document->document_type_label }} • {{ $document->formatted_file_size }}</span>
                        </div>
                        <div>
                            <a href="{{ route('students.documents.download', [$student->id, $document->id]) }}" 
                               class="btn btn-sm btn-outline-secondary me-2">
                                <i data-feather="download" class="me-1" width="14"></i> Download
                            </a>
                            @can('verify', $document)
                                <form action="{{ route('students.documents.verify', [$student->id, $document->id]) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to {{ $document->is_verified ? 'unverify' : 'verify' }} this document?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $document->is_verified ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                        <i data-feather="{{ $document->is_verified ? 'x' : 'check' }}-circle" class="me-1" width="14"></i>
                                        {{ $document->is_verified ? 'Mark as Unverified' : 'Mark as Verified' }}
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Document Details</h5>
                </div>
                <div class="card-body">
                    <dl class="mb-0">
                        <dt>Title</dt>
                        <dd class="mb-3">{{ $document->title }}</dd>
                        
                        <dt>Document Type</dt>
                        <dd class="mb-3">
                            <span class="badge bg-light text-dark">
                                {{ $document->document_type_label }}
                            </span>
                        </dd>
                        
                        <dt>File Name</dt>
                        <dd class="mb-3 text-truncate" title="{{ $document->file_name }}">
                            <i data-feather="file" class="text-muted me-1" width="16"></i>
                            {{ $document->file_name }}
                        </dd>
                        
                        <dt>File Size</dt>
                        <dd class="mb-3">{{ $document->formatted_file_size }}</dd>
                        
                        <dt>Uploaded</dt>
                        <dd class="mb-3">
                            {{ $document->created_at->format('M d, Y h:i A') }}
                            <span class="text-muted">({{ $document->created_at->diffForHumans() }})</span>
                        </dd>
                        
                        @if($document->updated_at->gt($document->created_at))
                            <dt>Last Updated</dt>
                            <dd class="mb-3">
                                {{ $document->updated_at->format('M d, Y h:i A') }}
                                <span class="text-muted">({{ $document->updated_at->diffForHumans() }})</span>
                            </dd>
                        @endif
                        
                        @if($document->is_verified && $document->verified_at)
                            <dt>Verified</dt>
                            <dd class="mb-3">
                                {{ $document->verified_at->format('M d, Y h:i A') }}
                                @if($document->verifiedBy)
                                    <div class="text-muted small">
                                        by {{ $document->verifiedBy->name }}
                                    </div>
                                @endif
                            </dd>
                        @endif
                        
                        @if($document->notes)
                            <dt>Notes</dt>
                            <dd class="mb-0">
                                <div class="bg-light p-3 rounded">
                                    {!! nl2br(e($document->notes)) !!}
                                </div>
                            </dd>
                        @endif
                    </dl>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('students.documents.download', [$student->id, $document->id]) }}" 
                           class="btn btn-outline-primary">
                            <i data-feather="download" class="me-1"></i> Download
                        </a>
                        
                        @can('update', $document)
                            <a href="{{ route('students.documents.edit', [$student->id, $document->id]) }}" 
                               class="btn btn-outline-secondary">
                                <i data-feather="edit" class="me-1"></i> Edit Details
                            </a>
                            
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteDocumentModal">
                                <i data-feather="trash-2" class="me-1"></i> Delete Document
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@can('delete', $document)
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteDocumentModal" tabindex="-1" aria-labelledby="deleteDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDocumentModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this document? This action cannot be undone.</p>
                <div class="alert alert-warning mb-0">
                    <i data-feather="alert-triangle" class="me-1"></i>
                    <strong>Warning:</strong> The file will be permanently removed from the server.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i data-feather="x" class="me-1"></i> Cancel
                </button>
                <form action="{{ route('students.documents.destroy', [$student->id, $document->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i data-feather="trash-2" class="me-1"></i> Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection
