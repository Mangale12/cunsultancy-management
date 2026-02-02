@extends('layouts.app')

@section('title', $student->first_name . ' ' . $student->last_name)

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i data-feather="check-circle" class="me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i data-feather="alert-circle" class="me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<x-page-header 
    title="{{ $student->first_name }} {{ $student->last_name }}"
    subtitle="View student details and manage applications, documents, and academic records."
    :actions="
        '<a href=\"' . route('students.index') . '\" class=\"btn btn-outline-secondary me-2\">
            <i data-feather=\"arrow-left\" class=\"me-2\"></i> Back to Students
        </a>
        <a href=\"' . route('students.edit', $student->id) . '\" class=\"btn btn-primary\">
            <i data-feather=\"edit-2\" class=\"me-2\"></i> Edit Student
        </a>'
    "
/>

    <!-- Student Details -->
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Basic Information -->
        <x-card>
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="user" class="me-2"></i>
                    Personal Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Full Name</label>
                        <p class="fw-semibold">{{ $student->first_name }} {{ $student->last_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Email</label>
                        <p class="fw-semibold">
                            <a href="mailto:{{ $student->email }}" class="text-decoration-none">{{ $student->email }}</a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Phone</label>
                        <p class="fw-semibold">
                            @if($student->phone)
                                <a href="tel:{{ $student->phone }}">{{ $student->phone }}</a>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Date of Birth</label>
                        <p class="fw-semibold">
                            @if($student->date_of_birth)
                                {{ $student->date_of_birth->format('M d, Y') }}
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Passport Number</label>
                        <p class="fw-semibold">
                            @if($student->passport_number)
                                {{ $student->passport_number }}
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Country</label>
                        <p class="fw-semibold">
                            @if($student->country)
                                <a href="{{ route('countries.show', $student->country->id) }}" class="text-decoration-none">
                                    <i data-feather="globe" class="me-1"></i>{{ $student->country->name }}
                                </a>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">State</label>
                        <p class="fw-semibold">
                            @if($student->state)
                                <a href="{{ route('states.show', $student->state->id) }}" class="text-decoration-none">
                                    <i data-feather="map-pin" class="me-1"></i>{{ $student->state->name }}
                                </a>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Branch</label>
                        <p class="fw-semibold">
                            @if($student->branch)
                                <a href="{{ route('branches.show', $student->branch->id) }}" class="text-decoration-none">
                                    <i data-feather="briefcase" class="me-1"></i>{{ $student->branch->name }}
                                </a>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Agent</label>
                        <p class="fw-semibold">
                            @if($student->agent)
                                <a href="{{ route('agents.show', $student->agent->id) }}" class="text-decoration-none">
                                    <i data-feather="user-check" class="me-1"></i>{{ $student->agent->name }}
                                </a>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Application Status</label>
                        <p class="fw-semibold">
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'in_review' => 'info',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'completed' => 'success',
                                    'secondary' => 'secondary'
                                ];
                                $statusColor = $statusColors[$student->application_status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $statusColor }}">
                                {{ $student->application_status_label }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Created At</label>
                        <p class="fw-semibold">{{ $student->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Applications -->
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i data-feather="book-open" class="me-2"></i>
                    Applications ({{ $student->applications->count() }})
                </h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#applyApplicationModal">
                    <i data-feather="plus" class="me-1"></i> Apply More Application
                </button>
            </div>
            <div class="card-body">
                @if($student->applications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>University</th>
                                    <th>Country</th>
                                    <th>Intake</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->applications as $application)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                                <i data-feather="book" class="text-primary" style="width: 14px; height: 14px;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $application->course->name ?? 'N/A' }}</div>
                                                <small class="text-muted">ID: #APP-{{ str_pad($application->id, 3, '0', STR_PAD_LEFT) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($application->university)
                                            <span class="badge bg-info">{{ $application->university->name }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($application->university && $application->university->country)
                                            <span class="badge bg-secondary">{{ $application->university->country->name }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($application->intake)
                                            <span class="badge bg-success">{{ $application->intake->name }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $statusColors[$application->status ?? 'secondary'] }}">
                                            {{ $application->status_label ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i data-feather="book-open" class="text-muted" style="width: 48px; height: 48px;"></i>
                        <h5 class="mt-3 mb-2">No applications found</h5>
                        <p class="text-muted mb-3">This student hasn't submitted any applications yet.</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#applyApplicationModal">
                            <i data-feather="plus" class="me-2"></i>Apply First Application
                        </button>
                    </div>
                @endif
            </div>
        </x-card>

        <!-- Documents -->
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i data-feather="file-text" class="me-2"></i>
                    Documents ({{ $student->documents->count() }})
                </h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                    <i data-feather="upload-cloud" class="me-1"></i> Upload Document
                </button>
            </div>
            <div class="card-body">
                @if($student->documents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Document Type</th>
                                    <th>File Name</th>
                                    <th>Uploaded</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->documents as $document)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">{{ $document->title }}</span>
                                    </td>
                                    <td>
                                        <div class="small text-truncate" style="max-width: 200px;">
                                            {{ $document->file_name }}
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $document->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                            </a>
                                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                <i data-feather="download" style="width: 14px; height: 14px;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i data-feather="file-text" class="text-muted" style="width: 48px; height: 48px;"></i>
                        <h5 class="mt-3 mb-2">No documents found</h5>
                        <p class="text-muted mb-3">This student hasn't uploaded any documents yet.</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                            <i data-feather="upload-cloud" class="me-2"></i>Upload First Document
                        </button>
                    </div>
                @endif
            </div>
        </x-card>
    </div>

    <div class="col-lg-4">
        <!-- Student Profile Card -->
        <x-card>
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="user" class="me-2"></i>
                    Student Profile
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <img src="{{ $student->image_path ? asset('storage/'.$student->image_path) : 'https://ui-avatars.com/api/?name='.urlencode($student->first_name.' '.$student->last_name).'&background=0d6efd&color=fff' }}" 
                         class="rounded-circle mx-auto d-block" width="80" height="80" alt="{{ $student->first_name }} {{ $student->last_name }}">
                </div>
                <h4 class="mb-1">{{ $student->first_name }} {{ $student->last_name }}</h4>
                <p class="text-muted mb-3">{{ $student->email }}</p>
                
                <div class="mb-3">
                    <span class="badge bg-{{ $statusColors[$student->application_status] ?? 'secondary' }} fs-6">
                        {{ $student->application_status_label }}
                    </span>
                </div>
                
                <div class="row g-2">
                    <div class="col-6">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="book-open" class="text-primary mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">{{ $student->applications->count() }}</h5>
                                <small class="text-muted">Applications</small>
                            </div>
                        </x-card>
                    </div>
                    <div class="col-6">
                        <x-card class="text-center">
                            <div class="py-3">
                                <i data-feather="file-text" class="text-success mb-2" style="width: 24px; height: 24px;"></i>
                                <h5 class="mb-1">{{ $student->documents->count() }}</h5>
                                <small class="text-muted">Documents</small>
                            </div>
                        </x-card>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Quick Actions -->
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="zap" class="me-2"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-outline-primary">
                        <i data-feather="edit-2" class="me-2"></i>Edit Student
                    </a>
                    <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#applyApplicationModal">
                        <i data-feather="plus" class="me-2"></i>Apply Application
                    </button>
                    <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                        <i data-feather="upload-cloud" class="me-2"></i>Upload Document
                    </button>
                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i data-feather="trash-2" class="me-2"></i>Delete Student
                        </button>
                    </form>
                </div>
            </div>
        </x-card>

        <!-- Status Update -->
        <x-card class="mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i data-feather="settings" class="me-2"></i>
                    Update Status
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('students.complete-application', $student) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="status" class="form-label">Application Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending" {{ $student->application_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_review" {{ $student->application_status === 'in_review' ? 'selected' : '' }}>In Review</option>
                            <option value="approved" {{ $student->application_status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $student->application_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="completed" {{ $student->application_status === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Add any notes about this status change...">{{ old('notes', $student->application_notes) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </x-card>
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
                        <td><a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-primary text-decoration-none">Preview</a></td>
                        <td class="text-muted">{{ $doc->file_name }}</td>
                        <td><a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-primary text-decoration-none">Download</a></td>
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

<!-- Apply Application Modal -->
<div class="modal fade" id="applyApplicationModal" tabindex="-1" aria-labelledby="applyApplicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title text-primary fw-bold" id="applyApplicationModalLabel">Apply for Course</h6>
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
                        <button type="submit" class="btn btn-success btn-sm px-4">Save Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<style>
    /* Full width modal styles */
    .modal-document-upload .modal-dialog {
        max-width: 98%;
        margin: 1rem auto;
        height: calc(100% - 2rem);
    }
    .modal-document-upload .modal-content {
        height: 100%;
        display: flex;
        flex-direction: column;
        border: none;
        border-radius: 0.5rem;
    }
    .modal-document-upload .modal-body {
        overflow-y: auto;
        padding: 1.5rem;
        background-color: #f8f9fa;
    }
    .document-upload-item {
        transition: all 0.3s ease;
        border-radius: 8px;
        margin-bottom: 1rem;
        background: white;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
    }
    .document-upload-item:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    .document-upload-item:last-child {
        margin-bottom: 0;
    }
    .form-control, .form-select {
        border-radius: 0.375rem;
    }
    .btn-sm {
        border-radius: 0.25rem;
    }
</style>

<div class="modal fade modal-document-upload" id="uploadDocumentModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen-lg-down modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white sticky-top">
                <h6 class="modal-title fw-bold mb-0">
                    <i data-feather="upload" class="feather-18 me-2"></i>
                    Upload Student Documents
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="documentUploadForm" method="POST" action="{{ route('students.upload-document', $student->id) }}" enctype="multipart/form-data" class="d-flex flex-column h-100">
                @csrf
                <div class="modal-body p-4">
                    <div id="uploadAlert" class="alert d-none mb-4"></div>
                    
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    
                    <div id="document-uploads" class="mb-4">
                        <div class="document-upload-item p-4 mb-4">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Document Title <span class="text-danger">*</span></label>
                                    <input type="text" name="documents[0][title]" class="form-control form-control-sm" placeholder="e.g. Passport Copy" required>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Document Type <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" name="documents[0][document_type_id]" required>
                                        <option value="" selected disabled>Select Type</option>
                                        @foreach($documentTypes as $documentType)
                                            <option value="{{ $documentType->id }}">{{ $documentType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-danger remove-document mt-4 w-100" style="display: none;">
                                    <i data-feather="trash-2" class="feather-14"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Document File <span class="text-danger">*</span></label>
                                    <input type="file" name="documents[0][file]" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Expiry Date</label>
                                    <input type="date" name="documents[0][expiry_date]" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Notes (Optional)</label>
                            <textarea name="documents[0][notes]" class="form-control form-control-sm" rows="2" placeholder="Any details about this file..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-another-doc">
                        <i data-feather="plus" class="feather-14 me-1"></i> Add Another Document
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="bulk-upload-toggle">
                        <i data-feather="upload" class="feather-14 me-1"></i> Bulk Upload
                    </button>
                </div>
                <div id="bulk-upload-section" class="mb-3" style="display: none;">
                    <label class="form-label small fw-bold">Bulk Upload Documents</label>
                    <input type="file" name="bulk_documents[]" class="form-control form-control-sm" multiple>
                    <small class="text-muted">You can select multiple files. Files will be uploaded with default settings.</small>
                </div>
                        </div>
                    </div>
                    
                    <div class="px-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-another-doc">
                                <i data-feather="plus" class="feather-14 me-1"></i> Add Another Document
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="bulk-upload-toggle">
                                <i data-feather="upload" class="feather-14 me-1"></i> Bulk Upload
                            </button>
                        </div>
                        
                        <div id="bulk-upload-section" class="mb-3" style="display: none;">
                            <div class="card border">
                                <div class="card-body p-3">
                                    <label class="form-label small fw-bold">Bulk Upload Documents</label>
                                    <input type="file" name="bulk_documents[]" class="form-control form-control-sm" multiple>
                                    <small class="text-muted">You can select multiple files. Files will be uploaded with default settings.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-light border-top py-3">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                    <i data-feather="x" class="feather-16 me-1"></i> Cancel
                                </button>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary px-4" id="uploadBtn">
                                    <span class="spinner-border spinner-border-sm d-none me-1" role="status" aria-hidden="true"></span>
                                    <i data-feather="upload" class="feather-16 me-1"></i>
                                    <span class="btn-text">Upload Documents</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather Icons
    feather.replace();

    // Document upload counter
    let docCounter = 1;

    // Add another document field
    document.getElementById('add-another-doc').addEventListener('click', function() {
        const newDocItem = document.querySelector('.document-upload-item').cloneNode(true);
        const newIndex = docCounter++;
        
        // Update all names and IDs
        newDocItem.querySelectorAll('[name^="documents[0]"]').forEach(el => {
            const newName = el.name.replace('documents[0]', `documents[${newIndex}]`);
            el.name = newName;
            el.value = '';
            el.required = true;
            
            // Reset file input
            if (el.type === 'file') {
                el.value = '';
            }
            
            // Reset select to first option
            if (el.tagName === 'SELECT') {
                el.selectedIndex = 0;
            }
        });
        
        // Show remove button for all but the first item
        newDocItem.querySelector('.remove-document').style.display = 'block';
        
        // Add remove functionality
        newDocItem.querySelector('.remove-document').addEventListener('click', function() {
            this.closest('.document-upload-item').remove();
        });
        
        document.getElementById('document-uploads').appendChild(newDocItem);
        feather.replace();
    });
    
    // Toggle bulk upload section
    document.getElementById('bulk-upload-toggle').addEventListener('click', function() {
        const bulkSection = document.getElementById('bulk-upload-section');
        const isBulk = bulkSection.style.display === 'block';
        bulkSection.style.display = isBulk ? 'none' : 'block';
        this.innerHTML = `<i data-feather="${isBulk ? 'upload' : 'x'}" class="feather-14 me-1"></i> ${isBulk ? 'Bulk Upload' : 'Cancel Bulk Upload'}`;
        feather.replace();
    });
    
    // Form submission handling
    document.getElementById('documentUploadForm').addEventListener('submit', function(e) {
        const form = this;
        const uploadBtn = form.querySelector('#uploadBtn');
        const btnText = uploadBtn.querySelector('.btn-text');
        const spinner = uploadBtn.querySelector('.spinner-border');
        
        // Show loading state
        uploadBtn.disabled = true;
        btnText.textContent = 'Uploading...';
        spinner.classList.remove('d-none');
        
        // Handle form submission via AJAX
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Show success message
            const alert = document.getElementById('uploadAlert');
            alert.className = 'alert alert-success';
            alert.textContent = data.message || 'Documents uploaded successfully!';
            alert.classList.remove('d-none');
            
            // Reset form if successful
            if (data.success) {
                form.reset();
                // Reload the page to show new documents
                setTimeout(() => window.location.reload(), 1500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const alert = document.getElementById('uploadAlert');
            alert.className = 'alert alert-danger';
            alert.textContent = 'An error occurred while uploading documents. Please try again.';
            alert.classList.remove('d-none');
        })
        .finally(() => {
            // Reset button state
            uploadBtn.disabled = false;
            btnText.textContent = 'Upload Document';
            spinner.classList.add('d-none');
        });
    });
    
    // When University is selected in the modal
    $('#modal_university').on('change', function() {
        var uniId = $(this).val();
        var courseSelect = $('#modal_course');
        
        courseSelect.prop('disabled', true).html('<option>Loading...</option>');

        if(uniId) {
            $.ajax({
                url: '/get-courses-by-university/' + uniId,
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