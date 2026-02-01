@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">{{ $student->name }} - Documents</h4>
        <div>
            <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-outline-secondary me-2">
                <i data-feather="arrow-left" class="me-1"></i> Back to Student
            </a>
            <a href="{{ route('students.documents.create', $student->id) }}" class="btn btn-sm btn-primary">
                <i data-feather="upload" class="me-1"></i> Upload Document
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Document</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Uploaded</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $document)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @php
                                                $icon = match($document->document_type) {
                                                    'passport' => 'file-text',
                                                    'transcript' => 'file-text',
                                                    'ielts' => 'award',
                                                    'recommendation' => 'file-text',
                                                    'sop' => 'file-text',
                                                    'financial' => 'dollar-sign',
                                                    default => 'file'
                                                };
                                            @endphp
                                            <i data-feather="{{ $icon }}" class="text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $document->title }}</div>
                                            <div class="text-muted small">{{ $document->file_name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $document->document_type_label }}</td>
                                <td>{{ $document->formatted_file_size }}</td>
                                <td>{{ $document->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($document->is_verified)
                                        <span class="badge bg-success">
                                            <i data-feather="check-circle" class="me-1" width="14"></i> Verified
                                        </span>
                                        @if($document->verified_at)
                                            <div class="text-muted small">
                                                {{ $document->verified_at->format('M d, Y') }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i data-feather="clock" class="me-1" width="14"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('students.documents.show', [$student->id, $document->id]) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           target="_blank"
                                           data-bs-toggle="tooltip" 
                                           title="Preview">
                                            <i data-feather="eye" width="14"></i>
                                        </a>
                                        <a href="{{ route('students.documents.download', [$student->id, $document->id]) }}" 
                                           class="btn btn-sm btn-outline-secondary"
                                           data-bs-toggle="tooltip" 
                                           title="Download">
                                            <i data-feather="download" width="14"></i>
                                        </a>
                                        <a href="{{ route('students.documents.edit', [$student->id, $document->id]) }}" 
                                           class="btn btn-sm btn-outline-info"
                                           data-bs-toggle="tooltip" 
                                           title="Edit">
                                            <i data-feather="edit" width="14"></i>
                                        </a>
                                        <form action="{{ route('students.documents.destroy', [$student->id, $document->id]) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this document?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="tooltip" 
                                                    title="Delete">
                                                <i data-feather="trash-2" width="14"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">No documents found.</div>
                                    <a href="{{ route('students.documents.create', $student->id) }}" class="btn btn-sm btn-primary mt-2">
                                        <i data-feather="upload" class="me-1"></i> Upload Your First Document
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($documents->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
