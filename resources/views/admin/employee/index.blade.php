@extends('layouts.app')

@section('title', 'Employees')

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
    title="Employee Management"
    subtitle="Manage staff members and their assigned roles within the consultancy."
    :actions="
        '<a href=\"' . route('employees.create') . '\" class=\"btn btn-primary\">
            <i data-feather=\"user-plus\" class=\"me-2\"></i> Add New Employee
        </a>'
    "
/>

<!-- Search and Filter Section -->
<x-card class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-8">
            <label class="form-label">Search Employees</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i data-feather="search"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       placeholder="Search by name, email, or ID..." 
                       id="employeeSearch">
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-grid gap-2 d-md-flex">
                <button type="button" class="btn btn-outline-secondary" id="clearEmployeeSearch">
                    <i data-feather="x" class="me-1"></i> Clear
                </button>
                <button type="button" class="btn btn-outline-primary" id="exportEmployees">
                    <i data-feather="download" class="me-1"></i> Export
                </button>
            </div>
        </div>
    </div>
</x-card>

<!-- Employees Table -->
<x-card>
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="employeeTable">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Employee Name</th>
                    <th class="d-none d-md-table-cell">Branch</th>
                    <th class="d-none d-lg-table-cell">Contact</th>
                    <th class="d-none d-md-table-cell">Status</th>
                    <th class="text-center" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                <tr>
                    <td>
                        <span class="badge bg-light text-dark fw-normal">{{ $loop->iteration }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                <i data-feather="users" class="text-primary" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $employee->name }}</div>
                                <small class="text-muted">ID: #EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge bg-info text-dark">{{ $employee->branch->name ?? 'Not Assigned' }}</span>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        @if($employee->email)
                            <div class="small">
                                <div class="text-truncate" style="max-width: 150px;">
                                    <i data-feather="mail" style="width: 12px; height: 12px;"></i> 
                                    {{ $employee->email }}
                                </div>
                                @if($employee->phone)
                                    <div class="text-muted">
                                        <i data-feather="phone" style="width: 12px; height: 12px;"></i> 
                                        {{ $employee->phone }}
                                    </div>
                                @endif
                            </div>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge bg-success">Active</span>
                    </td>
                    <td class="text-center">
                        <x-action-buttons 
                            :edit-route="'employees.edit'"
                            :show-route="'employees.show'"
                            :delete-route="'employees.destroy'"
                            :edit-id="$employee->id"
                            :show-id="$employee->id"
                            :delete-id="$employee->id"
                            delete-confirm="Are you sure you want to delete this employee? This action cannot be undone."
                        />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">
                            <i data-feather="users" style="width: 48px; height: 48px;"></i>
                            <h5 class="mt-3 mb-2">No employees found</h5>
                            <p class="mb-3">Start by adding your first employee to manage your consultancy operations.</p>
                            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                                <i data-feather="user-plus" class="me-2"></i>Add First Employee
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if(method_exists($employees, 'hasPages') && $employees->hasPages())
    <div class="card-footer bg-white border-top">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <small class="text-muted mb-2 mb-md-0">
                Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }} employees
            </small>
            <div class="d-flex justify-content-center justify-content-md-end">
                {{ $employees->links() }}
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
                <i data-feather="users" class="text-primary mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $employees->count() }}</h4>
                <small class="text-muted">Total Employees</small>
            </div>
        </x-card>
    </div>
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="briefcase" class="text-success mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $employees->whereNotNull('branch_id')->count() }}</h4>
                <small class="text-muted">Assigned to Branches</small>
            </div>
        </x-card>
    </div>
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="graduation-cap" class="text-info mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $employees->sum(function($e) { return $e->students_count ?? 0; }) }}</h4>
                <small class="text-muted">Students Managed</small>
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
        
        // Employee search functionality
        const employeeSearch = document.getElementById('employeeSearch');
        const clearEmployeeSearch = document.getElementById('clearEmployeeSearch');
        const employeeTable = document.getElementById('employeeTable');
        const rows = employeeTable.querySelectorAll('tbody tr');
        
        if (employeeSearch) {
            employeeSearch.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const isVisible = text.includes(searchText);
                    row.style.display = isVisible ? '' : 'none';
                });
                
                // Update empty state visibility
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                const emptyRow = employeeTable.querySelector('tr:has(.text-muted)');
                if (emptyRow) {
                    emptyRow.style.display = visibleRows.length === 0 && rows.length > 1 ? '' : 'none';
                }
            });
        }
        
        // Clear search
        if (clearEmployeeSearch) {
            clearEmployeeSearch.addEventListener('click', function() {
                if (employeeSearch) employeeSearch.value = '';
                rows.forEach(row => {
                    row.style.display = '';
                });
            });
        }
        
        // Export functionality
        const exportEmployees = document.getElementById('exportEmployees');
        if (exportEmployees) {
            exportEmployees.addEventListener('click', function() {
                // Simple CSV export
                let csv = 'Employee Name,Branch,Email,Phone,Status\n';
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const cells = row.querySelectorAll('td');
                        const name = cells[1]?.textContent.trim() || '';
                        csv += `"${name}","","","","","Active"\n`;
                    }
                });
                
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'employees_export.csv';
                a.click();
                window.URL.revokeObjectURL(url);
                
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                alert.style.zIndex = '9999';
                alert.innerHTML = `
                    <i data-feather="check-circle" class="me-2"></i>
                    Employees exported successfully!
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
                if (employeeSearch) employeeSearch.focus();
            }
            
            if (e.key === 'Escape' && employeeSearch && employeeSearch.value) {
                employeeSearch.value = '';
                rows.forEach(row => {
                    row.style.display = '';
                });
            }
        });
    });
</script>
@endpush
