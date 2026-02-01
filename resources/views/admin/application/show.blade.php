@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 bg-light min-vh-100">
    <div class="d-flex justify-content-between align-items-center py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Applications</a></li>
                <li class="breadcrumb-item active">ID: {{ $application->id }}</li>
            </ol>
        </nav>
        <a href="{{ url()->previous() }}" class="btn btn-dark btn-sm rounded-pill px-3">
            <i data-feather="arrow-left" class="me-1"></i> Go Back
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <h5 class="fw-bold mb-0">{{ $application->student->name }}</h5>
                <span class="badge bg-{{ $application->status == 'Approved' ? 'success' : 'primary' }} px-3 py-2">
                    {{ $application->status ?? 'Pending' }}
                </span>
            </div>
            
            <div class="row g-3 text-uppercase small">
                <div class="col-md-2">
                    <label class="text-muted d-block">Student Name</label>
                    <span class="fw-bold">{{ $application->student->name }}</span>
                </div>
                <div class="col-md-2">
                    <label class="text-muted d-block">Email</label>
                    <span class="fw-bold text-lowercase text-primary">{{ $application->student->email }}</span>
                </div>
                <div class="col-md-2">
                    <label class="text-muted d-block">University</label>
                    <span class="fw-bold">{{ $application->university->name }}</span>
                </div>
                <div class="col-md-2">
                    <label class="text-muted d-block">Intake</label>
                    <span class="fw-bold">{{ $application->intake->name ?? 'N/A' }}</span>
                </div>
                <div class="col-md-2">
                    <label class="text-muted d-block">Applied On</label>
                    <span class="fw-bold">{{ $application->created_at->format('d M Y') }}</span>
                </div>
                <div class="col-md-2">
                    <label class="text-muted d-block">Nationality</label>
                    <span class="fw-bold">{{ $application->student->country->name ?? 'N/A' }}</span>
                </div>
            </div>
            
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                    <i data-feather="refresh-cw" class="me-1"></i> Change Status
                </button>
                <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                    <i data-feather="upload" class="me-1"></i> Upload Document
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0 small fw-bold">Course Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1 small text-muted">Selected Course</p>
                            <h6 class="text-primary fw-bold">{{ $application->course->name }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 small text-muted">Campus / Location</p>
                            <h6 class="fw-bold">{{ $application->university->city ?? 'Main Campus' }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-primary">Uploaded Documents</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">S.N.</th>
                                <th>Document Name</th>
                                <th>File Name</th>
                                <th>Action</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($application->student->documents as $key => $doc)
                            <tr>
                                <td class="ps-3">{{ $key + 1 }}</td>
                                <td class="fw-bold">{{ $doc->title }}</td>
                                <td class="text-muted">{{ $doc->file_name }}</td>
                                <td>
                                    <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="btn btn-link btn-sm p-0 me-2">Preview</a>
                                    <a href="{{ asset('storage/'.$doc->file_path) }}" download class="btn btn-link btn-sm p-0 text-success">Download</a>
                                </td>
                                <td>{{ $doc->created_at->format('d M, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-primary">Application History Logs</h6>
                </div>
                <div class="card-body">
                    <div class="timeline-container">
                        @foreach($application->logs as $log)
                        <div class="timeline-item position-relative ps-4 pb-4">
                            <div class="timeline-dot bg-success"></div>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold small">{{ $log->user->name }}</span>
                                <small class="text-muted" style="font-size: 10px;">{{ $log->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="text-primary fw-bold small mt-1">{{ $log->status }}</div>
                            @if($log->comment)
                                <div class="bg-light p-2 rounded mt-1 small text-muted italic">"{{ $log->comment }}"</div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-bold text-primary">Update Application Status</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('applications.update-status', $application->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="small fw-bold">New Status</label>
                        <select name="status" class="form-select form-select-sm" required>
                            <option value="Prescreening Approved">Prescreening Approved</option>
                            <option value="Conditional Offer">Conditional Offer</option>
                            <option value="Unconditional Offer">Unconditional Offer</option>
                            <option value="Visa Lodged">Visa Lodged</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="small fw-bold">Remarks</label>
                        <textarea name="comment" class="form-control form-control-sm" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Save and Log</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-primary { background-color: #0025cc !important; }
    .text-primary { color: #0025cc !important; }
    .timeline-container { border-left: 2px solid #f1f1f1; margin-left: 10px; }
    .timeline-item::before {
        content: "";
        position: absolute;
        left: -7px;
        top: 5px;
        width: 12px;
        height: 12px;
        background-color: #198754;
        border-radius: 50%;
        border: 2px solid white;
    }
</style>
@endsection