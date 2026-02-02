@extends('layouts.app')

@section('title', 'Countries')

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
    title="Registered Countries"
    subtitle="Manage countries and regions for student applications."
    :actions="'<a href=\"' . route('countries.create') . '\" class=\"btn btn-primary\"><i data-feather=\"plus\" class=\"me-2\"></i> Add New Country</a>'"
/>

<!-- Search and Filter Section -->
<x-card class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-8">
            <label class="form-label">Search Countries</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i data-feather="search"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       placeholder="Search by country name..." 
                       id="tableSearch">
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-grid gap-2 d-md-flex">
                <button type="button" class="btn btn-outline-secondary" id="clearSearch">
                    <i data-feather="x" class="me-1"></i> Clear
                </button>
                <button type="button" class="btn btn-outline-primary" id="exportBtn">
                    <i data-feather="download" class="me-1"></i> Export
                </button>
            </div>
        </div>
    </div>
</x-card>

<!-- Countries Table -->
<x-card>
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="countryTable">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Country Name</th>
                    <th class="d-none d-md-table-cell">Code</th>
                    <th class="d-none d-lg-table-cell">Status</th>
                    <th class="text-center" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($countries as $country)
                <tr>
                    <td>
                        <span class="badge bg-light text-dark fw-normal">{{ $loop->iteration }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                <i data-feather="globe" class="text-primary" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $country->name }}</div>
                                @if($country->states_count ?? false)
                                    <small class="text-muted">{{ $country->states_count }} states</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        @if($country->code)
                            <span class="badge bg-secondary">{{ $country->code }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <span class="badge bg-success">Active</span>
                    </td>
                    <td class="text-center">
                        <x-action-buttons 
                            :edit-route="'countries.edit'"
                            :show-route="'countries.show'"
                            :delete-route="'countries.destroy'"
                            :edit-id="$country->id"
                            :show-id="$country->id"
                            :delete-id="$country->id"
                            delete-confirm="Are you sure you want to delete this country? This will also delete all associated states."
                        />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted">
                            <i data-feather="globe" style="width: 48px; height: 48px;"></i>
                            <h5 class="mt-3 mb-2">No countries found</h5>
                            <p class="mb-3">Start by adding your first country to the system.</p>
                            <a href="{{ route('countries.create') }}" class="btn btn-primary">
                                <i data-feather="plus" class="me-2"></i>Add First Country
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if(method_exists($countries, 'hasPages') && $countries->hasPages())
    <div class="card-footer bg-white border-top">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <small class="text-muted mb-2 mb-md-0">
                Showing {{ $countries->firstItem() }} to {{ $countries->lastItem() }} of {{ $countries->total() }} countries
            </small>
            <div class="d-flex justify-content-center justify-content-md-end">
                {{ $countries->links() }}
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
                <i data-feather="globe" class="text-primary mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $countries->count() }}</h4>
                <small class="text-muted">Total Countries</small>
            </div>
        </x-card>
    </div>
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="map-pin" class="text-success mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $countries->sum(function($c) { return $c->states_count ?? 0; }) }}</h4>
                <small class="text-muted">Total States</small>
            </div>
        </x-card>
    </div>
    <div class="col-md-4">
        <x-card class="text-center">
            <div class="py-3">
                <i data-feather="users" class="text-info mb-2" style="width: 32px; height: 32px;"></i>
                <h4 class="mb-1">{{ $countries->sum(function($c) { return $c->students_count ?? 0; }) }}</h4>
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
        
        // Table search functionality
        const tableSearch = document.getElementById('tableSearch');
        const clearSearch = document.getElementById('clearSearch');
        const countryTable = document.getElementById('countryTable');
        const rows = countryTable.querySelectorAll('tbody tr');
        
        if (tableSearch) {
            tableSearch.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const isVisible = text.includes(searchText);
                    row.style.display = isVisible ? '' : 'none';
                });
                
                // Update empty state visibility
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                const emptyRow = countryTable.querySelector('tr:has(.text-muted)');
                if (emptyRow) {
                    emptyRow.style.display = visibleRows.length === 0 && rows.length > 1 ? '' : 'none';
                }
            });
        }
        
        // Clear search
        if (clearSearch) {
            clearSearch.addEventListener('click', function() {
                if (tableSearch) tableSearch.value = '';
                rows.forEach(row => {
                    row.style.display = '';
                });
            });
        }
        
        // Export functionality
        const exportBtn = document.getElementById('exportBtn');
        if (exportBtn) {
            exportBtn.addEventListener('click', function() {
                // Simple CSV export
                let csv = 'Country Name,Code,Status\n';
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const cells = row.querySelectorAll('td');
                        const countryName = cells[1]?.textContent.trim() || '';
                        const code = cells[2]?.textContent.trim() || '';
                        csv += `"${countryName}","${code}","Active"\n`;
                    }
                });
                
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'countries_export.csv';
                a.click();
                window.URL.revokeObjectURL(url);
                
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                alert.style.zIndex = '9999';
                alert.innerHTML = `
                    <i data-feather="check-circle" class="me-2"></i>
                    Countries exported successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alert);
                feather.replace();
                
                setTimeout(() => {
                    alert.remove();
                }, 3000);
            });
        }
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K for search focus
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                if (tableSearch) tableSearch.focus();
            }
            
            // Escape to clear search
            if (e.key === 'Escape' && tableSearch && tableSearch.value) {
                tableSearch.value = '';
                rows.forEach(row => {
                    row.style.display = '';
                });
            }
        });
        
        // Add row hover effects
        rows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(2px)';
                this.style.transition = 'transform 0.2s ease';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
    });
</script>
@endpush
