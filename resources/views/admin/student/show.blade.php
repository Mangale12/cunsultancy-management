@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 bg-light min-vh-100">
    <div class="d-flex justify-content-between align-items-center py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Partners</a></li>
                <li class="breadcrumb-item"><a href="#">Student</a></li>
                <li class="breadcrumb-item active">{{ $student->id }}</li>
            </ol>
        </nav>
        <a href="{{ route('students.index') }}" class="btn btn-dark btn-sm rounded-pill px-3">
            <i data-feather="arrow-left" class="me-1"></i> Go Back
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-4">{{ $student->name }}</h5>
            <div class="row g-3 text-uppercase small">
                <div class="col-md-2">
                    <label class="text-muted d-block">Name</label>
                    <span class="fw-bold">{{ $student->name }}</span>
                </div>
                <div class="col-md-2">
                    <label class="text-muted d-block">Date of Birth</label>
                    <span class="fw-bold">{{ $student->date_of_birth }}</span>
                </div>
                <div class="col-md-2">
                    <label class="text-muted d-block">Passport Number</label>
                    <span class="fw-bold">{{ $student->passport_number ?? 'N/A' }}</span>
                </div>
                <div class="col-md-2">
                    <label class="text-muted d-block">Email</label>
                    <span class="fw-bold text-lowercase">{{ $student->email }}</span>
                </div>
                <div class="col-md-2">
                    <label class="text-muted d-block">Mobile Number</label>
                    <span class="fw-bold">{{ $student->phone }}</span>
                </div>
                <div class="col-md-2">
                    <label class="text-muted d-block">Nationality</label>
                    <span class="fw-bold">{{ $student->country->name ?? 'Nepal' }}</span>
                </div>
                <div class="col-md-2 mt-3">
                    <label class="text-muted d-block">State</label>
                    <span class="fw-bold">{{ $student->state->name ?? 'Eastern' }}</span>
                </div>
                <div class="col-md-2 mt-3">
                    <label class="text-muted d-block">Study Country</label>
                    <span class="fw-bold">United Kingdom</span>
                </div>
                <div class="col-md-2 mt-3">
                    <label class="text-muted d-block">Created At</label>
                    <span class="fw-bold">{{ $student->created_at }}</span>
                </div>
                <div class="col-md-2 mt-3">
                    <label class="text-muted d-block">Status</label>
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'in_review' => 'info',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            'completed' => 'success'
                        ];
                        $statusColor = $statusColors[$student->application_status] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $statusColor }}">
                        {{ $student->application_status_label }}
                    </span>
                    @if($student->application_completed_at)
                        <div class="small text-muted mt-1">
                            Completed: {{ $student->application_completed_at->format('M d, Y') }}
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="mt-4 d-flex justify-content-between align-items-center">
                <div>
                    <button class="btn btn-success btn-sm me-2">Assessment Requested</button>
                    <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#applyApplicationModal">
                        Apply More Application
                    </button>
                </div>
                
                <div class="dropdown
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Update Application Status
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 300px;">
                        <form action="{{ route('students.complete-application', $student) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="status" class="form-label small text-muted">Status</label>
                                <select name="status" id="status" class="form-select form-select-sm" required>
                                    <option value="pending" {{ $student->application_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_review" {{ $student->application_status === 'in_review' ? 'selected' : '' }}>In Review</option>
                                    <option value="approved" {{ $student->application_status === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $student->application_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="completed" {{ $student->application_status === 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label small text-muted">Notes</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control form-control-sm" placeholder="Add any notes about this status change...">{{ old('notes', $student->application_notes) }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">Update Status</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-primary mb-0">Applications</h6>
            <div class="text-muted small">
                @if($student->application_completed_at)
                    Last updated: {{ $student->application_completed_at->diffForHumans() }}
                @endif
            </div>
        </div>
        <div class="table-responsive shadow-sm bg-white rounded">
            <table class="table table-bordered table-sm align-middle mb-0 small">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="ps-2">S.N.</th>
                        <th>Upload Document</th>
                        <th>Course Name</th>
                        <th>University</th>
                        <th>Country</th>
                        <th>Intake</th>
                        <th>Current Status</th>
                        <th>Created On</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->applications as $key => $application)
                    <tr>
                        <td class="ps-2">{{ $key + 1 }}.</td>
                        <td><button class="btn btn-primary btn-xs py-0 px-1" style="font-size: 10px;">Upload Document</button></td>
                        <td class="text-primary fw-bold"><a href="{{ route('student-apply-application.show', $application->id)}}">{{ $application->course->name ?? 'N/A' }}</a></td>
                        <td>{{ $application->university->name ?? 'N/A' }}</td>
                        <td>{{ $application->university->country->name ?? 'N/A' }}</td>
                        <td>{{ $application->intake->name ?? 'N/A' }}</td>
                        <td class="text-primary">{{ $application->application_status_label ?? 'N/A' }}</td>
                        <td>{{ $application->created_at ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mb-4">
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-primary mb-0">Documents</h6>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                    <i data-feather="upload-cloud" class="me-1" style="width: 14px;"></i> Upload New Document
                </button>
            </div>
        </div>
        <div class="table-responsive shadow-sm bg-white rounded">
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <table class="table table-bordered table-sm align-middle mb-0 small">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="ps-2">S.N.</th>
                        <th>Name</th>
                        <th>Preview</th>
                        <th>File Name</th>
                        <th>Download</th>
                        <th>Uploaded On</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->documents as $key => $doc)
                    <tr>
                        <td class="ps-2">{{ $key + 1 }}.</td>
                        <td>{{ $doc->title }}</td>
                        <td><a href="#" class="text-primary text-decoration-none">Preview</a></td>
                        <td class="text-muted">{{ $doc->file_name }}</td>
                        <td><a href="#" class="text-primary text-decoration-none">Download</a></td>
                        <td>{{ $doc->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mb-4">
        <h6 class="fw-bold mb-3 text-primary">Remarks Timeline</h6>
        <div class="p-3 bg-white shadow-sm rounded border">
            <span class="badge bg-primary mb-2 p-2">University of East London</span>
            <p class="small text-muted mb-0">For any further correspondence it will be on application to application basis. Kindly click on the above applied university for any further process.</p>
        </div>
    </div>
</div>

<div class="modal fade" id="applyApplicationModal" tabindex="-1" aria-labelledby="applyApplicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title text-primary fw-bold" id="applyApplicationModalLabel">Kindly Select Your Priority University</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="applyApplicationForm" action="{{ route('student-apply-application.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">University <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="university_id" id="modal_university" required>
                                <option value="" selected disabled>Select University</option>
                                @foreach($universities as $uni)
                                    <option value="{{ $uni->id }}">{{ $uni->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Year <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="application_year_id" required>
                                <option value="" selected disabled>Select Year</option>
                                @foreach($applicationYears as $applicationYear)
                                    <option value="{{ $applicationYear->id }}">{{ $applicationYear->year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Intake <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="intake_id" required>
                                <option value="" selected disabled>Select Intake</option>
                                @foreach($intakes as $intake)
                                    <option value="{{ $intake->id }}">{{ $intake->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Course <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="course_id" id="modal_course" required>
                                <option value="" selected disabled>Select Course</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm px-4">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h6 class="modal-title text-primary fw-bold">Upload Student Document</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="documentUploadForm" method="POST" action="{{ route('students.upload-document', $student->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div id="uploadAlert" class="alert d-none"></div>
                    
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Document Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. Passport Copy" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Document Type <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" name="document_type_id" required>
                                    <option value="" selected disabled>Select Type</option>
                                   @foreach($documentTypes as $documentType)
                                        <option value="{{ $documentType->id }}">{{ $documentType->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                   <div class="mb-3">
                        <label class="form-label small fw-bold">Document File <span class="text-danger">*</span></label>
                        <input type="file" name="document_file" class="form-control form-control-sm" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Notes (Optional)</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="3" placeholder="Any details about this file..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4" id="uploadBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Upload Document</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const form = $('#documentUploadForm');
    const alertBox = $('#uploadAlert');
    const fileInput = $('#documentFile');
    const dropZone = $('#dropZone');
    const browseBtn = $('#browseBtn');
    const uploadBtn = $('#uploadBtn');
    const btnText = $('.btn-text');
    const spinner = $('.spinner-border');
    const fileInfo = $('#fileInfo');

    // Handle file selection
    browseBtn.on('click', function() {
        fileInput.click();
    });

    // Handle file selection via input
    fileInput.on('change', function() {
        updateFileInfo(this.files[0]);
    });

    // Handle drag and drop
    dropZone.on('dragover', function(e) {
        e.preventDefault();
        dropZone.addClass('border-primary bg-light');
    });

    dropZone.on('dragleave', function(e) {
        e.preventDefault();
        dropZone.removeClass('border-primary bg-light');
    });

    dropZone.on('drop', function(e) {
        e.preventDefault();
        dropZone.removeClass('border-primary bg-light');
        
        const files = e.originalEvent.dataTransfer.files;
        if (files.length) {
            fileInput[0].files = files;
            updateFileInfo(files[0]);
        }
    });

    // Update file info display
    function updateFileInfo(file) {
        if (file) {
            const fileSize = (file.size / (1024 * 1024)).toFixed(2);
            fileInfo.html(`
                <div class="d-flex align-items-center justify-content-between bg-light p-2 rounded">
                    <div class="d-flex align-items-center">
                        <i data-feather="file" class="me-2"></i>
                        <span>${file.name}</span>
                    </div>
                    <small class="text-muted">${fileSize} MB</small>
                </div>
            `);
            feather.replace();
        }
    }

    // Handle form submission
    form.on('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (!fileInput[0].files.length) {
            showAlert('Please select a file to upload.', 'danger');
            return;
        }

        // Show loading state
        uploadBtn.prop('disabled', true);
        spinner.removeClass('d-none');
        btnText.text('Uploading...');

        // Create FormData object
        const formData = new FormData(this);

        // Submit via AJAX
        $.ajax({
            url: '{{ route('student-documents.store') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showAlert('Document uploaded successfully!', 'success');
                form[0].reset();
                fileInfo.empty();
                
                // Reload the page after 1.5 seconds to show the new document
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while uploading the document.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    // Handle validation errors
                    const errors = [];
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errors.push(value[0]);
                    });
                    errorMessage = errors.join('<br>');
                }
                showAlert(errorMessage, 'danger');
            },
            complete: function() {
                // Reset button state
                uploadBtn.prop('disabled', false);
                spinner.addClass('d-none');
                btnText.text('Upload Document');
            }
        });
    });

    // Show alert message
    function showAlert(message, type) {
        alertBox.removeClass('d-none alert-success alert-danger')
               .addClass(`alert-${type}`)
               .html(`
                   <div class="d-flex align-items-center">
                       <i data-feather="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="me-2"></i>
                       <span>${message}</span>
                   </div>
               `);
        feather.replace();
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            alertBox.fadeOut(300, function() {
                $(this).addClass('d-none').show();
            });
        }, 5000);
    }
});
</script>
<style>
.file-upload-wrapper {
    cursor: pointer;
}
.border-dashed {
    border-style: dashed !important;
}
#dropZone:hover {
    background-color: #f8f9fa !important;
}
.modal-fullscreen {
    max-width: 95%;
    margin: 1.75rem auto;
}
</style>
@endpush
<style>
    .bg-primary { background-color: #0025cc !important; }
    .text-primary { color: #0025cc !important; }
    .btn-primary { background-color: #0025cc; border: none; }
    .table-bordered > :not(caption) > * > * { border-width: 0 1px; border-color: #dee2e6; }
    .breadcrumb-item + .breadcrumb-item::before { content: ">"; }
    .btn-xs { padding: 1px 5px; font-size: 11px; }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
    // When University is selected in the modal
    $('#modal_university').on('change', function() {
        var uniId = $(this).val();
        var courseSelect = $('#modal_course');
        
        courseSelect.prop('disabled', true).html('<option>Loading...</option>');

        if(uniId) {
            $.ajax({
                url: '/get-courses-by-university/' + uniId, // Create this route in web.php
                type: "GET",
                success: function(data) {
                    courseSelect.prop('disabled', false).empty();
                    courseSelect.append('<option value="" selected disabled>Select Course</option>');
                    $.each(data, function(key, value) {
                        courseSelect.append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        }
    });
});
</script>
@endpush
@endsection