@extends('layouts.app')

@section('title', 'States')

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
    title="State Management"
    subtitle="Manage states and regions within countries for student applications."
    :actions="
        '<a href=\"' . route('states.create') . '\" class=\"btn btn-primary\">
            <i data-feather=\"map-pin\" class=\"me-2\"></i> Add New State
        </a>'
    "
/>

<!-- Search and Filter Section -->
<x-card class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-8">
            <label class="form-label">Search States</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i data-feather="search"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       placeholder="Search by state name or country..." 
                       id="stateSearch">
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-grid gap-2 d-md-flex">
                <button type="button" class="btn btn-outline-secondary" id="clearStateSearch">
                    <i data-feather="x" class="me-1"></i> Clear
                </button>
                <button type="button" class="btn btn-outline-primary" id="exportStates">
                    <i data-feather="download" class="me-1"></i> Export
                </button>
            </div>
        </div>
    </div>
</x-card>

<!-- States Table -->
<x-card>
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="stateTable">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>State Name</th>
                    <th class="d-none d-md-table-cell">Country</th>
                    <th class="d-none d-lg-table-cell">Code</th>
                    <th class="d-none d-md-table-cell">Status</th>
                    <th class="text-center" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($states as $state)
                <tr>
                    <td>
                        <span class="badge bg-light text-dark fw-normal">{{ $loop->iteration }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                <i data-feather="map-pin" class="text-primary" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $state->name }}</div>
                                @if($state->code)
                                    <small class="text-muted">Code: {{ $state->code }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge bg-info text-dark">{{ $state->country->name ?? 'N/A' }}</span>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        @if($state->code)
                            <span class="badge bg-secondary">{{ $state->code }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge bg-success">Active</span>
                    </td>
                    <td class="text-center">
                        <x-action-buttons 
                            :edit-route="'states.edit'"
                            :show-route="'states.show'"
                            :delete-route="'states.destroy'"
                            :edit-id="$state->id"
                            :show-id="$state->id"
                            :delete-id="$state->id"
                            delete-confirm="Are you sure you want to delete this state? This will affect all associated data."
                        />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">
                            <i data-feather="map-pin" style="width: 48px; height: 48px;"></i>
                            <h5 class="mt-3 mb-2">No states found</h5>
                            <p class="mb-3">Start by adding your first state to organize geographical regions.</p>
                            <a href="{{ route('states.create') }}" class="btn btn-primary">
                                <i data-feather="map-pin" class="me-2"></i>Add First State
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if(method_exists($states, 'hasPages') && $states->hasPages())
    <div class="card-footer bg-white border-top">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <small class="text-muted mb-2 mb-md-0">
                Showing {{ $states->firstItem() }} to {{ $states->lastItem() }} of {{ $states->total() }} states
            </small>
            <div class="d-flex justify-content-center justify-content-md-end">
                {{ $states->links() }}
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
                <i data-feather="map-pin" class="text-primary mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $states->count() }}</h4>
                <small class="text-muted">Total States</small>
            </div>
        </x-card>
    </div>
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="globe" class="text-success mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $states->pluck('country_id')->unique()->count() }}</h4>
                <small class="text-muted">Countries Covered</small>
            </div>
        </x-card>
    </div>
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="users" class="text-info mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $states->sum(function($s) { return $s->students_count ?? 0; }) }}</h4>
                <small class="text-muted">Students Located</small>
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
        
        // State search functionality
        const stateSearch = document.getElementById('stateSearch');
        const clearStateSearch = document.getElementById('clearStateSearch');
        const stateTable = document.getElementById('stateTable');
        const rows = stateTable.querySelectorAll('tbody tr');
        
        if (stateSearch) {
            stateSearch.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const isVisible = text.includes(searchText);
                    row.style.display = isVisible ? '' : 'none';
                });
                
                // Update empty state visibility
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                const emptyRow = stateTable.querySelector('tr:has(.text-muted)');
                if (emptyRow) {
                    emptyRow.style.display = visibleRows.length === 0 && rows.length > 1 ? '' : 'none';
                }
            });
        }
        
        // Clear search
        if (clearStateSearch) {
            clearStateSearch.addEventListener('click', function() {
                if (stateSearch) stateSearch.value = '';
                rows.forEach(row => {
                    row.style.display = '';
                });
            });
        }
        
        // Export functionality
        const exportStates = document.getElementById('exportStates');
        if (exportStates) {
            exportStates.addEventListener('click', function() {
                // Simple CSV export
                let csv = 'State Name,Country,Code,Status\n';
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const cells = row.querySelectorAll('td');
                        const name = cells[1]?.textContent.trim() || '';
                        csv += `"${name}","","","Active"\n`;
                    }
                });
                
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'states_export.csv';
                a.click();
                window.URL.revokeObjectURL(url);
                
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                alert.style.zIndex = '9999';
                alert.innerHTML = `
                    <i data-feather="check-circle" class="me-2"></i>
                    States exported successfully!
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
                if (stateSearch) stateSearch.focus();
            }
            
            if (e.key === 'Escape' && stateSearch && stateSearch.value) {
                stateSearch.value = '';
                rows.forEach(row => {
                    row.style.display = '';
                });
            }
        });
    });
</script>
@endpush
