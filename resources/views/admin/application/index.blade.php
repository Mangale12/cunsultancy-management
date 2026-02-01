@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 h3 fw-bold">Application Reports</h1>
    
    <div class="row mt-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4 shadow-sm border-0">
                <div class="card-body">
                    <small>Total Applications</small>
                    <h2 class="fw-bold">{{ $stats['total_applications'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4 shadow-sm border-0">
                <div class="card-body">
                    <small>Expected Tuition</small>
                    <h2 class="fw-bold">${{ number_format($stats['total_tuition'], 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4 shadow-sm border-0">
                <div class="card-body">
                    <small>Total Scholarships</small>
                    <h2 class="fw-bold">${{ number_format($stats['total_scholarship'], 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-dark text-white mb-4 shadow-sm border-0">
                <div class="card-body">
                    <small>Visa Success</small>
                    <h2 class="fw-bold">{{ $stats['visa_approved'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="small fw-bold">University</label>
                    <select name="university_id" class="form-select form-select-sm">
                        <option value="">All Universities</option>
                        @foreach($universities as $uni)
                            <option value="{{ $uni->id }}" {{ request('university_id') == $uni->id ? 'selected' : '' }}>{{ $uni->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold">Start Date</label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold">End Date</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-4">Filter</button>
                    <a href="{{ route('student-applications.index') }}" class="btn btn-light btn-sm px-4 border">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="ps-3">Date</th>
                            <th>Student</th>
                            <th>University & Course</th>
                            <th>Tuition</th>
                            <th>Scholarship</th>
                            <th>App Status</th>
                            <th>Visa Status</th>
                            <th class="text-end pe-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                        <tr>
                            <td class="ps-3 text-muted">{{ $app->application_date ? $app->application_date->format('d M Y') : 'N/A' }}</td>
                            <td>
                                <div class="fw-bold">{{ $app->student->name }}</div>
                                <div class="text-muted" style="font-size: 10px;">ID: #{{ $app->student->id }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-primary">{{ $app->university->name }}</div>
                                <div class="text-muted">{{ $app->course->name }}</div>
                            </td>
                            <td>{{ number_format($app->tuition_fee, 2) }}</td>
                            <td class="text-success">{{ number_format($app->scholarship_amount, 2) }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $app->application_status }}</span></td>
                            <td><span class="badge bg-{{ $app->visa_status == 'Approved' ? 'success' : 'warning' }}">{{ $app->visa_status }}</span></td>
                            <td class="text-end pe-3">
                                <a href="{{ route('student-applications.show', $app->id) }}" class="btn btn-sm btn-link">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary { background-color: #0025cc !important; }
    .text-primary { color: #0025cc !important; }
</style>
@endsection