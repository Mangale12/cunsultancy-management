@extends('layouts.app')

@section('title', 'Branches')

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
    title="Branch Management"
    subtitle="Manage consultancy branches and their operational locations."
    :actions="
        '<a href=\"' . route('branches.create') . '\" class=\"btn btn-primary\">
            <i data-feather=\"briefcase\" class=\"me-2\"></i> Add New Branch
        </a>'
    "
/>

<!-- Search and Filter Section -->
<x-card class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-8">
            <label class="form-label">Search Branches</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i data-feather="search"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       placeholder="Search by branch name or location..." 
                       id="branchSearch">
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-grid gap-2 d-md-flex">
                <button type="button" class="btn btn-outline-secondary" id="clearBranchSearch">
                    <i data-feather="x" class="me-1"></i> Clear
                </button>
                <button type="button" class="btn btn-outline-primary" id="exportBranches">
                    <i data-feather="download" class="me-1"></i> Export
                </button>
            </div>
        </div>
    </div>
</x-card>

<!-- Branches Table -->
<x-card>
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="branchTable">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Branch Name</th>
                    <th class="d-none d-md-table-cell">Location</th>
                    <th class="d-none d-lg-table-cell">Contact</th>
                    <th class="d-none d-md-table-cell">Status</th>
                    <th class="text-center" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($branches as $branch)
                <tr>
                    <td>
                        <span class="badge bg-light text-dark fw-normal">{{ $loop->iteration }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                <i data-feather="briefcase" class="text-primary" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $branch->name }}</div>
                                @if($branch->code)
                                    <small class="text-muted">Code: {{ $branch->code }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        @if($branch->address)
                            <div class="small">
                                <div class="text-truncate" style="max-width: 150px;">
                                    <i data-feather="map-pin" style="width: 12px; height: 12px;"></i> 
                                    {{ $branch->address }}
                                </div>
                                @if($branch->city)
                                    <div class="text-muted">{{ $branch->city }}</div>
                                @endif
                            </div>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="d-none d-lg-table-cell">
                        @if($branch->phone || $branch->email)
                            <div class="small">
                                @if($branch->phone)
                                    <div class="text-truncate" style="max-width: 120px;">
                                        <i data-feather="phone" style="width: 12px; height: 12px;"></i> 
                                        {{ $branch->phone }}
                                    </div>
                                @endif
                                @if($branch->email)
                                    <div class="text-truncate" style="max-width: 120px;">
                                        <i data-feather="mail" style="width: 12px; height: 12px;"></i> 
                                        {{ $branch->email }}
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
                            :edit-route="'branches.edit'"
                            :show-route="'branches.show'"
                            :delete-route="'branches.destroy'"
                            :edit-id="$branch->id"
                            :show-id="$branch->id"
                            :delete-id="$branch->id"
                            delete-confirm="Are you sure you want to delete this branch? This will affect all assigned agents and students."
                        />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">
                            <i data-feather="briefcase" style="width: 48px; height: 48px;"></i>
                            <h5 class="mt-3 mb-2">No branches found</h5>
                            <p class="mb-3">Start by adding your first branch to organize your consultancy operations.</p>
                            <a href="{{ route('branches.create') }}" class="btn btn-primary">
                                <i data-feather="briefcase" class="me-2"></i>Add First Branch
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if(method_exists($branches, 'hasPages') && $branches->hasPages())
    <div class="card-footer bg-white border-top">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <small class="text-muted mb-2 mb-md-0">
                Showing {{ $branches->firstItem() }} to {{ $branches->lastItem() }} of {{ $branches->total() }} branches
            </small>
            <div class="d-flex justify-content-center justify-content-md-end">
                {{ $branches->links() }}
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
                <i data-feather="briefcase" class="text-primary mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $branches->count() }}</h4>
                <small class="text-muted">Total Branches</small>
            </div>
        </x-card>
    </div>
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="user-check" class="text-success mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $branches->sum(function($b) { return $b->agents_count ?? 0; }) }}</h4>
                <small class="text-muted">Total Agents</small>
            </div>
        </x-card>
    </div>
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="users" class="text-info mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $branches->sum(function($b) { return $b->students_count ?? 0; }) }}</h4>
                <small class="text-muted">Total Students</small>
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
        
        // Branch search functionality
        const branchSearch = document.getElementById('branchSearch');
        const clearBranchSearch = document.getElementById('clearBranchSearch');
        const branchTable = document.getElementById('branchTable');
        const rows = branchTable.querySelectorAll('tbody tr');
        
        if (branchSearch) {
            branchSearch.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const isVisible = text.includes(searchText);
                    row.style.display = isVisible ? '' : 'none';
                });
                
                // Update empty state visibility
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                const emptyRow = branchTable.querySelector('tr:has(.text-muted)');
                if (emptyRow) {
                    emptyRow.style.display = visibleRows.length === 0 && rows.length > 1 ? '' : 'none';
                }
            });
        }
        
        // Clear search
        if (clearBranchSearch) {
            clearBranchSearch.addEventListener('click', function() {
                if (branchSearch) branchSearch.value = '';
                rows.forEach(row => {
                    row.style.display = '';
                });
            });
        }
        
        // Export functionality
        const exportBranches = document.getElementById('exportBranches');
        if (exportBranches) {
            exportBranches.addEventListener('click', function() {
                // Simple CSV export
                let csv = 'Branch Name,Code,Address,City,Phone,Email\n';
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const cells = row.querySelectorAll('td');
                        const name = cells[1]?.textContent.trim() || '';
                        csv += `"${name}","","","","",""\n`;
                    }
                });
                
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'branches_export.csv';
                a.click();
                window.URL.revokeObjectURL(url);
                
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                alert.style.zIndex = '9999';
                alert.innerHTML = `
                    <i data-feather="check-circle" class="me-2"></i>
                    Branches exported successfully!
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
                if (branchSearch) branchSearch.focus();
            }
            
            if (e.key === 'Escape' && branchSearch && branchSearch.value) {
                branchSearch.value = '';
                rows.forEach(row => {
                    row.style.display = '';
                });
            }
        });
    });
</script>
@endpush
