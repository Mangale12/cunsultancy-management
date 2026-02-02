@extends('layouts.app')

@section('title', 'Courses')

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
    title="Course Management"
    subtitle="Manage academic courses and programs offered to students."
    :actions="
        '<a href=\"' . route('courses.create') . '\" class=\"btn btn-primary\">
            <i data-feather=\"book\" class=\"me-2\"></i> Add New Course
        </a>'
    "
/>

<!-- Search and Filter Section -->
<x-card class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-8">
            <label class="form-label">Search Courses</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i data-feather="search"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       placeholder="Search by course name or description..." 
                       id="courseSearch">
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-grid gap-2 d-md-flex">
                <button type="button" class="btn btn-outline-secondary" id="clearCourseSearch">
                    <i data-feather="x" class="me-1"></i> Clear
                </button>
                <button type="button" class="btn btn-outline-primary" id="exportCourses">
                    <i data-feather="download" class="me-1"></i> Export
                </button>
            </div>
        </div>
    </div>
</x-card>

<!-- Courses Table -->
<x-card>
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="courseTable">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Course Name</th>
                    <th class="d-none d-md-table-cell">Duration</th>
                    <th class="d-none d-lg-table-cell">Level</th>
                    <th class="d-none d-md-table-cell">Status</th>
                    <th class="text-center" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($courses as $course)
                <tr>
                    <td>
                        <span class="badge bg-light text-dark fw-normal">{{ $loop->iteration }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                <i data-feather="book" class="text-primary" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $course->name }}</div>
                                @if($course->code)
                                    <small class="text-muted">Code: {{ $course->code }}</small>
                                @endif
                                @if($course->description)
                                    <div class="text-truncate" style="max-width: 200px;">
                                        <small class="text-muted">{{ $course->description }}</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        @if($course->duration)
                            <span class="badge bg-info">{{ $course->duration }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="d-none d-lg-table-cell">
                        @if($course->level)
                            <span class="badge bg-secondary">{{ $course->level }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge bg-success">Active</span>
                    </td>
                    <td class="text-center">
                        <x-action-buttons 
                            :edit-route="'courses.edit'"
                            :show-route="'courses.show'"
                            :delete-route="'courses.destroy'"
                            :edit-id="$course->id"
                            :show-id="$course->id"
                            :delete-id="$course->id"
                            delete-confirm="Are you sure you want to delete this course? This will affect all enrolled students."
                        />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">
                            <i data-feather="book" style="width: 48px; height: 48px;"></i>
                            <h5 class="mt-3 mb-2">No courses found</h5>
                            <p class="mb-3">Start by adding your first course to offer to students.</p>
                            <a href="{{ route('courses.create') }}" class="btn btn-primary">
                                <i data-feather="book" class="me-2"></i>Add First Course
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if(method_exists($courses, 'hasPages') && $courses->hasPages())
    <div class="card-footer bg-white border-top">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <small class="text-muted mb-2 mb-md-0">
                Showing {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of {{ $courses->total() }} courses
            </small>
            <div class="d-flex justify-content-center justify-content-md-end">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
    @endif
</x-card>

<!-- Statistics Cards -->
<div class="row g-4 mt-2">
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="book" class="text-primary mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $courses->count() }}</h4>
                <small class="text-muted">Total Courses</small>
            </div>
        </x-card>
    </div>
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="users" class="text-success mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $courses->sum(function($c) { return $c->students_count ?? 0; }) }}</h4>
                <small class="text-muted">Total Students</small>
            </div>
        </x-card>
    </div>
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="graduation-cap" class="text-info mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $courses->sum(function($c) { return $c->applications_count ?? 0; }) }}</h4>
                <small class="text-muted">Total Applications</small>
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
        
        // Course search functionality
        const courseSearch = document.getElementById('courseSearch');
        const clearCourseSearch = document.getElementById('clearCourseSearch');
        const courseTable = document.getElementById('courseTable');
        const rows = courseTable.querySelectorAll('tbody tr');
        
        if (courseSearch) {
            courseSearch.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const isVisible = text.includes(searchText);
                    row.style.display = isVisible ? '' : 'none';
                });
                
                // Update empty state visibility
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                const emptyRow = courseTable.querySelector('tr:has(.text-muted)');
                if (emptyRow) {
                    emptyRow.style.display = visibleRows.length === 0 && rows.length > 1 ? '' : 'none';
                }
            });
        }
        
        // Clear search
        if (clearCourseSearch) {
            clearCourseSearch.addEventListener('click', function() {
                if (courseSearch) courseSearch.value = '';
                rows.forEach(row => {
                    row.style.display = '';
                });
            });
        }
        
        // Export functionality
        const exportCourses = document.getElementById('exportCourses');
        if (exportCourses) {
            exportCourses.addEventListener('click', function() {
                // Simple CSV export
                let csv = 'Course Name,Code,Duration,Level,Status\n';
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const cells = row.querySelectorAll('td');
                        const name = cells[1]?.textContent.trim() || '';
                        csv += `"${name}","","","","Active"\n`;
                    }
                });
                
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'courses_export.csv';
                a.click();
                window.URL.revokeObjectURL(url);
                
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                alert.style.zIndex = '9999';
                alert.innerHTML = `
                    <i data-feather="check-circle" class="me-2"></i>
                    Courses exported successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alert);
                feather.replace();
                
                setTimeout(() => {
                    alert.remove();
                }, 3000);
            });
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                if (courseSearch) courseSearch.focus();
            }
            
            if (e.key === 'Escape' && courseSearch && courseSearch.value) {
                courseSearch.value = '';
                rows.forEach(row => {
                    row.style.display = '';
                });
            }
        });
    });
</script>
@endpush
