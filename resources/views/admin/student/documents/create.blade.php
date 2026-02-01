@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Upload Document for {{ $student->name }}</h4>
        <a href="{{ route('students.documents.index', $student->id) }}" class="btn btn-sm btn-outline-secondary">
            <i data-feather="arrow-left" class="me-1"></i> Back to Documents
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('students.documents.store', $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="document_type" class="form-label">Document Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('document_type') is-invalid @enderror" 
                                    id="document_type" 
                                    name="document_type" 
                                    required>
                                <option value="" disabled selected>Select document type</option>
                                @foreach($documentTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('document_type') == $value ? 'selected' : '' }}>
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
                                   value="{{ old('title') }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">A descriptive name for this document</small>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="document" class="form-label">Select File <span class="text-danger">*</span></label>
                    <div class="file-upload-wrapper" style="cursor: pointer;">
                        <div class="border rounded p-4 text-center @error('document') border-danger @else border-dashed @enderror" 
                             style="border-style: dashed !important;"
                             id="dropZone">
                            <div id="filePreview" class="mb-3 d-none">
                                <i data-feather="file" width="48" height="48" class="text-primary"></i>
                                <div class="mt-2" id="fileName"></div>
                                <div class="text-muted small" id="fileSize"></div>
                            </div>
                            <div id="uploadPrompt">
                                <i data-feather="upload" width="48" height="48" class="text-muted"></i>
                                <h5 class="mt-2">Drag & drop your file here</h5>
                                <p class="text-muted">or</p>
                                <button type="button" class="btn btn-outline-primary" id="browseBtn">
                                    <i data-feather="folder" class="me-1"></i> Browse Files
                                </button>
                                <div class="text-muted small mt-2">
                                    Max file size: 10MB. Supported formats: PDF, JPG, PNG, DOC, DOCX
                                </div>
                            </div>
                            <input type="file" 
                                   class="d-none" 
                                   id="document" 
                                   name="document" 
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" 
                                   required>
                        </div>
                        @error('document')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label">Notes (Optional)</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" 
                              name="notes" 
                              rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Add any additional notes about this document</small>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="reset" class="btn btn-light me-2">
                        <i data-feather="x" class="me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="upload" class="me-1"></i> Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-dashed {
        border-style: dashed !important;
    }
    .file-upload-wrapper {
        transition: all 0.3s ease;
    }
    .file-upload-wrapper:hover {
        transform: translateY(-2px);
    }
    #dropZone.drag-over {
        background-color: rgba(13, 110, 253, 0.05);
        border-color: #0d6efd !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('document');
    const browseBtn = document.getElementById('browseBtn');
    const filePreview = document.getElementById('filePreview');
    const uploadPrompt = document.getElementById('uploadPrompt');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');

    // Handle drag and drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dropZone.classList.add('drag-over');
    }

    function unhighlight() {
        dropZone.classList.remove('drag-over');
    }

    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    // Handle file selection via button
    browseBtn.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        if (files.length > 0) {
            const file = files[0];
            
            // Update file info display
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            
            // Show preview and hide upload prompt
            filePreview.classList.remove('d-none');
            uploadPrompt.classList.add('d-none');
            
            // Update file input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Form reset handling
    const form = document.querySelector('form');
    form.addEventListener('reset', function() {
        filePreview.classList.add('d-none');
        uploadPrompt.classList.remove('d-none');
        fileInput.value = '';
    });
});
</script>
@endpush
