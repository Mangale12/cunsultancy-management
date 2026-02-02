@extends('layouts.app')

@section('title', 'Students')

@section('content')
<!-- Success Message -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i data-feather="check-circle" class="me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<x-page-header 
    title="Student Enrollment"
    subtitle="Manage student registrations and track application progress."
    :actions="
        '<a href=\"' . route('students.create') . '\" class=\"btn btn-primary\">
            <i data-feather=\"user-plus\" class=\"me-2\"></i> New Registration
        </a>'
    "
/>

<!-- Filters Section -->
<x-card class="mb-4">
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Search Students</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i data-feather="search"></i>
                </span>
                <input type="text" class="form-control" placeholder="Search by name, email, or ID..." id="searchInput">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select class="form-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Branch</label>
            <select class="form-select" id="branchFilter">
                <option value="">All Branches</option>
                <option value="1">Main Branch</option>
                <option value="2">Downtown Branch</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">&nbsp;</label>
            <div class="d-grid">
                <button type="button" class="btn btn-outline-secondary" id="clearFilters">
                    <i data-feather="x" class="me-1"></i> Clear
                </button>
            </div>
        </div>
    </div>
</x-card>

<!-- Students Table -->
<x-card>
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="studentsTable">
            <thead>
                <tr>
                    <th>Student</th>
                    <th class="d-none d-md-table-cell">Contact</th>
                    <th class="d-none d-lg-table-cell">Branch/Agent</th>
                    <th class="d-none d-md-table-cell">Applied Course</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $student->image_path ? asset('storage/'.$student->image_path) : 'https://ui-avatars.com/api/?name='.urlencode($student->name).'&background=0d6efd&color=fff' }}" 
                                 class="rounded-circle me-3" width="40" height="40" alt="{{ $student->name }}">
                            <div>
                                <div class="fw-semibold text-truncate" style="max-width: 150px;">
                                    {{ $student->name }}
                                </div>
                                <small class="text-muted">ID: #STU-{{ str_pad($student->id, 3, '0', STR_PAD_LEFT) }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <div class="small">
                            <div class="text-truncate" style="max-width: 150px;">
                                <i data-feather="mail" style="width: 12px; height: 12px;"></i> 
                                {{ $student->email }}
                            </div>
                            <div class="text-muted">
                                <i data-feather="phone" style="width: 12px; height: 12px;"></i> 
                                {{ $student->phone }}
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <span class="badge bg-info text-dark">{{ $student->branch->name ?? 'N/A' }}</span>
                        <br>
                        <small class="text-muted">Agent: {{ $student->agent->name ?? 'Direct' }}</small>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <div class="text-truncate" style="max-width: 120px;">
                            {{ $student->course->name ?? 'Not Assigned' }}
                        </div>
                    </td>
                    <td>
                        <span class="badge rounded-pill {{ $student->status == 'active' ? 'bg-success' : ($student->status == 'pending' ? 'bg-warning' : 'bg-secondary') }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('students.show', $student->id) }}" 
                               class="btn btn-sm btn-outline-primary" 
                               title="View Details">
                                <i data-feather="eye"></i>
                            </a>
                            <a href="{{ route('students.edit', $student->id) }}" 
                               class="btn btn-sm btn-outline-secondary" 
                               title="Edit Student">
                                <i data-feather="edit-2"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger" 
                                    title="Delete Student"
                                    onclick="confirmDelete({{ $student->id }}, '{{ $student->name }}')">
                                <i data-feather="trash-2"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">
                            <i data-feather="users" style="width: 48px; height: 48px;"></i>
                            <h5 class="mt-3 mb-2">No students found</h5>
                            <p class="mb-3">Get started by adding your first student registration.</p>
                            <a href="{{ route('students.create') }}" class="btn btn-primary">
                                <i data-feather="user-plus" class="me-2"></i>Add First Student
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if(method_exists($students, 'hasPages') && $students->hasPages())
    <div class="card-footer bg-white border-top">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <small class="text-muted mb-2 mb-md-0">
                Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} students
            </small>
            <div class="d-flex justify-content-center justify-content-md-end">
                {{ $students->links() }}
            </div>
        </div>
    </div>
    @endif
</x-card>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteStudentName"></strong>?</p>
                <p class="text-muted small">This action cannot be undone and will remove all associated data.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Student</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Feather Icons
        feather.replace();
        
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const studentsTable = document.getElementById('studentsTable');
        const rows = studentsTable.querySelectorAll('tbody tr');
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchText) ? '' : 'none';
                });
            });
        }
        
        // Filter functionality
        const statusFilter = document.getElementById('statusFilter');
        const branchFilter = document.getElementById('branchFilter');
        
        function applyFilters() {
            const statusValue = statusFilter ? statusFilter.value : '';
            const branchValue = branchFilter ? branchFilter.value : '';
            
            rows.forEach(row => {
                let showRow = true;
                
                if (statusValue) {
                    const statusBadge = row.querySelector('.badge');
                    if (statusBadge && !statusBadge.textContent.toLowerCase().includes(statusValue)) {
                        showRow = false;
                    }
                }
                
                if (branchValue && showRow) {
                    const branchBadge = row.querySelector('.badge.bg-info');
                    if (branchBadge && !branchBadge.textContent.toLowerCase().includes(branchValue.toLowerCase())) {
                        showRow = false;
                    }
                }
                
                row.style.display = showRow ? '' : 'none';
            });
        }
        
        if (statusFilter) statusFilter.addEventListener('change', applyFilters);
        if (branchFilter) branchFilter.addEventListener('change', applyFilters);
        
        // Clear filters
        const clearFilters = document.getElementById('clearFilters');
        if (clearFilters) {
            clearFilters.addEventListener('click', function() {
                if (searchInput) searchInput.value = '';
                if (statusFilter) statusFilter.value = '';
                if (branchFilter) branchFilter.value = '';
                
                rows.forEach(row => {
                    row.style.display = '';
                });
            });
        }
        
        // Delete functionality
        window.confirmDelete = function(studentId, studentName) {
            document.getElementById('deleteStudentName').textContent = studentName;
            document.getElementById('deleteForm').action = '/students/' + studentId;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        };
    });
</script>
@endpush