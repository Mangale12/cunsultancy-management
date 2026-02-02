@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<x-page-header 
    title="Dashboard Overview"
    subtitle="Welcome back! Here's what's happening with your consultancy today."
    :actions="
        '<button type=\"button\" class=\"btn btn-primary\" id=\"addBtn\">
            <i data-feather=\"plus\" class=\"me-2\"></i> Add New Record
        </button>'
    "
/>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6">
        <x-stat-card 
            title="Total Students"
            value="1,245"
            icon="users"
            color="primary"
            trend="up"
            trendValue="12% from last month"
        />
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <x-stat-card 
            title="Active Applications"
            value="84"
            icon="file-text"
            color="success"
            trend="up"
            trendValue="8% from last week"
        />
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <x-stat-card 
            title="Pending Payments"
            value="$4,200"
            icon="dollar-sign"
            color="warning"
            trend="down"
            trendValue="3% from last month"
        />
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6">
        <x-stat-card 
            title="Success Rate"
            value="94%"
            icon="trending-up"
            color="info"
            trend="up"
            trendValue="2% improvement"
        />
    </div>
</div>

<!-- Recent Activity and Quick Actions -->
<div class="row g-4">
    <div class="col-lg-8">
        <x-card title="Recent Applications">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name=John+Doe&background=0d6efd&color=fff" 
                                         class="rounded-circle me-3" width="32" height="32">
                                    <div>
                                        <div class="fw-semibold">John Doe</div>
                                        <small class="text-muted">ID: #STU-001</small>
                                    </div>
                                </div>
                            </td>
                            <td>Business Management</td>
                            <td><span class="badge bg-success">Approved</span></td>
                            <td>2 hours ago</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary view-btn">
                                    <i data-feather="eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=198754&color=fff" 
                                         class="rounded-circle me-3" width="32" height="32">
                                    <div>
                                        <div class="fw-semibold">Jane Smith</div>
                                        <small class="text-muted">ID: #STU-002</small>
                                    </div>
                                </div>
                            </td>
                            <td>Computer Science</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td>5 hours ago</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary view-btn">
                                    <i data-feather="eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name=Mike+Johnson&background=dc3545&color=fff" 
                                         class="rounded-circle me-3" width="32" height="32">
                                    <div>
                                        <div class="fw-semibold">Mike Johnson</div>
                                        <small class="text-muted">ID: #STU-003</small>
                                    </div>
                                </div>
                            </td>
                            <td>Engineering</td>
                            <td><span class="badge bg-info">In Review</span></td>
                            <td>1 day ago</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary view-btn">
                                    <i data-feather="eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-top">
                <a href="{{ route('student-applications.index') }}" class="btn btn-outline-primary btn-sm">
                    View All Applications <i data-feather="arrow-right" class="ms-1"></i>
                </a>
            </div>
        </x-card>
    </div>
    
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <x-card title="Quick Actions">
            <div class="d-grid gap-2">
                <a href="{{ route('students.create') }}" class="btn btn-outline-primary text-start">
                    <i data-feather="user-plus" class="me-2"></i>
                    Add New Student
                </a>
                <a href="{{ route('student-applications.create') }}" class="btn btn-outline-success text-start">
                    <i data-feather="file-plus" class="me-2"></i>
                    New Application
                </a>
                <a href="{{ route('courses.create') }}" class="btn btn-outline-info text-start">
                    <i data-feather="book" class="me-2"></i>
                    Add Course
                </a>
                <a href="#" class="btn btn-outline-warning text-start">
                    <i data-feather="download" class="me-2"></i>
                    Generate Report
                </a>
            </div>
        </x-card>
        
        <!-- System Status -->
        <x-card title="System Status" class="mt-4">
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">Database</span>
                    <span class="badge bg-success">Healthy</span>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">Storage</span>
                    <span class="badge bg-warning">78%</span>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-warning" style="width: 78%"></div>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">API Response</span>
                    <span class="badge bg-success">120ms</span>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: 95%"></div>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Feather Icons
        feather.replace();
        
        // Add button interaction
        const addBtn = document.getElementById('addBtn');
        if (addBtn) {
            addBtn.addEventListener('click', function() {
                // You can replace this with a modal or redirect
                alert('Opening "Add New Record" form...');
            });
        }
        
        // View button interaction
        document.querySelectorAll('.view-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const studentName = row.querySelector('.fw-semibold').textContent;
                alert('Viewing details for: ' + studentName);
            });
        });
        
        // Animate stats on load
        const statValues = document.querySelectorAll('.stat-card h2');
        statValues.forEach(function(stat, index) {
            setTimeout(function() {
                stat.style.opacity = '0';
                stat.style.transform = 'translateY(10px)';
                stat.style.transition = 'all 0.5s ease';
                
                setTimeout(function() {
                    stat.style.opacity = '1';
                    stat.style.transform = 'translateY(0)';
                }, 100);
            }, index * 100);
        });
    });
</script>
@endpush
